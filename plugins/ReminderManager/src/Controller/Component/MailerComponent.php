<?php
namespace ReminderManager\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Console\ShellDispatcher;

use ReminderManager\Shell\MailerShell;

class MailerComponent extends Component
{

  public function start(){

    if(!$this->checkIfRunning()){

      /*
      // Questo metodo lo chiama in sincrono
      $shell = new ShellDispatcher();
      $output = $shell->run(['cake', 'mailer','start']);
      debug($output);
      */

      // Questo serve per chiamarlo in background
      $shell = new MailerShell();

      $shell->exec_background();

      return array('response' => 'OK', 'data' => 1, 'msg' => "Mailer Avviato");;

    }else{

      //Sto già girando quindi non serve ripartire
      //debug('Sto già girando quindi non serve ripartire');
      return array('response' => 'OK', 'data' => 1, 'msg' => "Mailer già avviato");
    }

  }

  public function sendEmailTest($idMailing,$emailTest){

    //debug($idMailing);
    //debug($emailTest);

    $shell = new MailerShell();

    $resp = $shell->sendEmailTest($idMailing,$emailTest);

    return $resp;

  }

  private function checkIfRunning(){

    // Verifico se sto già girando, in tal caso non devo farmi ripartire per non sovrapporre dei processi.

    //exec('ps aux', $output,$ret_val);
    //debug($output);
    //debug($ret_val);

    @exec("pgrep php", $pids, $ret_val2);
    //debug($pids);
    //debug($ret_val2);

    if(empty($pids) && $ret_val2 >= 0) {
      return false;
    }else{
      return true;
    }



  }

  public function setMailStatus($id, $status){

    $shell = new MailerShell();

    return $shell->setMailStatus($id, $status);

  }


}
