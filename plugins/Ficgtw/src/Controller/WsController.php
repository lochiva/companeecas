<?php
namespace Ficgtw\Controller;

use Ficgtw\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Http\Client;
use Cake\Core\Configure;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Ficgtw.Fatture');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");


    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }


    public function addClienteFornitore($tipo){

        $this->request->data["api_uid"] = Configure::read('dbconfig.ficgtw.API_UID');
        $this->request->data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $tipo == 'fornitore' ? $url = 'https://api.fattureincloud.it/v1/fornitori/nuovo' : $url = 'https://api.fattureincloud.it/v1/clienti/nuovo';

        $response = $http->post(
            $url,
            json_encode($this->request->data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

        if(isset($res->success) && $res->success==true){
          $this->_result['response'] = "OK";
          $this->_result['data'] = $res;
          $this->_result['msg'] = "Cliente inserito correttamente";
        } else {
          $this->_result['response'] = "KO";
          $this->_result['msg'] = $res->error;
        }


    }

	public function editClienteFornitore($tipo){

        $this->request->data["api_uid"] =  Configure::read('dbconfig.ficgtw.API_UID');
        $this->request->data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $tipo == 'fornitore' ? $url = 'https://api.fattureincloud.it/v1/fornitori/modifica' : $url = 'https://api.fattureincloud.it/v1/clienti/modifica';

        $response = $http->post(
            $url,
            json_encode($this->request->data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

        if(isset($res->success) && $res->success==true){
          $this->_result['response'] = "OK";
          $this->_result['data'] = $res;
          $this->_result['msg'] = "Anagrafica modificata correttamente";
        } else {
          $this->_result['response'] = "KO";
          $this->_result['msg'] = $res->error;
        }

    }

	public function deleteClienteFornitore($tipo){

        $this->request->data["api_uid"] =  Configure::read('dbconfig.ficgtw.API_UID');
        $this->request->data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $tipo == 'fornitore' ? $url = 'https://api.fattureincloud.it/v1/fornitori/elimina' : $url = 'https://api.fattureincloud.it/v1/clienti/elimina';

        $response = $http->post(
            $url,
            json_encode($this->request->data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

        if(isset($res->success) && $res->success==true){
          $this->_result['response'] = "OK";
          $this->_result['data'] = $res;
          $this->_result['msg'] = "Anagrafica eliminata correttamente";
        }else{
          $this->_result['response'] = "KO";
          $this->_result['msg'] = $res->error;
        }

    }

	public function getIdClienteFornitore(){

		$apiUid =  Configure::read('dbconfig.ficgtw.API_UID');
        $apiKey = Configure::read('dbconfig.ficgtw.API_KEY');;

		$dataCf = [
			"api_uid" => $apiUid,
		    "api_key" => $apiKey,
		    "cf" => $this->request->data['cf'],
			"pagina" => 1,
		];

		$dataPiva = [
			"api_uid" => $apiUid,
		    "api_key" => $apiKey,
		    "piva" => $this->request->data['piva'],
			"pagina" => 1,
		];

        $http = new Client();

		$clienteId = '';

		if($dataCf['cf'] != ''){

	        $url = 'https://api.fattureincloud.it/v1/clienti/lista';

	        $response = $http->post(
	            $url,
	            json_encode($dataCf),
	            ['type' => 'json']
	        );

	        $res = json_decode($response->body);

	        if(isset($res->success) && $res->success==true){
				if(!empty($res->lista_clienti[0])){
					$clienteId = $res->lista_clienti[0]->id;
				}
	        }
		}

		if($clienteId == '' && $dataPiva['piva'] != ''){
			$url = 'https://api.fattureincloud.it/v1/clienti/lista';

	        $response = $http->post(
	            $url,
	            json_encode($dataPiva),
	            ['type' => 'json']
	        );

	        $res = json_decode($response->body);

	        if(isset($res->success) && $res->success==true){
				if(!empty($res->lista_clienti[0])){
					$clienteId = $res->lista_clienti[0]->id;
				}
	        }
		}

		$fornitoreId = '';

		if($dataCf['cf'] != ''){

			$url = 'https://api.fattureincloud.it/v1/fornitori/lista';

	        $response = $http->post(
	            $url,
	            json_encode($dataCf),
	            ['type' => 'json']
	        );

	        $res = json_decode($response->body);

	        if(isset($res->success) && $res->success==true){
				if(!empty($res->lista_fornitori[0])){
					$fornitoreId = $res->lista_fornitori[0]->id;
				}
	        }
		}

		if($fornitoreId == '' && $dataPiva['piva'] != ''){
			$url = 'https://api.fattureincloud.it/v1/fornitori/lista';

	        $response = $http->post(
	            $url,
	            json_encode($dataPiva),
	            ['type' => 'json']
	        );

	        $res = json_decode($response->body);

	        if(isset($res->success) && $res->success==true){
				if(!empty($res->lista_fornitori[0])){
					$fornitoreId = $res->lista_fornitori[0]->id;
				}
	        }
		}

		$this->_result['response'] = "OK";
		$this->_result['data'] = ['clienteId' => $clienteId, 'fornitoreId' => $fornitoreId];
		$this->_result['msg'] = "";

	}

	public function getCliente(){

		$apiUid =  Configure::read('dbconfig.ficgtw.API_UID');
        $apiKey = Configure::read('dbconfig.ficgtw.API_KEY');;

		$data = [
			"api_uid" => $apiUid,
		    "api_key" => $apiKey,
		    "id" => $this->request->data['id'],
			"pagina" => 1,
		];

        $http = new Client();

        $url = 'https://api.fattureincloud.it/v1/clienti/lista';

        $response = $http->post(
            $url,
            json_encode($data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

		$cliente= [];

        if(isset($res->success) && $res->success==true){
			if(!empty($res->lista_clienti[0])){
				$cliente = $res->lista_clienti[0];
			}
        }

		$this->_result['response'] = "OK";
		$this->_result['data'] = $cliente;
		$this->_result['msg'] = '';

	}

	public function getFornitore(){

		$apiUid =  Configure::read('dbconfig.ficgtw.API_UID');
        $apiKey = Configure::read('dbconfig.ficgtw.API_KEY');;

		$data = [
			"api_uid" => $apiUid,
		    "api_key" => $apiKey,
		    "id" => $this->request->data['id'],
			"pagina" => 1,
		];

        $http = new Client();

        $url = 'https://api.fattureincloud.it/v1/fornitori/lista';

        $response = $http->post(
            $url,
            json_encode($data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

		$fornitore= [];

        if(isset($res->success) && $res->success==true){
			if(!empty($res->lista_fornitori[0])){
				$fornitore = $res->lista_fornitori[0];
			}
        }

		$this->_result['response'] = "OK";
		$this->_result['data'] = $fornitore;
		$this->_result['msg'] = '';
	}

    public function readFattura(){

          $this->request->data["api_uid"] =  Configure::read('dbconfig.ficgtw.API_UID');
          $this->request->data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

          $http = new Client();

          $url = 'https://api.fattureincloud.it:443/v1/fatture/dettagli';

          $response = $http->post(
              $url,
              json_encode($this->request->data),
              ['type' => 'json']
          );

          $res = json_decode($response->body);

          if(isset($res->success) && $res->success==true){
            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = "";
          } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = $res->error;
          }

    }

    public function addFattura(){

		$res = $this->Fatture->addFattura($this->request->data);

		if(isset($res->success) && $res->success==true){
		  $result['response'] = "OK";
		  $result['data'] = $res;
		  $result['msg'] = "Fattura inserita correttamente";
		  if(isset($data["redirect"]) && $data["redirect"]!=''){
			  $this->redirect($data["redirect"].'/'.$data['customField1'].'/'.$data['customField2'].'/'.$data['customField3']);
		  }
		} else {
		  $result['response'] = "KO";
		  $result['data'] = -1;
		  $result['msg'] = $res->error;
		}

		$this->_result = $result;

    }

    public function addFatturaPassiva(){

        $this->request->data["api_uid"] = Configure::read('dbconfig.ficgtw.API_UID');
        $this->request->data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $url = 'https://api.fattureincloud.it/v1/acquisti/nuovo';

        $response = $http->post(
            $url,
            json_encode($this->request->data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

        if(isset($res->success) && $res->success==true){
          $this->_result['response'] = "OK";
          $this->_result['data'] = $res;
          $this->_result['msg'] = "";
        } else {
          $this->_result['response'] = "KO";
          $this->_result['msg'] = $res->error;
        }

    }

    public function editFattura(){

		$data["api_uid"] = Configure::read('dbconfig.ficgtw.API_UID');
        $data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $url = 'https://api.fattureincloud.it/v1/fatture/modifica';

        $response = $http->post(
            $url,
            json_encode($data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

		if(isset($res->success) && $res->success==true){
            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = "";
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = $res->error;
        }

    }

    public function editFatturaPassiva(){

        $this->request->data["api_uid"] = Configure::read('dbconfig.ficgtw.API_UID');
        $this->request->data["api_key"] = Configure::read('dbconfig.ficgtw.API_KEY');

        $http = new Client();

        $url = 'https://api.fattureincloud.it/v1/acquisti/modifica';

        $response = $http->post(
            $url,
            json_encode($this->request->data),
            ['type' => 'json']
        );

        $res = json_decode($response->body);

        if(isset($res->success) && $res->success==true){
          $this->_result['response'] = "OK";
          $this->_result['data'] = $res;
          $this->_result['msg'] = "";
        } else {
          $this->_result['response'] = "KO";
          $this->_result['msg'] = $res->error;
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
