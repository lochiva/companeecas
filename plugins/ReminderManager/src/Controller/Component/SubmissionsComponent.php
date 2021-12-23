<?php
namespace ReminderManager\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Controller\ComponentRegistry;

use ReminderManager\Shell\MailerShell;

class SubmissionsComponent extends Component
{

  private $status = [];

  public $components = array('Mailer');

  public function beforeFilter(){

    $this->status[0] = "Salvato";
    $this->status[1] = "Da Inviare";
    $this->status[2] = "In corso";
    $this->status[3] = "Inviato";
    $this->status[4] = "Sospeso";
    $this->status[5] = "Errore";

  }

  public function setNewTypeTemplate($data){

    $attributes = TableRegistry::get('ReminderManager.SubmissionsAttributes');

    $attrId = $attributes->getAttributeId($data['attribute']);

    if($attrId !== false){

      $types = TableRegistry::get('ReminderManager.SubmissionsType');

      $typeId = $types->setNewType(['name' => $data['type']]);

      if($typeId !== false){

        $submissionsTypeSubmissionsAttributes = TableRegistry::get('ReminderManager.SubmissionsTypeSubmissionsAttributes');

        $item = $submissionsTypeSubmissionsAttributes->newEntity();

        $item->id_submission_type = $typeId;
        $item->id_submission_attribute = $attrId;

        if($submissionsTypeSubmissionsAttributes->save($item)){

          //Salvo il template
          $submissionsTemplates = TableRegistry::get('ReminderManager.SubmissionsTemplates');
          $template = $submissionsTemplates->newEntity();

          $template->title = $data['title'];
          $template->object = $data['object'];
          $template->text = $data['body'];
          $template->template = $data['template'];
          $template->id_type = $typeId;

          if($submissionsTemplates->save($template)){

            $types = $this->getTypeTemplate($data['attribute']);
            //debug($types);
            foreach ($types['data'] as $key => $value) {
              if($value['id'] == $typeId){
                $types['data'][$key]['selected'] = true;
              }
            }

            return array('response' => 'OK', 'data' => $types['data'] , 'msg' => "ok");

          }else{ // Salvataggio template fallito

            // Rollback
            $type = $types->get($typeId);
            $res = $types->delete($type);

            $res = $submissionsTypeSubmissionsAttributes->delete($item);

            return array('response' => 'KO', 'data' => 1, 'msg' => "Errore nel salvataggio del nuovo tipo e attributo, si prega di contattare il gestore. [ER08]");

          }

        }else{ // Salvataggio molti a molti fallito

          // Rollback
          $type = $types->get($typeId);
          $res = $types->delete($type);
          return array('response' => 'KO', 'data' => 1, 'msg' => "Errore nel salvataggio del nuovo tipo e attributo, si prega di contattare il gestore. [ER07]");

        }

      }else{ // errore nell'inserire il nuovo tipo
        return array('response' => 'KO', 'data' => 1, 'msg' => "Errore nel salvataggio del nuovo tipo, si prega di contattare il gestore. [ER06]");
      }

    }else{ // Non esiste l'attributo per cui si vuole creare il template
      return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: l'attributo passato non è valido, si prega di contattare il gestore. [ER05]");
    }

  }

  public function getTypeTemplate($attribute){

    $submissionType = TableRegistry::get('ReminderManager.SubmissionsType');

    $types = $submissionType->getTypeByAttributes($attribute);

    $typesSubmissions[] = [
      'id' => 0,
      'text' => 'Nuova'
    ];

    foreach ($types as $key => $type) {
      $typesSubmissions[] = [
        'id' => $type['id'],
        'text' => $type['name']
      ];
    }
    //debug($typesSubmissions);
    return array('response' => 'OK', 'data' => $typesSubmissions, 'msg' => "ok");

  }

  public function getTemplateByType($id){

    $submissionType = TableRegistry::get('ReminderManager.SubmissionsTemplates');

    $res = $submissionType->find()->where(['SubmissionsTemplates.id_type' => $id])->toArray();

    return array('response' => 'OK', 'data' => (!empty($res[0])?$res[0]:false), 'msg' => "ok");

  }

