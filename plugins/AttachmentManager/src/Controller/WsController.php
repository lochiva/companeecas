<?php

namespace AttachmentManager\Controller;

use AttachmentManager\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('AttachmentManager.Attachment');

    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = ['response' => 'KO', 'data' => null, 'msg' => null];

    }

    public function isAuthorized($user = null)
    {
		if($user['role'] == 'admin' || $user['role'] == 'ente'){
			return true;
		}

		if($user['role'] == 'user'){
			$userActions = [];
			if (in_array($this->request->getParam('action'), $userActions)) {
				return true;
			}
		}

        // Default deny
        return false;
    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this->_result));
    }

    public function saveAttachment()
    {
        $data = $this->request->data;

        if(count($data['attachments']) == 1 && empty($data['attachments'][0]['tmp_name'])){
            $this->_result['msg'] = 'Nessun file caricato.';        
        }else{
            $attachmentsTable = TableRegistry::get('AttachmentManager.Attachments');

            $basePath = ROOT.DS.Configure::read('dbconfig.attachments.ATTACHMENTS_UPLOAD_PATH');

            $error = false;

            foreach($data['attachments'] as $attachment){ 
                $fileName = uniqid().'_'.$attachment['name'];
                $path = $basePath.date('Y').DS.date('m');

                if (!is_dir($path) && !mkdir($path, 0755, true)){
                    $error = true;
                }

                if(!move_uploaded_file($attachment['tmp_name'], $path.DS.$fileName) ){
                    $error = true;
                }

                if(!$error){
                    $entity = $attachmentsTable->newEntity();

                    $entity->context = $data['context'];
                    $entity->id_item = $data['id_item'];
                    $entity->file = $attachment['name'];
                    $entity->file_path = $path.DS.$fileName;
                    $entity->file_type = explode('/', $attachment['type'])[1];
                    $entity->file_size = round(($attachment['size'] / 1000), 1);
                    $entity->upload_date = date('Y-m-d');

                    if(!$attachmentsTable->save($entity)){
                        $error = true;
                    }
                }
            }

            if($error){
                $this->_result['msg'] = 'Errore nel caricamento di un file.';
            }else{
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'File caricati con successo.';
            }
        }
    }

    public function getAttachments($context, $idItem){ 
        $attachmentsTable = TableRegistry::get('AttachmentManager.Attachments');

        $attachments = $attachmentsTable->find()
                        ->select(['id', 'file', 'file_type', 'file_size', 'upload_date'])
                        ->where(['context' => $context, 'id_item' => $idItem, 'deleted' => '0'])
                        ->order('created DESC')
                        ->toArray(); 

        foreach($attachments as $a){
            $a->upload_date = !empty($a->upload_date) ? $a->upload_date->format('d/m/Y') : '';
        }
                 
        $this->_result['response'] = 'OK';
        $this->_result['data'] = $attachments;
        $this->_result['msg'] = 'Allegati recuperati con successo.';
    }

    public function downloadAttachment($id)
    {
        $attachmentsTable = TableRegistry::get('AttachmentManager.Attachments');

        $attachment = $attachmentsTable->get($id);
        
        $uploadPath = $attachment['file_path'];
        $name = $attachment['file'];

        $fileArray = array_reverse(explode('/', $uploadPath));
        $fileName = $fileArray[0];

        if(file_exists($uploadPath)){
            $this->response->file($uploadPath , array(
                'download'=> true,
                'name'=> $name
            ));
            setcookie('downloadStarted', '1', false, '/');
            return $this->response;
        }else{
            setcookie('downloadStarted', '1', false, '/');
            $this->_result['msg'] = 'Il file richiesto non esiste.';
        }
    }

    public function deleteAttachment()
    {
        $id = $this->request->data['id'];

        $attachmentsTable = TableRegistry::get('AttachmentManager.Attachments');

        $attachment = $attachmentsTable->get($id);

        $attachment->deleted = '1';

        if($attachmentsTable->save($attachment)){
            $this->_result['response'] = 'OK';
            $this->_result['msg'] = 'Allegato eliminato con successo.';
        }else{
            $this->_result['msg'] = 'Errore nell\'eliminazione dell\'allegato.';
        }
    }

    public function attachmentsNumberForBadge($context, $idItem)
    {
        $res = $this->Attachment->getAttachmentsNumber($context, $idItem);

        $this->_result = ['response' => 'OK', 'data' => $res, 'msg' => ''];
    }


}
