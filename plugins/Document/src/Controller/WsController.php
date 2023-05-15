<?php
/**
* Document is a plugin for manage attachment
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
namespace Document\Controller;

use Document\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Document.Document');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['getChild','addClient','addProject','getProjectByClient','mouveParentDocument']);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");


    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }

    public function getChild($parent = 0){

        $doc = $this->Document->getDocumentsByParent($parent);

        //$documents = $doc->toArray();
        $documents = [];

        foreach ($doc as $key => $d) {
            $documents[$key]['id'] = $d->id;
            $documents[$key]['id_document'] = $d->id_document;
            $documents[$key]['parent'] = $d->parent;
            $documents[$key]['title'] = $d->title;
            if(is_object($d->client)){
                $documents[$key]['client'] = $d->client->name;
            }else{
                $documents[$key]['client'] = "-";
            }
            if(is_object($d->project)){
                $documents[$key]['project'] = $d->project->name;
            }else{
                $documents[$key]['project'] = "-";
            }
            $documents[$key]['text1'] = $d->text1;
            $documents[$key]['created'] = date("d/m/Y H:i:s", time($d->created));

            //Verifico per ogni documento quanti figli ha
            $dc = $this->Document->getDocumentsByParent($d->id_document);
            $documents[$key]['child'] = $dc->count();

            //Verifico per ogni documento quante revisioni ci sono
            $dr = $this->Document->getDocumentsRevision($d->id_document);
            $documents[$key]['revision'] = $dr->count();

        }

        //echo "<pre>"; print_r($documents); echo "</pre>";

        $this->_result['response'] = "OK";
        $this->_result['data'] = array('tot' => count($documents), 'doc' => $documents);
        $this->_result['msg'] = "Operazione eseguita con successo.";

    }

    public function addClient($name = ""){

        //Questo metodo dovrà essere poi sostiuito da un servizio di creazion ecliente esposto dal plugin "Contacts" ma che ora non esiste ancora.

        if($name != ""){
            $contacts = TableRegistry::get('Document.Contacts');

            $contact = $contacts->newEntity();

            $c['name'] = $name;
            $c['client'] = 1;

            $contact = $contacts->patchEntity($contact, $c);

            if($contacts->save($contact)){

                //$clients = $contacts->find('all')->where(['client' => 1])->Order(['name' => 'ASC']);
                $clients = $this->Document->getAllClients();
                $c_out = [];

                foreach ($clients as $key => $value) {
                    $c_out[$key]['id'] = $value->id;
                    $c_out[$key]['name'] = $value->name;
                }

                $this->_result['response'] = "OK";
                $this->_result['data'] = ['clients' => $c_out];
                $this->_result['msg'] = "Salvataggio eseguito con successo.";
            }else{
                $this->_result['response'] = "KO";
                $this->_result['data'] = -1;
                $this->_result['msg'] = "Impossibile eseguire il salvataggio, verificare che il cliente non esista già prima di riprovare.";
            }
        }else{
            $this->_result['response'] = "KO";
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Nome del Cliente non valido, si prega di riprovare.";
        }
    }

    public function addProject($name = "",$idClient = ""){

        //Questo metodo dovrà essere poi sostiuito da un servizio di creazion ecliente esposto dal plugin "Contacts" ma che ora non esiste ancora.

        if($name != "" && $idClient != ""){
            $projects = TableRegistry::get('Document.Projects');

            $project = $projects->newEntity();

            $c['name'] = $name;
            $c['id_client'] = $idClient;

            $project = $projects->patchEntity($project, $c);

            if($projects->save($project)){

                //$clients = $contacts->find('all')->where(['client' => 1])->Order(['name' => 'ASC']);
                $projects = $this->Document->getAllProjectByClients($idClient);
                $p_out = [];

                foreach ($projects as $key => $value) {
                    $p_out[$key]['id'] = $value->id;
                    $p_out[$key]['name'] = $value->name;
                }

                $this->_result['response'] = "OK";
                $this->_result['data'] = ['projects' => $p_out];
                $this->_result['msg'] = "Salvataggio eseguito con successo.";
            }else{
                $this->_result['response'] = "KO";
                $this->_result['data'] = -1;
                $this->_result['msg'] = "Impossibile eseguire il salvataggio, verificare che il progetto non esista già prima di riprovare.";
            }
        }else{
            $this->_result['response'] = "KO";
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Dati non validi, si prega di riprovare.";
        }
    }

    public function getProjectByClient($idClient = ""){

        if($idClient != ""){

            $projects = $this->Document->getAllProjectByClients($idClient);
            $p_out = [];

            foreach ($projects as $key => $value) {
                $p_out[$key]['id'] = $value->id;
                $p_out[$key]['name'] = $value->name;
            }

            $this->_result['response'] = "OK";
            $this->_result['data'] = ['projects' => $p_out];
            $this->_result['msg'] = "Dati letti correttamente.";

        }else{
            $this->_result['response'] = "KO";
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Dati non validi, si prega di riprovare.";
        }

    }

    public function mouveParentDocument($id = "", $parent = ""){

        if($id != "" && $parent != ""){

            $this->Document->mouveParentDocument($id, $parent);

            $this->_result['response'] = "OK";
            $this->_result['data'] = 1;
            $this->_result['msg'] = "Documento spostato.";

        }else{
            $this->_result['response'] = "KO";
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Dati non validi, si prega di riprovare.";
        }

    }

    public function getAllDocuments()
    {
        $documents = $this->Document->getParentsTree();
        array_walk_recursive($documents, function(&$item) {
            $item = htmlspecialchars($item);
          });
        $this->_result['response'] = "OK";
        $this->_result['data'] = $documents;
        $this->_result['msg'] = "ok.";
    }

    public function getDocument($id = 0)
    {
        $document = $this->Document->_get($id);
        $document->title = htmlspecialchars($document->title);
        if(!empty($document->azienda)){
          array_walk_recursive($document->azienda, function(&$item) {
              $item = htmlspecialchars($item);
            });
        }
        if(!empty($document->ordine)){
          array_walk_recursive($document->ordine, function(&$item) {
              $item = htmlspecialchars($item);
            });
        }

        $document['revision'] = $this->Document->getDocumentsRevision($document->id_document)->count();
        $this->_result['response'] = "OK";
        $this->_result['data'] = $document;
        $this->_result['msg'] = "ok.";
    }

    public function moveDocument($type="")
    {

        $data = $this->request->data;
        $unLock = false;
        $try = 0;
        while($unLock === false){
           $try++;
           $unLock = $this->lockWrite();
           if(!$unLock){
             usleep(20000);
           }
           if($try >= 100){
             $this->_result['response'] = "KO";
             $this->_result['data'] = -1;
             $this->_result['msg'] = "Errore durante la scrittura dati.";
             return;
           }
        }

        if(!isset($data['position']) || !isset($data['old_position']) || empty($data['id'])
          || !isset($data['parent']) || !isset($data['old_parent']) || empty($type)){

            $this->_result['response'] = "KO";
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Dati non validi, si prega di riprovare.";
            $this->unlockWrite();
            return;

        }
        switch ($type) {
          case 'parent':
              if($this->Document->moveDocumnetParent($data)){
                $this->_result['response'] = "OK";
                $this->_result['data'] = 1;
                $this->_result['msg'] = "Documento spostato.";
              }
              break;
          case 'position':
              if($this->Document->moveDocumentPosition($data)){
                $this->_result['response'] = "OK";
                $this->_result['data'] = 1;
                $this->_result['msg'] = "Documento spostato.";
              }
              break;
          default:
              $this->_result['response'] = "KO";
              $this->_result['data'] = -1;
              $this->_result['msg'] = "Dati non validi, si prega di riprovare.";
              break;
        }
        $this->unlockWrite();

    }

    public function setPositionForAll()
    {
        $documents = $this->Document->getParentsTree();
        $this->Document->setPositionForAll($documents);

        $this->_result['response'] = "OK";
        $this->_result['data'] = 1;
        $this->_result['msg'] = "WebService eseguito.";
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

        /*if(!in_array($type, $arr_type)){
          $this->_result['msg'] = "Formato del file non valido.";
          return;
        }*/
        $uploadPath = ROOT.DS.'src'.DS.'files'.DS.date('Y').DS.date('m');
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
        $this->_result['data'] = Router::url('/document/home/getUploadedFile/'.date('Y').'/'.date('m').'/'.$fileName);
        $this->_result['msg'] = "Salvataggio avvenuto.";

    }

    public function orderDocuments($id=0, $order='ASC')
    {

        $this->_result['data'] = '';
        $this->_result['msg'] = "";

        if($this->Document->orderDocuments($id, $order)){
            $this->_result['response'] = "OK";
        }else{
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Errore durante l\'elaborazione.';
        }

    }


}