  public function deleteFileSubmission($file){

    $pathFiles = Configure::read('reminderConfig.path_file_to_manage');

    if(!empty($pathFiles)){

      if(file_exists($pathFiles . $file)){
        unlink($pathFiles . $file);
      }

      return array('response' => 'OK', 'data' => 1, 'msg' => "ok");

    }else{ // Config del path vuoto
      return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: config pathFile mancante, si prega di contattare il gestore. [ER11]");
    }

  }

  public function saveNewSubmission($data){

    $numInvii = 0;

    if(!empty($data['submissions']['attribute'])){

      // Comincio a salvare i dati della Submission
      $sub = $this->saveSubmission($data['submissions']);

      // Salvo gli allegati della Submissions

      if(!empty($data['file']) && $data['file'] != 'undefined'){

        //debug($data['file']); die();
        $res = $this->uploadFile($data['file']);

        //debug($res);

        if($res){

          $file = ['name' => $data['file']['name'], 'path' => $res];
          $this->saveSubmissionsAttachments($sub->id,$file);

        }

      }

      //die();
      //debug($sub);

      if($sub){

        // Proseguo solo se non sono un update di uno schedino, in questo caso non serve proseguire...

        if(($data['submissions']['attribute'] == "SCHEDINI" && !isset($data['submissions']['idSubmission'])) || ($data['submissions']['attribute'] != "SCHEDINI")){

          // Eseguo operazioni custom sulle submissions_mails in base all'attributo della submissions. Attenzione, i dati sono passati per riferimento

          $sub_mails = $this->customFilterForAttribute($data['submissions']['attribute'],$data['submissions_emails']);

          //debug($sub_mails);

          // #######################################################################################################################
          // Se sto facendo update devo prima cancellare tutti le vecchie mail e poi inserisco quelle nuove...
          if(isset($data['submissions']['idSubmission']) && $data['submissions']['idSubmission'] != ""){

            $se = TableRegistry::get('ReminderManager.SubmissionsEmails');
            $se->deleteAll(['id_submission' => $data['submissions']['idSubmission']]);

          }

          // #######################################################################################################################

          if($sub_mails){

            // A questo punto per ogni mail posso salvare nel db i suoi dati

            foreach ($sub_mails as $key => $mail) {

              $mail['id_submission'] = $sub->id;
              $m = $this->saveSubmissionMail($mail);

              $numInvii ++;

              if($m){

                // #######################################################################################################################
                // Se stavo facendo update devo prima cancellare tutti i vecchi dati e poi inserisco quelli nuovi...
                if(isset($mail['id_email']) && $mail['id_email'] != ""){

                  $sea = TableRegistry::get('ReminderManager.SubmissionsEmailsAttachements');
                  $sea->deleteAll(['id_submission_email' => $mail['id_email']]);

                  $sec = TableRegistry::get('ReminderManager.SubmissionsEmailsCustoms');
                  $sec->deleteAll(['id_submission_email' => $mail['id_email']]);

                }
                // #######################################################################################################################

                if(!empty($mail['filename']) && !empty($mail['path'])){
                  $attach = "";
                  $attach[] = ['id_submission_email' => $m->id, 'filename' => $mail['filename'], 'path' => $mail['path']];
                  $ma = $this->saveSubmissionMailAttachment($attach);
                }

                if(!empty($mail['custom'])){
                  $mc = $this->saveSubmissionMailCustom($m->id, $mail['custom']);
                }

              }

            }

            return array('response' => 'OK', 'data' => ['submission' => $sub, 'numProcessedMail' => $numInvii], 'msg' => "Invio salvato con successo con  $numInvii invii schedulati");

          }else{
            // Rollback

            $this->deleteSubmission($sub);

            return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: impossibile eseguire le operazioni custom, si prega di contattare il gestore. [ER14]");
          }
        }else{
          return array('response' => 'OK', 'data' => ['submission' => $sub], 'msg' => "Invio salvato con successo");
        }

      }else{
        return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: impossibile salvare la testa del mailing, si prega di contattare il gestore. [ER13]");
      }

    }else{ // manca l'attributo
      return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: attributo mancante, si prega di contattare il gestore. [ER14]");
    }

  }

  public function saveSubmissionsAttachments($id_submission,$file){

    $submissionsAttachments = TableRegistry::get('ReminderManager.SubmissionsAttachements');

    $submissionAttachment = $submissionsAttachments->newEntity();

    $submissionAttachment->id_submission = $id_submission;
    $submissionAttachment->filename = $file['name'];
    $submissionAttachment->path = $file['path'];

    if($submissionsAttachments->save($submissionAttachment)){
      return true;
    }else{
      return false;
    }

  }

