<?php
namespace ReminderManager\Controller;

use ReminderManager\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

use ReminderManager\Shell\MailerShell;
use Cake\Console\ShellDispatcher;


/**
 * Ws Controller
 *
 * @property \ReminderManager\Model\Table\WsTable $Ws */
class WsController extends AppController
{

  public function initialize()
  {
      parent::initialize();
      $this->loadComponent('ReminderManager.Sispac');
      $this->loadComponent('ReminderManager.Submissions');
      $this->loadComponent('ReminderManager.Mailer');
  }

  public function beforeFilter(Event $event)
  {
      parent::beforeFilter($event);
      //$this->Auth->allow(['getCalendarEvents', 'saveEvent','deleteEvent']);

      $this->layout = 'ajax';
      $this->viewPath = 'Async';
      $this->view = 'default';
      $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

  }

  public function beforeRender(Event $event) {
      parent::beforeFilter($event);
      $this->set('result', json_encode($this -> _result));
  }

  public function getSubmissions(){

    $data = $this->request->query;

    //debug($data);

    if(!empty($data)){

      $this->_result = $this->Submissions->getSubmissions($data);

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri [ER15]");
    }

  }

  public function getClientsSchedini(){

    $pathFiles = Configure::read('reminderConfig.path_file_to_manage');

    if($pathFiles != ""){

      //debug($pathFiles);

      if(file_exists($pathFiles)) {

        // escludo le dir . e ..
        $files = array_diff(scandir($pathFiles), array('.', '..'));

        //escludo le altre dir
        foreach($files as $key => $file){
            if (is_dir($pathFiles.$file)){
                unset($files[$key]);
            }
        }

        $files = array_values($files);

        //debug($files);

        if(count($files)>0){

          $aziende = $this->loadModel('Aziende.Aziende');

          foreach ($files as $key => $file) {

            $sispac = $this->Sispac->getSispacFromFileName($file);

            $azienda = $aziende->getAziendaFromSispac($sispac);

            $list[$key]['id'] = isset($azienda['id'])?$azienda['id']:'';
            $list[$key]['denominazione'] = isset($azienda['denominazione'])?$azienda['denominazione']:'';
            $list[$key]['email_contabilita'] = isset($azienda['email_contabilita'])?$azienda['email_contabilita']:'';
            $list[$key]['cod_sispac'] = isset($azienda['cod_sispac'])?$azienda['cod_sispac']:'';
            $list[$key]['filename'] =  $file;

          }

          //debug($list);

          $this->_result = array('response' => 'OK', 'data' => $list, 'msg' => "ok");

        }else{ // Gestione 0 file
          $this->_result = array('response' => 'OK', 'data' => [], 'msg' => "Non ci sono file Schedini da inviare");
        }

      }else{ // Gestione file exist
        $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore directory inesistente dei file Schedini, si prega di contattare l'amministratore del sistema [ER01]");
      }

    }else{ // Gestione $pathFiles == ""
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore di configurazione per la ricerca dei file Schedini, si prega di contattare l'amministratore del sistema [ER02]");
    }

  }

  public function getTypeTemplate(){

    $data = $this->request->query;

    //debug($data);

    if(!empty($data['attribute'])){

      $this->_result = $this->Submissions->getTypeTemplate($data['attribute']);

    }else{ // attribute empty
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore sulla tipologia di attributo da caricare, si prega di contattare l'amministratore del sistema [ER03]");
    }

  }

  public function setNewTypeTemplate(){

    $data = $this->request->data;

    //debug($data);

    if(!empty($data['type']) && !empty($data['title']) && !empty($data['object']) && !empty($data['body']) && !empty($data['attribute']) && !empty($data['template'])){

      $this->_result = $this->Submissions->setNewTypeTemplate($data);

    }else{ // Check sui dati pieni
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: dati non sufficenti. [ER04]");
    }

  }

  public function getTemplateByType(){

    $data = $this->request->query;

    //debug($data);

    if(!empty($data['id'])){

      $this->_result = $this->Submissions->getTemplateByType($data['id']);

    }else{ // attribute empty
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore identificativo template non ricevuto, si prega di contattare l'amministratore del sistema [ER09]");
    }

  }

