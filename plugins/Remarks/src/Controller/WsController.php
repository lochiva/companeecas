<?php
/**
* Remarks is a plugin for manage attachment
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

namespace Remarks\Controller;

use Remarks\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Remarks.Remarks');

    }

    public function isAuthorized($user)
    {
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ragioneria' || 
            $user['role'] == 'questura' ||
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
        $this->_result = ['response' => 'KO', 'data' => null, 'msg' => null];

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this->_result));
    }

    public function saveRemark()
    {
        $data = $this->request->data;
        $data['user_id'] = $this->request->session()->read('Auth.User.id');

        $labelNotification = $this->request->data['label_notification'];

        unset($this->request->data['label_notification']);

        $res = $this->Remarks->saveRemark($data);

        if($res){ 
            if(empty($data['id']) && $res->visibility == 0){
                //invio notifiche
                $remarkedUsers = TableRegistry::get('Remarks.Remarks')->getRemarkedUsers($data['reference'], $data['reference_id']);
                foreach($remarkedUsers as $user){
                    if($data['user_id'] != $user['user_id']){
                        $noticeData = [
                            'id_creator' => $data['user_id'],
                            'id_dest' => $user['user_id'],
                            'message' => $labelNotification.': aggiunta nuova nota.'
                        ];

                        $noticeTable = TableRegistry::get('Notifications');
                        $noticeTable->sendNotice($noticeData);
                    }
                }
            }

            $this->_result = ['response' => 'OK', 'data' => '', 'msg' => ''];
        }else{
            $this->_result = ['response' => 'KO', 'data' => '', 'msg' => 'Errore nel salvataggio della nota.'];
        }
    }

    public function deleteRemark()
    {
        $remarkId = $this->request->data['remark_id'];

        $res = $this->Remarks->deleteRemark($remarkId);

        if($res){
            $this->_result = ['response' => 'OK', 'data' => '', 'msg' => 'Remark cancellato correttamente'];
        }else{
            $this->_result = ['response' => 'KO', 'data' => '', 'msg' => 'Errore nella cancellazione del remark.'];
        }
    }

    public function getRemarksByRef($reference, $showDeleted)
    {
        $userId = $this->request->session()->read('Auth.User.id');

        $res = $this->Remarks->getRemarksByRef($reference, $userId, $showDeleted);

        if($res){
            $this->_result = ['response' => 'OK', 'data' => $res, 'msg' => ''];
        }else{
            $this->_result = ['response' => 'KO', 'data' => '', 'msg' => 'Nessuna nota trovata per il reference "'.$reference.'" .'];
        }
    }

    public function getRemarksByRefId($reference, $referenceId, $showDeleted)
    {
        $userId = $this->request->session()->read('Auth.User.id');

        $res = $this->Remarks->getRemarksByRefId($reference, $referenceId, $userId, $showDeleted);

        if($res){
            $this->_result = ['response' => 'OK', 'data' => $res, 'msg' => ''];
        }else{
            $this->_result = ['response' => 'KO', 'data' => '', 'msg' => 'Nessuna nota trovata per il reference "'.$reference.'" e l\'id "'.$referenceId.'".'];
        }
    }

    public function getRemark($remarkId)
    {
        $res = $this->Remarks->getRemark($remarkId);

        if($res){
            $this->_result = ['response' => 'OK', 'data' => $res, 'msg' => ''];
        }else{
            $this->_result = ['response' => 'KO', 'data' => '', 'msg' => 'Nessuna nota trovata.'];
        }
    }

    public function remarksNumberForBadge($reference, $referenceId)
    {
        $userId = $this->request->session()->read('Auth.User.id');

        $res = $this->Remarks->getRemarksNumber($reference, $referenceId, $userId);

        $this->_result = ['response' => 'OK', 'data' => $res, 'msg' => ''];
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

        $folderPath = date('Y').DS.date('m');
        $uploadPath = WWW_ROOT.DS.Configure::read('dbconfig.remarks.REMARKS_UPLOAD_PATH_TINYMCE').$folderPath;
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
        $this->_result['data'] = Router::url(DS.Configure::read('dbconfig.remarks.REMARKS_UPLOAD_PATH_TINYMCE').$folderPath.DS.$fileName, true);
        $this->_result['msg'] = "Salvataggio avvenuto.";

    }

    public function uploadRemarkAttachment()
    {
        $this->_result['response'] = "KO";
        $this->_result['data'] = '';

        $attachment = $this->request->data['remark_attachment'];

        $folderPath = date('Y').DS.date('m');
        $uploadPath = ROOT.DS.Configure::read('dbconfig.remarks.REMARKS_UPLOAD_PATH').$folderPath;
        $fileName = uniqid().$attachment['name'];

        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)){
          $this->_result['msg'] = "Errore durante salvataggio del file.";
          return;
        }

        if(!move_uploaded_file($attachment['tmp_name'],$uploadPath.DS.$fileName) ){
          $this->_result['msg'] = "Errore durante salvataggio del file.";
          return;
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = $folderPath.DS.$fileName;
        $this->_result['msg'] = "Salvataggio avvenuto.";
    }

    public function downloadAttachment($remarkId = 0)
    {
		$remarks = TableRegistry::get('Remarks.Remarks');
        $remark = $remarks->get($remarkId);
        
        $filePath = $remark['attachment'];

		if($filePath != ''){
			$uploadPath = ROOT.DS.Configure::read('dbconfig.remarks.REMARKS_UPLOAD_PATH').$filePath;

			$fileArray = array_reverse(explode('/', $uploadPath));
			$fileName = $fileArray[0];

            if(file_exists($uploadPath)){
                //download file
                $this->response->file($uploadPath , array(
                    'download'=> true,
                    'name'=> $fileName
                ));
                setcookie('downloadStarted', '1', false, '/');
                return $this->response;
            }else{
                setcookie('downloadStarted', '1', false, '/');
                die('Il file richiesto non esiste.');
            }
		}else{
            setcookie('downloadStarted', '1', false, '/');
            die('La nota non ha un allegato.');
		}
	}


}