  public function uploadFile($file){

		$res = false;

    $path = Configure::read('reminderConfig.path_file_uploaded');

		$year = date('Y');
		$fileSuffix = date('YmdHis');

		$savePath = $path.$year;
    //debug($savePath);
		//se il path non esiste lo creo
		if(!is_dir($savePath)){
			mkdir($savePath, 0775, true);
		}

    $fileName = $this->generateUniqueFileName($file['name']);
		//Copio il file
		$dest = $savePath .'/'. $fileName;
        if(copy($file['tmp_name'], $dest)){
            $res =  $dest;
        }

		return $res;

	}


  private function saveSubmission($data){

    //debug($data); die();

    if(!empty($data['title']) && !empty($data['object']) && !empty($data['body'])){

      $this->controller = $this->_registry->getController();
      $this->session = $this->controller->request->session();


      $submissions = TableRegistry::get('ReminderManager.Submissions');

      $submission = $submissions->newEntity();

      if(!empty($data['idSubmission']) && $data['idSubmission'] > 0){
        $submission->id = $data['idSubmission'];
      }

      if(isset($data['status']) && $data['status'] > 0){
        $status = $data['status'];
        $status_text = $this->status[$status];

        if($status == 1){
          // devo salvare anche l'utente che determina l'invio

          $submission->id_user_sended = !empty($this->session->read('Auth.User.id'))?$this->session->read('Auth.User.id'):'';
        }

      }else{
        $status = 0;
        $status_text = $this->status[0];
      }

      //debug($status); debug($this->status); die();

      $submission->attribute = $data['attribute'];
      $submission->id_submission_type = $data['typeSubmission'];
      $submission->name = $data['title'];
      $submission->sender_email = $data['sender_email'];
      $submission->object = $data['object'];
      $submission->text = $data['body'];
      $submission->template = $data['template'];
      $submission->status = $status;
      $submission->status_text = $status_text;
      $submission->id_user_created = !empty($this->session->read('Auth.User.id'))?$this->session->read('Auth.User.id'):'';

      //debug($submission); die();

      if($submissions->save($submission)){
        return $submission;
      }else{
        return false;
      }

    }else{
      return false;
    }

  }

  private function customFilterForAttribute($attribute,$mails){

    switch($attribute){

      case 'SCHEDINI':

        // questo attributo prevede di dover copiare i file dalla cartella comune a quella di archivio e restituire il filepath completo del nuovo file.

        $pathFileTo = Configure::read('reminderConfig.path_file_for_schedini');
        $pathFileFrom = Configure::read('reminderConfig.path_file_to_manage');

        $mailsToRet = [];

        if(!empty($pathFileTo) && !empty($pathFileFrom)){

          foreach ($mails as $key => $mail) {

            if(file_exists($pathFileFrom . $mail['filename'])){

              $newFileName = $this->generateUniqueFileName($mail['filename']);
              $newFilePath = $pathFileTo . (isset($mail['customPath'])?$mail['customPath']:'');
              //debug($newFilePath);
              //debug($newFileName);

              if(!file_exists($newFilePath)){
                if (!mkdir($newFilePath, 0777, true)) {
                    die('Failed to create folders...');
                }
              }

              if(copy($pathFileFrom.$mail['filename'], $newFilePath.$newFileName)){

                $mailsToRet[$key]['name'] = $mail['name'];
                $mailsToRet[$key]['email'] = $mail['email'];
                $mailsToRet[$key]['filename'] = $newFileName;
                $mailsToRet[$key]['path'] = $newFilePath.$newFileName;
                $mailsToRet[$key]['custom'] = !empty($mail['custom'])?$mail['custom']:'';

                unlink($pathFileFrom . $mail['filename']);

              }

            }

          }

          return $mailsToRet;

        }else{
          return false;
        }

      break;

      default:
        return $mails;
      break;

    }

  }

  private function generateUniqueFileName($filename){

    $name = substr($filename,0,-4);
    $ext = substr($filename,-3);

    return $name . "_" . date('YmdHis') . '.' . $ext;

  }

  private function deleteSubmission($sub){

    $submissions = TableRegistry::get('ReminderManager.Submissions');
    $submissions->delete($sub);

  }