  public function deleteFile(){

    $data = $this->request->query;

    //debug($data);

    if(!empty($data['file'])){

      $this->_result = $this->Submissions->deleteFileSubmission($data['file']);

    }else{ // attribute empty
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore file mancante, si prega di contattare l'amministratore del sistema [ER10]");
    }

  }


  public function saveSubmission(){

    $data = $this->request->data;

    //debug($data); die();

    if(isset($data['submissions']) && !is_array($data['submissions'])){
      $data['submissions'] = json_decode($data['submissions'],true);
    }

    if(isset($data['submissions_emails']) && !is_array($data['submissions_emails'])){
      $data['submissions_emails'] = json_decode($data['submissions_emails'],true);
    }

    //debug($data); die();

    if(!empty($data['submissions']) && (!empty($data['submissions_emails']) || !empty($data['submissions']['idSubmission']))){

      $res = $this->Submissions->saveNewSubmission($data);

      // Se il salvataggio prevedeva di far partire anche una mail di test la invio....
      if($res['response'] == 'OK' && !empty($data['email_test'])){

        $test = $this->Mailer->sendEmailTest($res['data']['submission']['id'],$data['email_test']);

        if($test){
          $res['msg'] .= '. Invio mail di test avvenuto con successo!';
        }else{
          $res['msg'] .= ". ATTENZIONE: Errore nell'invio della mail di test!";
        }

      }

      // Se il salvataggio prevedeva di far partire anch eil mailer faccio partire il robot per l'invio....
      if($res['response'] == 'OK' && (!empty($data['submissions']['status']) && $data['submissions']['status'] == 1)){

        $res = $this->Mailer->start();

      }

      $this->_result = $res;

    }else{ // Dati mancanti

      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore dati mancanti per il salvataggio, si prega di contattare l'amministratore del sistema [ER12]");

    }

  }

  public function getSubmissionDetail(){

    $data = $this->request->query;

    //debug($data);

    if(!empty($data['id'])){

      $this->_result = $this->Submissions->getSubmissionDetail($data);

    }else{ // id mancante
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: id mancante per dettaglio submission, si prega di contattare l'amministratore del sistema [ER16]");
    }

  }

  public function changeStatusSubmission(){

    $data = $this->request->data;

    //debug($data); //die();

    if(isset($data['newStatus']) && !empty($data['idSubmission'])){

      $res = $this->Submissions->setNewStatusSubmission($data['newStatus'],$data['idSubmission']);

      if($res['response'] == 'OK' && $data['newStatus'] == 1){

        $res = $this->Mailer->start();

      }

      $this->_result = $res;

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: newStatus o id mancante per dettaglio submission, si prega di contattare l'amministratore del sistema [ER17]");
    }

  }

  public function cloneSubmission(){

    $data = $this->request->data;

    //debug($data);

    if(!empty($data['idSubmission'])){
      $this->_result = $this->Submissions->cloneSubmission($data['idSubmission']);
    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: id mancante per clonare submission, si prega di contattare l'amministratore del sistema [ER20]");
    }

  }

  public function shellTestMail(){

    echo "ECCOMI QUI!!!!!</br>";

    $shell = new ShellDispatcher();
    $output = $shell->run(['cake', 'mailer','testSendMail','m.blua@itoa.it']);

    /*
    $shell = new MailerShell();

    $cmd = ROOT.DS.'bin'.DS.'cake mailer testSendMail m.blua@itoa.it';

    $shell->exec_background($cmd);

    debug($cmd);
    */

  }

  public function startMailer(){

    $res = $this->Mailer->start();

    $this->_result = $res;

  }

  public function setMailStatus(){

    $data = $this->request->data;

    //debug($data);

    if(!empty($data['id']) && isset($data['status']) && ($data['status'] == 0 || $data['status'] ==2)){
      $this->_result = $this->Submissions->setMailStatus($data['id'],$data['status']);
    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: id mancante o stato errato per saltare invio, si prega di contattare l'amministratore del sistema [ER22]");
    }

  }

