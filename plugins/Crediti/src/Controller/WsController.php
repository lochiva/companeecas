<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Ws  (https://www.companee.it)
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
namespace Crediti\Controller;

use Crediti\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;
use Cake\Log\Log;
use Cake\Core\Configure;
use \Exception;

/**
 * WsController Controller
 *
 * @author Rafael Esposito
 */
class WsController extends AppController
{
  public function initialize()
  {
      parent::initialize();
      $this->loadComponent('Crediti.Credit');
      $this->email = new Email();

      Log::config('crediti_log', [
            'className' => 'File',
            'path' => LOGS,
            'levels' => [],
            'scopes' => ['email'],
            'file' => 'plugin_crediti.log',
        ]);
  }

  public function beforeFilter(Event $event)
  {
      parent::beforeFilter($event);
      //$this->Auth->allow(['getCalendarEvents', 'saveEvent','deleteEvent']);
      $user = $this->request->session()->read('Auth.User');

      if($user['role']!='admin'){
          $this->redirect('/');
      }
      $this->_userId = $user['id'];
      $this->layout = 'ajax';
      $this->viewPath = 'Async';
      $this->view = 'default';
      $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

  }

  public function beforeRender(Event $event)
  {
      parent::beforeFilter($event);
      $this->set('result', json_encode($this -> _result));
  }

  public function getCreditsTotals()
  {
    $out = $this->Credit->getCreditsTotals();
    //debug($out);
    $this->_result = $out;

  }

  public function getCreditsAzienda($id=0)
  {
    if($id > 0){

      $data = $this->Credit->getCreditsAzienda($id);
      $data['total'] = $this->Credit->retrieveCreditsGroupAziendaById($id);
      $data['total'] = $data['total'][0];
      $this->_result = array('response' => 'OK','data' => $data, 'msg'=>'Tutto ok');

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
    }

  }

  public function getTotalsCreditsAziendaNotifiche($id = 0)
  {
    if($id > 0){

      $data = $this->Credit->getTotalsCreditsAziendaNotifiche($id);
      $this->_result = array('response' => 'OK','data' => $data, 'msg'=>'Tutto ok');

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
    }

  }

  public function getAziendaInfoForNotifiche($id=0)
  {

    if($id > 0){

      $data = $this->Credit->getAziendaInfoForNotifiche($id);
      $this->_result = array('response' => 'OK','data' => $data, 'msg'=>'Tutto ok');

    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
    }

  }

  public function inviaNotificaAzienda($id = 0,$partnerId = 0)
  {
    // Controllo che ci sia un id valido
    if($id > 0 ){
      $data = [];
      $data['author_id'] = $this->_userId;

      // Controllo se la notifica è di tipo email
      if(!empty($this->request->data['email']) && $this->request->data['tipo'] == 'email'){
        // Recupero l'email dell'azienda
        $aziendeTable = TableRegistry::get('Crediti.Aziende');
        $azinedaEmail = $aziendeTable->retireveAzinedaEmail($id);
        // Leggo l'email configurata come Sender, in caso sia assente imposto app@domain.com
        $appEmail = Configure::read('dbconfig.EMAIL_SENDER_CREDITS');

        if($appEmail == null || empty($appEmail) )
          $appEmail = 'app@domain.com';

        // Se ho trovato l'email dall'azienda preparo l'oggetto email
        if(!empty($azinedaEmail)){
          try{
            $this->email->template('Crediti.default','Crediti.default')
                ->emailFormat('html')
                ->to($azinedaEmail)
                ->subject($this->request->data['subject'])
                ->from($appEmail)
                ->viewVars(['content' => $this->request->data['email']]);
            // Se è presente l'id del partner inserisco la sua email nel cc
            if($partnerId > 0){
              $usersTable = TableRegistry::get('Users');
              $partner = $usersTable->get($partnerId);
              if($partner != null && $partner['email'] != ''){
                $this->email->cc($partner['email']);
              }
            }
            // Mando l'email e resetto l'oggetto
            $this->email->transport('default');
            $res = $this->email->send();
            $this->email->reset();
            // Salvo nella tabella notifiche
            $data['testo'] = ['to'=>$azinedaEmail,'subject'=>$this->request->data['subject'],'messaggio'=>$this->request->data['email']];
            $data['type'] = 'email';
            $this->Credit->saveNotifica($id,$data);

            $this->_result = array('response' => 'OK','data' => 1, 'msg'=>'Email inviata con sucesso all\'email: '.$azinedaEmail);

          }catch(Exception $e){

            Log::warning( 'Errore Email: '.$e->getMessage(),['scope' => ['email']]);
            $this->_result = array('response' => 'KO','data' => 1, 'msg'=>'Errore Server!');
          }

        }else{
          $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Il cliente non ha una email solleciti nell'anagrafica.");
        }

      // In caso non sia un email salvo semplicemente nella tabella notifiche
      }else{
        $data['testo']['messaggio'] = $this->request->data['testo'];
        $data['type'] = $this->request->data['tipo'];

        $this->Credit->saveNotifica($id,$data);
        $this->_result = array('response' => 'OK','data' => 1, 'msg'=>'Azione salvata con successo.');
      }
    }else{
      $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
    }

  }

  public function getCredits()
  {
    $out = $this->Credit->getCredits();
    //debug($out);
    $this->_result = $out;
  }


}