  private function saveSubmissionMail($mail){

    $submissionsEmails = TableRegistry::get('ReminderManager.SubmissionsEmails');

    $submissionEmail = $submissionsEmails->newEntity();

    if(isset($mail['id_email'])){
      $submissionEmail->id = $mail['id_email'];
    }

    $submissionEmail->id_submission = $mail['id_submission'];
    $submissionEmail->name = $mail['name'];
    $submissionEmail->email = $mail['email'];

    if($submissionsEmails->save($submissionEmail)){
      return $submissionEmail;
    }else{
      return false;
    }


  }

  private function saveSubmissionMailAttachment($attach){

    $submissionsEmailsAttachments = TableRegistry::get('ReminderManager.SubmissionsEmailsAttachements');

    foreach ($attach as $key => $a) {

      unset($submissionEmailAttachment);
      $submissionEmailAttachment = $submissionsEmailsAttachments->newEntity();

      $submissionEmailAttachment->id_submission_email = $a['id_submission_email'];
      $submissionEmailAttachment->path = $a['path'];
      $submissionEmailAttachment->filename = $a['filename'];

      $submissionsEmailsAttachments->save($submissionEmailAttachment);

    }


  }

  private function saveSubmissionMailCustom($idSubmissionEmail, $custom){

    $submissionsEmailsCustom = TableRegistry::get('ReminderManager.SubmissionsEmailsCustoms');

    foreach ($custom as $key => $value) {

      $submissionEmailCustom = $submissionsEmailsCustom->newEntity();
      $submissionEmailCustom->id_submission_email = $idSubmissionEmail;
      $submissionEmailCustom->custom_key = $key;
      $submissionEmailCustom->custom_value = $value;

      $submissionsEmailsCustom->save($submissionEmailCustom);

    }

  }

  public function getSubmissions($filters){

    $submissions = TableRegistry::get('ReminderManager.Submissions');

    $sub = $submissions->getSubmissions($filters, false);
    $subTot = count($submissions->getSubmissions($filters, true));

    $rows = [];
    foreach($sub as $s){

      $comp = "";
      $btnAction = '<a href="' . Router::url('/reminder_manager/submission/detail/' . $s['id']) . '" class="btn btn-info" title="Dettaglio"><i class="fa fa-pencil-square-o "></i></a>';

      if($s['stato'] == 0){ //salvato
        $comp = '<span class="badge bg-blue badge-list-submissions" title="Salvato">' . $s['completamento'] . ' %</span>';
      }elseif($s['stato'] == 1){ // da inviare
        $comp = '<span class="badge bg-purple badge-list-submissions" title="Da Inviare">' . ($s['completamento']>0?$s['completamento']:'1') . ' %</span>';
        $btnAction .= ' <a href="#" class="btn btn-stop btn-stop" title="Sospendi" data-id="' . $s['id'] . '"><i class="fa fa-stop "></i></a>';
      }elseif($s['stato'] == 2){ // in corso
        $comp = '<span class="badge bg-orange badge-list-submissions" title="In Corso">' . $s['completamento'] . ' %</span>';
        $btnAction .= ' <a href="#" class="btn btn-stop btn-stop" title="Sospendi" data-id="' . $s['id'] . '"><i class="fa fa-stop "></i></a>';
      }elseif($s['stato'] == 3){ // inviato
        $comp = '<span class="badge bg-green badge-list-submissions" title="Terminato">' . $s['completamento'] . ' %</span>';
        $btnAction .= ' <a href="#" class="btn btn-info btn-clone" title="Clona" data-id="' . $s['id'] . '"><i class="fa fa-clone "></i></a>';
      }elseif($s['stato'] == 4){ // sospeso
        $comp = '<span class="badge bg-maroon badge-list-submissions" title="Sospeso">' . $s['completamento'] . ' %</span>';
        $btnAction .= ' <a href="#" class="btn btn-success btn-restart" title="Riprendi" data-id="' . $s['id'] . '"><i class="fa fa-play "></i></a>';
      }elseif($s['stato'] == 5){ // errore
        $comp = '<span class="badge bg-red badge-list-submissions" title="Errore"><i class="fa fa-exclamation-triangle"></i> ' . $s['completamento'] . ' % <i class="fa fa-exclamation-triangle"></i></span>';
        $btnAction = '<a href="' . Router::url('/reminder_manager/submission/detail/' . $s['id']) . '" class="btn btn-danger" title="Dettaglio"><i class="fa fa-pencil-square-o "></i></a>';
      }

      $rows[] = [
        $s['data']->format('d/m/Y H:i:s'),
        "[" . $s['attributo'] . "] " . (!empty($s['tipo'])? $s['tipo']: $s['name']),
        '<p class="destinatari">' . $s['destinatari'] . '</p>',
        $comp,
        $btnAction
      ];

    }

    $out['total_rows'] = $subTot;
    $out['rows'] = $rows;

    //debug($out);

    return $out;

  }

