<?php
namespace App\Controller;
################################################################################
#
# Companee :   Ws (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################

use App\Controller\AppController;
use App\Controller\UsersController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class WsController extends AppController
{

    public function initialize(){
        parent::initialize();
        
		$this->loadComponent('Trading');
	}

    public function isAuthorized($user)
    {
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ragioneria' || 
            $user['role'] == 'ente_ospiti' ||
            $user['role'] == 'ente_contabile'
        ){
            return true;
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return true;
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

        $user = $this->Auth->user();

        if(isset($user['role']) && ($user['role'] == 'user' || $user['role'] == 'companee_admin')){
            $this->Auth->allow();
        }else{
            $this->Auth->allow(['getprovince', 'getLuoghi', 'getCap']);
        }

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }


    public function autocompleteTags()
    {
        $nome = $this->request->query['q'];
        $res = array();

        if(strlen($nome) < 2){
          $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Devi inserire almeno tre lettere.");
        }else{
          $tagsTable = TableRegistry::get('Tags');
          $res = $tagsTable->getAutocomplete($nome);
          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
        }
    }

    public function userNewNotice()
    {
        $user = $this->request->session()->read('Auth.User');
        if(empty($user['id'])){
            $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Errore sessione.");
            return;
        }
        $notice = TableRegistry::get('Notifications');
        $notice = $notice->getUserNewNotice($user['id']);
        $this->_result = array('response' => 'OK', 'data' => $notice, 'msg' => "Elenco risultati.");
    }

    public function readNotice($id)
    {
        $user = $this->request->session()->read('Auth.User');
        if(empty($user['id'])){
            $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Errore sessione.");
            return;
        }
        $noticeTable = TableRegistry::get('Notifications');
        if(!$noticeTable->readNotice($id)){
            $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Errore lettura.");
            return;
        }
        $this->_result = array('response' => 'OK', 'data' => date('H:m - d/m/y'), 'msg' => "Notifica letta.");
    }

    public function sendNotice()
    {
        $user = $this->request->session()->read('Auth.User');
        if(empty($user['id'])){
            $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Errore sessione.");
            return;
        }
        $this->request->data['id_creator'] = $user['id'];
        $noticeTable = TableRegistry::get('Notifications');
        if(!$noticeTable->sendNotice($this->request->data)){
            $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Errore salvataggio.");
            return;
        }
        $this->_result = array('response' => 'OK', 'data' => date('H:m - d/m/y'), 'msg' => "Notifica spedita.");
    }

    public function getProvince($all = false)
    {
        $toRet = TableRegistry::get('Province')->getList($all);
        $this->_result = array('response' => 'OK', 'data' => $toRet, 'msg' => "Elenco risultati.");
    }

    public function getCap($all = false)
    {

      $toRet = array();

        if(!empty($this->request->data['q']) ){

            $toRet = TableRegistry::get('Cap')->getList($this->request->data['q'],$all);

        }

        $this->_result = array('response' => 'OK', 'data' => $toRet, 'msg' => "Elenco risultati.");
    }

    public function getLuoghi($all = false)
    {
        $provincia = $this->request->data['q'];
        $res = array();

        if(empty($provincia)){
          $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Nessun dato trovato.");
        }else{
          $luoghiTable = TableRegistry::get('Luoghi');
          $res = $luoghiTable->getList($provincia,$all);
          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
        }
    }

    public function autocompleteProvincia()
    {
        $search = isset($this->request->query['q']) ? $this->request->query['q'] : ''; 

        $res = array();

        $luoghiTable = TableRegistry::get('Luoghi');
        $res = $luoghiTable->getAutocompleteProvincia($search);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
    }

    public function autocompleteComune($prv = '')
    {   
        $search = isset($this->request->query['q']) ? $this->request->query['q'] : ''; 
        
        $res = array();

        $luoghiTable = TableRegistry::get('Luoghi');
        $res = $luoghiTable->getAutocompleteComune($search, $prv);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
    }

    public function autocompleteLuoghi($prv = '')
    {
        $nome = $this->request->query['q'];
        $res = array();

        if(strlen($nome) < 3){
          $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Devi inserire almeno tre lettere.");
        }else{
          $luoghiTable = TableRegistry::get('Luoghi');
          $res = $luoghiTable->getAutocomplete($nome,$prv);
          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
        }
    }

	public function autocompleteUser()
	{
		$nome = $this->request->query['q'];
		$res = array();

		if(strlen($nome) < 2){
		  $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Devi inserire almeno tre lettere.");
		}else{
		  $usersTable = TableRegistry::get('Users');
		  $res = $usersTable->getUserAutocomplete($nome);
		  $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
		}
    }

    public function processErrevicodesFile($filename, $verified = false)
    {
        $this->Trading->processErrevicodesFile($filename, $verified);
    }

    public function autoCompletePoliceStations($comune = '')
    {   
        $search = isset($this->request->query['q']) ? $this->request->query['q'] : ''; 
        
        $res = array();

        $policeStations = TableRegistry::get('Aziende.PoliceStations');
        $res = $policeStations->getAutocompletePoliceStations($search, $comune);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
    }

    public function getCurrentTime()
    {
        $now = date('Y-m-d H:i:s');
        if ($now) {
            $this->_result['response'] = "OK";
            $this->_result['data'] = $now;
            $this->_result['msg'] = "Data e ora attuali recuperati con successo.";
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nel recupero di data e ora attuali.";
        }
    }
    
}
