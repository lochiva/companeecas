<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;


class ControlloComponent extends Component
{
    
    public function getUserTasks(){

        $users_task = TableRegistry::get('Consulenza.Users');

        $res = $users_task->find('all')
          ->contain(['Tasks'=>function ($q) {
               return $q->where(array(
                    'Tasks.start >' => date('Y-m-d 00:00:00'),
                    'Tasks.start <' => date('Y-m-d 23:59:59')
                    )
                )->order('Tasks.start ASC');
            }
            ])
          ->order('cognome ASC')
          ->toArray();        

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }

    public function getMyNewNotice(){ // restituisce solo notifiche non lette

    	// leggo id da sessione e recupero sue notifiche nuove
    	$uid =  $this->request->session()->read('Auth.User.id');

        $messages = TableRegistry::get('Consulenza.Messages');

        $res = $messages->find('all')->where(array('userDest_id' => $uid,'dateReaded IS NULL'))->contain(['UserSources'])->order('dateWrited ASC')->toArray();

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }    

    public function setNoticeRead($nid){ // setta quella notifica come letta solo per l'utente loggato

    	// leggo id da sessione
    	$uid =  $this->request->session()->read('Auth.User.id');

        $messages = TableRegistry::get('Consulenza.Messages');

		$msg = $messages->get($nid); 

		$msg->dateReaded = date('Y-m-d H:i:s');
		$messages->save($msg);        


        $res = $messages->find('all')->where(array('userDest_id' => $uid,'dateReaded IS NULL'))->order('dateWrited ASC')->toArray();

    }   
    
}