  public function getSubmissionDetail($data){

    $submissions = TableRegistry::get('ReminderManager.Submissions');

    $sub = $submissions->getSubmissionDetail($data);

    //debug($sub);

    return array('response' => 'OK', 'data' => $sub[0], 'msg' => "Ok");

  }

  public function setNewStatusSubmission($status, $idSubmission){
    /*
    $s[0] = "Salvato";
    $s[1] = "Da Inviare";
    $s[2] = "In corso";
    $s[3] = "Inviato";
    $s[4] = "Sospeso";
    $s[5] = "Errore";
    */
    if(isset($this->status[$status])){

      $submissions = TableRegistry::get('ReminderManager.Submissions');

      $sub = $submissions->get($idSubmission);

      //debug($sub);

      $sub->status = $status;
      $sub->status_text = $this->status[$status];

      if($status == 1 && $sub->id_user_sended != 0){
        // devo salvare anche l'utente che determina l'invio
        $this->controller = $this->_registry->getController();
        $this->session = $this->controller->request->session();

        $sub->id_user_sended = !empty($this->session->read('Auth.User.id'))?$this->session->read('Auth.User.id'):'';

      }

      if($submissions->save($sub)){
        return array('response' => 'OK', 'data' => $sub, 'msg' => "Stato dell'invio salvato correttamente");
      }else{
        return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: impossibile salvare il nuovo status, si prega di contattare l'amministratore [ER19]");
      }

    }else{
      return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: status inesistente, si prega di contattare l'amministratore [ER18]");
    }

  }

  public function cloneSubmission($idSubmission){

    // ##################################################################################################################
    // Comincio a clonare la Submission

    $this->controller = $this->_registry->getController();
    $this->session = $this->controller->request->session();

    $submissions = TableRegistry::get('ReminderManager.Submissions');
    $sub = $submissions->get($idSubmission);
    $newSub = $submissions->newEntity();


    $newSub->status = 0;
    $newSub->status_text = $this->status[0];
    $newSub->id_user_created = !empty($this->session->read('Auth.User.id'))?$this->session->read('Auth.User.id'):'';
    $newSub->attribute = $sub->attribute;
    $newSub->id_submission_type = $sub->id_submission_type;
    $newSub->sender_email = $sub->sender_email;
    $newSub->name = $sub->name;
    $newSub->object = $sub->object;
    $newSub->text = $sub->text;

    //debug($newSub);

    if($submissions->save($newSub)){

      // Clone le mail da inviare
      $submissionsEmails = TableRegistry::get('ReminderManager.SubmissionsEmails');
      $emails = $submissionsEmails->find('all')->where(['id_submission' => $sub->id])->toArray();

      //debug($emails);

      foreach ($emails as $key => $email) {

        unset($newEmails);
        $newEmails = $submissionsEmails->newEntity();
        $newEmails->id_submission = $newSub->id;
        $newEmails->name = $email->name;
        $newEmails->email = $email->email;

        if($submissionsEmails->save($newEmails)){

          // Clono gli allegati alla mail
          $submissionsEmailsAttachements = TableRegistry::get('ReminderManager.SubmissionsEmailsAttachements');
          $attachements = $submissionsEmailsAttachements->find('all')->where(['id_submission_email' => $email->id])->toArray();

          //debug($attachements);

          foreach ($attachements as $key => $attach) {

            unset($newAttachements);
            $newAttachements = $submissionsEmailsAttachements->newEntity();
            $newAttachements->id_submission_email = $newEmails->id;
            $newAttachements->filename = $attach->filename;
            $newAttachements->path = $attach->path;

            $submissionsEmailsAttachements->save($newAttachements);

          }

          // Clono i campi custom
          $submissionsEmailsCustoms = TableRegistry::get('ReminderManager.SubmissionsEmailsCustoms');
          $customs = $submissionsEmailsCustoms->find('all')->where(['id_submission_email' => $email->id])->toArray();

          //debug($customs);

          foreach ($customs as $key => $custom) {

            unset($newCustom);
            $newCustom = $submissionsEmailsCustoms->newEntity();
            $newCustom->id_submission_email = $newEmails->id;
            $newCustom->custom_key = $custom->custom_key;
            $newCustom->custom_value = $custom->custom_value;

            $submissionsEmailsCustoms->save($newCustom);

          }

        }

      }


      return array('response' => 'OK', 'data' => 1, 'msg' => "Invio clonato correttamente");


    }else{
      return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: impossibile clonare la submission, si prega di contattare l'amministratore [ER21]");
    }


  }

