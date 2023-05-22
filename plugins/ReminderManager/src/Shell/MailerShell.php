<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Mailer  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/

namespace ReminderManager\Shell;

use Cake\Console\Shell;
use Cake\Network\Email\Email;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class MailerShell extends Shell
{
    private $time_limit = 0;
    private $time_start = 0;

    public function main()
    {
        $this->out('Hello world.');
    }


    public function testSendMail($mail)
    {
        $this->out('Test Send mail to ' . $mail);

        //sleep(60);

        $email = new Email('default');
        $email->from(['me@example.com' => 'My Site'])
            ->to($mail)
            ->subject('About')
            ->send('My message');

    }

    public function sendEmailTest($idMailer, $testEmail){

      Log::write('debug', 'Invio email di test per il mailing id: ' . $idMailer . ' alla mail: ' . $testEmail ,'shell');

      // Recupero l'invio da fare
      $mail = $this->loadNextMail($idMailer);

      //debug($mail);

      // Manipolo alcuni dati per l'invio di test
      $mail['object'] = '[TEST EMAIL] ' . $mail['object'];
      $mail['text'] = '<p> #################### TEST EMAIL #################### </p>' . $mail['text'];
      $mail['SubmissionsEmails'][0]['email'] = $testEmail;

      // A questo punto posso inviare la mail
      $send = $this->sendMail($mail);

      if($send){
        Log::write('debug', 'Invio avvenuto con successo' ,'shell');
        return true;
      }else{
        Log::write('error', 'Errore Invio' ,'shell');
        return false;
      }

    }

    public function exec_background(){

      $cmd = ROOT.DS.'bin'.DS.'cake mailer start';

      if (substr(php_uname(), 0, 7) == 'Windows') {
          pclose(popen('start /B '.$cmd, 'r'));
      } else {
          exec($cmd.' > /dev/null &');
      }

      return true;

    }

    public function start(){

      //debug('start');
      Log::write('debug', 'Automazione avviata','shell');

      // Salvo il tempo limite per lo script e il time di partenza per verificare poi se posso ncora lavorare o se devo rilanciarmi e uccidermi.
      set_time_limit(130); //130
      $this->time_limit = ini_get('max_execution_time');
      $this->time_start = microtime(true);

      //debug('Limit: ' . $this->time_limit);
      //debug('Start: ' . $this->time_start);

      $cont = 1;
      $goodJob = 0;

      while($this->checkInTime()){
        sleep(1);
        //debug($cont);
        Log::write('debug', 'Tempo sufficiente, eseguo l\'iterazione ' . $cont ,'shell');
        //sleep(60); // Per test di check if running
        // Recupero l'invio da fare
        $mail = $this->loadNextMail();

        if($mail){
          //debug($mail);
          Log::write('debug', 'Lavoro per il mailing id:' . $mail['id'] ,'shell');
          // Aggiorno i dati del mailing se serve
          if(!empty($mail['SubmissionsEmails'])){
            Log::write('debug', 'Il mailing ha ancora degli invii da effettuare' ,'shell');
            $status = ['status' => 2, 'status_text' => 'In Corso'];

            $mailing = $this->updateMailingStatus($mail,$status);

            if($mailing){

              // A questo punto inviare la mail
              $send = $this->sendMail($mail);

              // Se l'invio è andato a buon fine posso segnare la mail come inviata
              if($send){
                Log::write('debug', 'Invio avvenuto con successo' ,'shell');

                $this->updateMailStatus($mail['SubmissionsEmails'][0]['id']);

              }else{
                Log::write('error', 'Errore nell\'inviare la mail' ,'shell');
                $status = ['status' => 5, 'status_text' => 'Errore nell\'inviare la mail'];

                $mailing = $this->updateMailingStatus($mail,$status);
              }

            }else{
              Log::write('error', 'Errore nell\'aggiornare i dati di intestazione del mailing' ,'shell');
              $status = ['status' => 5, 'status_text' => 'Errore nell\'aggiornare i dati di intestazione'];

              $mailing = $this->updateMailingStatus($mail,$status);
            }

          }else{
            Log::write('debug', 'Il mailing non ha più invii da effettuare, lo segno come terminato' ,'shell');
            $status = ['status' => 3, 'status_text' => 'Inviato'];

            $mailing = $this->updateMailingStatus($mail,$status);
          }

        }else{
          // Non ci sono più mail da inviare...
          Log::write('debug', 'Non ci sono più mail da inviare...' ,'shell');
          $goodJob = 1;
          break;
        }

        $cont++;

      }


      if($goodJob == 1){
        // Posso uscire
        Log::write('debug', 'Lavoro terminato...esco' ,'shell');
        die();
      }else{
        // Sono uscito dal loop a causa del poco tempo, quindi mi devo rilanciare e killarmi
        Log::write('debug', 'Sono uscito dal loop a causa del poco tempo, quindi mi devo rilanciare e killarmi' ,'shell');
        $this->exec_background(); // Per ora lo commento altrimenti vado in loop
        die();

      }




    }

    private function checkInTime(){

      $now = microtime(true);

      $dif = ($now - $this->time_start) + 40;

      if($this->time_limit > $dif){
        return true;
      }else{
        return false;
      }

    }

    private function loadNextMail($idMailer = null){

      // Cerco la prossima mail da inviare

      $submissions = TableRegistry::get('ReminderManager.Submissions');

      $email = $submissions->getNextMail($idMailer);

      if(!empty($email)){
        return $email;
      }else{
        return false;
      }

    }

    private function updateMailingStatus($mail, $leveldata = ['status' => 2, 'status_text' => 'In Corso']){

      //debug($mail); die();
      if(!empty($leveldata['status']) && !empty($leveldata['status_text'])){

        $resp = false;
        $submissions = TableRegistry::get('ReminderManager.Submissions');

        $sub = $submissions->get($mail['id']);

        if($sub->status != $leveldata['status']){

          $sub->status = $leveldata['status'];
          $sub->status_text = $leveldata['status_text'];
          if($leveldata['status'] == 2){
            $sub->start = date('Y-m-d H:i:s');
          }
          if($leveldata['status'] == 3){
            $sub->end = date('Y-m-d H:i:s');
          }
          if($submissions->save($sub)){

            Log::write('debug', 'Aggiornati i dati di intestazione del mailing' ,'shell');
            $resp = true;
          }else{
            $resp = false;
          }

        }else{

          Log::write('debug', 'Non serve aggiornare i dati di intestazione del mailing' ,'shell');
          $resp = true;

        }

      }else{

        Log::write('debug', 'Dati dello status mancanti!!!!' ,'shell');
        $resp = false;

      }

      //$resp = false; // Per test
      return $resp;

    }

    private function sendMail($mail){

      Log::write('debug', 'Invio la mail a ' . $mail['SubmissionsEmails'][0]['email'] ,'shell');

      //Log::write('debug', $mail ,'shell');
      //die();
      $ret = true;


      $email = new Email('default');

        $email->template('ReminderManager.' . $mail['template'])
        ->emailFormat('both')
        ->sender($mail['sender_email'])
        ->from([$mail['sender_email'] => "Itoa Companee"])
        ->to([$mail['SubmissionsEmails'][0]['email'] =>  $mail['SubmissionsEmails'][0]['email']])
        ->subject($mail['object'])
        ->viewVars(['email' => $mail]);

      // ###################################################################################################
      // Setto gli header della Mail
      $email->setHeaders(['List-Unsubscribe' => '<mailto:' . $mail['sender_email'] . '>']);

      // ###################################################################################################
      // Forzo il sender della mail...
      $email->transport('default');
			$transport = $email->transport();
			$transport->config('additionalParameters', '-f' . $mail['sender_email']);

      // ###################################################################################################
      // Setto gli allegati legati alla mail
      $attachments = [];

      if(!empty($mail['SubmissionsEmails'][0]['SubmissionsEmailsAttachements'])){

        foreach ($mail['SubmissionsEmails'][0]['SubmissionsEmailsAttachements'] as $key => $attch) {

          $attachments[] = $attch['path'];

        }

      }

      if(!empty($mail['SubmissionsAttachements'])){

        foreach ($mail['SubmissionsAttachements'] as $key => $attch) {
          $attachments[] = $attch['path'];
        }

      }

      // ###################################################################################################
      // Allego le immagini di interfaccia

      /*if($mail['template'] == 'default'){
        
        $attachments[] = [
              //'file' => WWW_ROOT . 'img' . DS . 'logo.png',
          'file' => WWW_ROOT . 'img' . DS . 'logo_lochiva-companee.png',
          'mimetype' => 'image/png',
          'contentId' => 'company_logo'
        ];

      }elseif($mail['template'] == 'consulenza'){

        $attachments[] = [
          'file' => WWW_ROOT . 'img' . DS . 'left_consulenza_mail.png',
          'mimetype' => 'image/png',
          'contentId' => 'left_consulenza_mail'
        ];
        $attachments[] = [
          'file' => WWW_ROOT . 'img' . DS . 'right_consulenza_mail.png',
          'mimetype' => 'image/png',
          'contentId' => 'right_consulenza_mail'
        ];
        $attachments[] = [
          'file' => WWW_ROOT . 'img' . DS . 'header_consulenza_mail.png',
          'mimetype' => 'image/png',
          'contentId' => 'header_consulenza_mail'
        ];

      }*/
      

    
      // ###################################################################################################

      if(!empty($attachments)){
        $email->attachments($attachments);
      }

      $res = $email->send();

      //debug($res);


      //$ret = false; // Per test

      return $ret;

    }

    public function setMailStatus($id, $status){
      if($status != 1){
        if($this->updateMailStatus($id, $status)){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

    private function updateMailStatus($mail, $status = 1){

      $submissionsEmails = TableRegistry::get('ReminderManager.SubmissionsEmails');

      $subEmail = $submissionsEmails->get($mail);

      $subEmail->sended = $status;

      if($submissionsEmails->save($subEmail)){
        Log::write('debug', 'Aggiornato lo stato dell\'invio a ' . $subEmail->email ,'shell');
        return true;
      }else{
        return false;
      }

    }

}
