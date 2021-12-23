<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class NoticeComponent extends Component
{
    
    public function getMyNotice(){

    	// leggo id da sessione e recupero sue notifiche
    	$uid =  $this->request->session()->read('Auth.User.id');
    	// se è admin le vede tutte
    	$role =  $this->request->session()->read('Auth.User.role');

        $messages = TableRegistry::get('Consulenza.Messages');

        if($role=='admin'){
			$res = $messages->find('all')->contain(['UserDests','UserSources'])->order('dateWrited DESC')->toArray();
        } else {
        	$res = $messages->find('all')->where(['userDest_id' => $uid])->contain(['UserDests','UserSources'])->order('dateWrited DESC')->toArray();
        }

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }

    public function getMyNewNotice(){ // restituisce solo notifiche non lette

    	// leggo id da sessione e recupero sue notifiche nuove
    	$uid =  $this->request->session()->read('Auth.User.id');

        $messages = TableRegistry::get('Consulenza.Messages');

        $res = $messages->find('all')->where(array('userDest_id' => $uid,'dateReaded IS NULL'))->contain(['UserSources'])->order('dateWrited DESC')->toArray();

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


        $res = $messages->find('all')->where(array('userDest_id' => $uid,'dateReaded IS NULL'))->order('dateWrited DESC')->toArray();

    }   
    
}