  public function getEmailAttachment($id=0){

    if(!empty($id)){
      $path = $this->Submissions->getEmailAttachment($id);

      header('Content-Disposition: attachment; filename=' . basename($path));
      readfile($path);

      die();

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: id mancante, si prega di contattare l'amministratore del sistema [ER23]");
    }

  }

  public function getAttachment($id=0){

    if(!empty($id)){
      $path = $this->Submissions->getAttachment($id);

      header('Content-Disposition: attachment; filename=' . basename($path));
      readfile($path);

      die();

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: id mancante, si prega di contattare l'amministratore del sistema [ER26]");
    }

  }

  public function getSubmissionsByCustom($cKey = "", $cValue = ""){

    if(!empty($cKey)&&!empty($cValue)){

      $res = $this->Submissions->getSubmissionsByCustom($cKey,$cValue);
      $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "OK");

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: dati mancanti [ER24]");
    }

  }

  public function getSenderEmail(){

    //debug($this->request->session()->read('Auth.User'));

    $senderEmail = $this->request->session()->read('Auth.User.email');

    $this->_result = array('response' => 'OK', 'data' => ['sender_email' => $senderEmail], 'msg' => "OK");

  }

  /*public function getOffices(){

    $this->loadModel('Consulenza.Offices');

    $offices = $this->Offices->find('all')->order('name ASC')->toArray();
    $toRet = [];
    foreach ($offices as $key => $o) {
      $toRet[] = ['id'=>$o['id'],'text' => $o['name']];
    }

    $this->_result = array('response' => 'OK', 'data' => ['offices' => $toRet], 'msg' => "OK");

  }*/

  /*public function getPartners(){

    $users = TableRegistry::get('Consulenza.Users');
    $toRet = [];
    $res = $users->find('all')->where(['isPartner' => 1])->order('cognome ASC')->toArray();

    //debug($res);

    foreach ($res as $key => $u) {
      $toRet[] = [
        'id' => $u['id'],
        'text' => $u['cognome'] . " " . $u['nome']
      ];
    }

    $this->_result = array('response' => 'OK', 'data' => ['partners' => $toRet], 'msg' => "OK");

  }*/

  public function getPossibleRecipients(){

    $data = $this->request->data;

    //debug($data);

    if(isset($data['offices']) && isset($data['partners'])){

      $ret = $this->Submissions->getPossibleRecipients($data);

      $this->_result = $ret;

    }else{

      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: Dati mancanti, si prega di contattare l'amministratore del sistema [ER24]");

    }


  }

  public function deleteAttachmentById(){

    $data = $this->request->data;

    //debug($data);

    if(isset($data['id'])){

      $ret = $this->Submissions->deleteAttachmentById($data['id']);
      $this->_result = $ret;

    }else{

      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore: Id mancante, si prega di contattare l'amministratore del sistema [ER25]");

    }

  }

  public function uploadFile()
    {
        $this->_result['response'] = "KO";
        $this->_result['data'] = -1;
        if(empty($this->request->data['uploadedfile'])){
          $this->_result['msg'] = "Non hai caricato nessun file.";
          return;
        }

        $file = $this->request->data['uploadedfile'];
        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file['tmp_name']);
        $type = substr($type, 0, strpos($type, '/'));
        $arr_type = ['image','audio','video'];

        if(!in_array($type, $arr_type)){
          $this->_result['msg'] = "Formato del file non valido.";
          return;
	  	}

        $folderPath = DS.date('Y').DS.date('m');
        $uploadPath = ROOT.DS.'webroot'.DS.'files'.$folderPath;
        $fileName = uniqid().$file['name'];

        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)){
          $this->_result['msg'] = "Errore durante salvataggio del file.";
          return;
        }

        if(!move_uploaded_file($file['tmp_name'],$uploadPath.DS.$fileName) ){
          $this->_result['msg'] = "Errore durante salvataggio del file.";
          return;
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = Router::url('/files'.$folderPath.DS.$fileName, true);
        $this->_result['msg'] = "Salvataggio avvenuto.";

    }

}