  public function setMailStatus($id, $status){

    if($this->Mailer->setMailStatus($id,$status)){
      return array('response' => 'OK', 'data' => 1, 'msg' => "Invio salvato correttamente");
    }else{
      return array('response' => 'KO', 'data' => 1, 'msg' => "Errore: impossibile salvare l'invio, si prega di contattare l'amministratore [ER23]");
    }

  }

  public function getEmailAttachment($id){

    $submissionsEmailsAttachments = TableRegistry::get('ReminderManager.SubmissionsEmailsAttachements');
    $attachments = $submissionsEmailsAttachments->find('all')->where(['id_submission_email' => $id])->toArray();

    return $attachments[0]['path'];

  }

  public function getAttachment($id){

    $submissionsAttachments = TableRegistry::get('ReminderManager.SubmissionsAttachements');
    $attachments = $submissionsAttachments->find('all')->where(['id_submission' => $id])->toArray();

    return $attachments[0]['path'];

  }

  public function getSubmissionsByCustom($cKey,$cValue){

    $submissionsEmailsCustoms = TableRegistry::get('ReminderManager.SubmissionsEmailsCustoms');

    $subs = $submissionsEmailsCustoms->getSubmissions($cKey,$cValue);
    //debug($subs);
    $subsToRet = [];
    foreach ($subs as $key => $sub) {

      $subsToRet[$key] = [
        'name' => $sub['SubmissionsEmails']['Submissions']['name'],
        'submissionStatus' => $sub['SubmissionsEmails']['Submissions']['status'],
        'submissionDate' => is_object($sub['SubmissionsEmails']['Submissions']['created']) ? $sub['SubmissionsEmails']['Submissions']['created']->i18nFormat('d/M/Y HH:mm'): "--/--/--",
        'submissionEmailSended' => $sub['SubmissionsEmails']['sended'],
        'submissionEmailEmail' =>$sub['SubmissionsEmails']['email'],
        'submissionId' => $sub['SubmissionsEmails']['Submissions']['id'],
        'submissionEmailId' => $sub['SubmissionsEmails']['id']
      ];
      // Aggiungo i link per ogni allegato e submission
      if(!empty($sub['SubmissionsEmails']['SubmissionsEmailsAttachements'])){
        $subsToRet[$key]['linkAttachmentEmail'] = Router::url('/reminder_manager/ws/getEmailAttachment/' . $sub['SubmissionsEmails']['id'] );
      }else{
        $subsToRet[$key]['linkAttachmentEmail'] = "";
      }

      if(!empty($sub['SubmissionsEmails']['Submissions']['SubmissionsAttachements'])){
        $subsToRet[$key]['linkAttachment'] = Router::url('/reminder_manager/ws/getAttachment/' . $sub['SubmissionsEmails']['Submissions']['id'] );
      }else{
        $subsToRet[$key]['linkAttachment'] = "";
      }

      $subsToRet[$key]['linkSubmission'] = Router::url('/reminder_manager/submission/detail/' . $sub['SubmissionsEmails']['Submissions']['id'] );

    }

    //debug($subsToRet);

    return $subsToRet;

  }

  public function getPossibleRecipients($data){

    $aziende = TableRegistry::get('Aziende.Aziende');
    $res = $aziende->getAziendeRecipient($data['offices'],$data['partners']);

    //debug($res);

    return array('response' => 'OK', 'data' => ['aziende' => $res], 'msg' => "ok");

  }

  public function deleteAttachmentById($id){

    $sa = TableRegistry::get('ReminderManager.SubmissionsAttachements');
    $sa->deleteAll(['id' => $id]);

    return array('response' => 'OK', 'data' => 1, 'msg' => "ok");


  }

}
