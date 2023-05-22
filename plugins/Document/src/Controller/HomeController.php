<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Home  (https://www.companee.it)
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

class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Document.Document');
        $this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(array('index','add','edit','delete','history','view','viewRev'));
    }



    public function index()
    {

        //echo $this->Document->test();

        #########################################################################################################
        //Applico i filtri

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";
        $option = [];
        if(isset($this->request->data['id_client']) && $this->request->data['id_client'] > 0){
            //echo "uso";
            $option['Documents.id_client'] = $this->request->data['id_client'];
        }

        if(isset($this->request->data['id_project']) && $this->request->data['id_project'] > 0){
            //echo "uso";
            $option['Documents.id_project'] = $this->request->data['id_project'];
        }

        #########################################################################################################

        $documents = $this->Document->getDocumentsByParent(0,$option);

        $number = $documents->count();

        $dcn = [];
        $drn = [];
        foreach ($documents as $key => $doc) {

            //Verifico per ogni documento quanti figli ha
            $dc = $this->Document->getDocumentsByParent($doc->id_document);
            $dcn[$doc->id] = $dc->count();

            //Verifico per ogni documento quante revisioni ci sono
            $dr = $this->Document->getDocumentsRevision($doc->id_document);
            $drn[$doc->id] = $dr->count();

        }

        //Recupero i clienti
        $clients = $this->Document->getAllClients();
        $c = [];
        foreach ($clients as $key => $value) {
            $c[$value->id] = $value->name;
        }

        #########################################################################################################
        //Recupero i progetti se ho filtrato su di un cliente altrimenti non poso

        $p = [];
        if(isset($this->request->data['id_client']) && $this->request->data['id_client'] > 0){

            $projects = $this->Document->getAllProjectByClients($this->request->data['id_client']);

            foreach ($projects as $key => $value) {
                $p[$value->id] = $value->name;
            }

        }

        #########################################################################################################
        $editedDoc = $this->request->session()->read('Document.editedDoc');
        if(!empty($editedDoc)){
            $this->request->session()->delete('Document.editedDoc');
            $this->set('editedDoc',$editedDoc);
        }

        $this->set('documents',$documents);
        $this->set('documentsNumber',$number);
        $this->set('dcn',$dcn);
        $this->set('drn',$drn);
        $this->set('clients',$c);
        $this->set('projects',$p);

    }

    public function add($parent = 0)
    {
        $document = $this->Document->_newEntity();

        //echo "<pre>"; print_r($this->request); echo "</pre>";

        if ($this->request->is(['post', 'put']) ) {

            //echo "<pre>"; print_r($this->request->data); echo "</pre>"; //exit;

            //Genero l'id univoco del documento
            $this->request->data['id_document'] = uniqid();

            //Essendo questo il primo slavataggio è anche l'ultimo
            $this->request->data['last_saved'] = 1;

            //Inserisco il parent che ho ricevuto o di default è 0
            $this->request->data['parent'] = $parent;

            //Inserisco la posizione come ultima contando tutti i documenti del livello
            $this->request->data['position'] = $this->Document->getMyChild($parent)->count();

            // Formatto i tags
            if(!empty($this->request->data['tags'])){
              foreach($this->request->data['tags'] as $key => $val){
                 if(is_numeric($val)){
                   $this->request->data['tags'][$key] = ['id' => intval($val)];
                 }else{
                   $this->request->data['tags'][$key] = ['name' => trim($val), 'level' => 0];
                 }
              }
            }
            //echo "<pre>"; print_r($this->request->data); echo "</pre>";

            //echo "<pre>"; print_r($document); echo "</pre>";
            array_walk_recursive($this->request->data, array($this,'trimByReference') );
            $document = $this->Document->_patchEntity($document, $this->request->data);

            //echo "<pre>"; print_r($document); echo "</pre>"; exit;

            if ($this->Document->_save($document)) {

                $this->Flash->success(__('Documento creato correttamente.'));
                return $this->redirect(['action' => 'index']);

            }
            $this->Flash->error(__('Impossibile creare il documento, si prega di riprovare.'));

        }

        //Se il parent è maggiore di 0 allora recupero il cliente ed il progetto per darli come def
        if($parent > 0){
            $document = $this->Document->getDocumentsByIdDocs($parent);

            $doc = $document->toArray();

            //debug($document->toArray());die;

            $this->request->data['id_azienda'] = $doc[0]->id_azienda;
            $this->request->data['id_order'] = $doc[0]->id_order;
            $azienda = $doc[0]->azienda;
            $ordine = $doc[0]->ordine;
            $this->set('azienda', $azienda);
            $this->set('ordine', $ordine);

        }

        //Recupero i clienti
        $clients = $this->Document->getAllClients();
        $first = "";

        $c = [];
        foreach ($clients as $key => $value) {
            $c[$value->id] = $value->name;
            if($first == ""){
                $first = $value->id;
            }
        }

        //Recupero i progetti del primo cliente o del parent
        //echo $first;
        if($parent > 0){
            $first = $this->request->data['id_azienda'];
        }
        $orders = $this->Document->getAllOrdersByClients($first);

        $o = [];
        foreach ($orders as $key => $value) {
            $o[$value->id] = $value->name;
        }
        //debug($document);die;
        $this->set('document', $document);

    }

    public function edit($id = 0)
    {
        if(!empty($this->request->data['id'])){
          $id = $this->request->data['id'];
          unset($this->request->data['id']);
        }
        if($id != 0){

            $document = $this->Document->_get($id);

            if($document->last_saved == 1){

                if ($this->request->is(['post', 'put'])) {

                    //Aggiorno il vecchio file
                    $document->last_saved = 0;

                    if($this->Document->_save($document)){

                        // Formatto i tags
                        if(!empty($this->request->data['tags'])){
                          foreach($this->request->data['tags'] as $key => $val){
                             if(is_numeric($val)){
                               $this->request->data['tags'][$key] = ['id' => intval($val)];
                             }else{
                               $this->request->data['tags'][$key] = ['name' => trim($val), 'level' => 0];
                             }
                          }
                        }

                        $this->request->data['last_saved'] = 1;
                        $this->request->data['id_document'] = $document->id_document;
                        $this->request->data['parent'] = $document->parent;
                        $this->request->data['position'] = $document->position;
                        $document = $this->Document->_newEntity();
                        array_walk_recursive($this->request->data, array($this,'trimByReference'));
                        $document = $this->Document->_patchEntity($document, $this->request->data);

                        $document = $this->Document->_save($document);
                        $this->request->session()->write('Document.editedDoc',$document->id);
                        $this->Flash->success(__('Documento salvato correttamente.'));
                        return $this->redirect(['action' => 'index']);

                    }

                }

            }else{
                if ($this->request->is(['post', 'put'])) {
                    $document = $this->Document->getDocumentsByIdDocs($document->id_document)->first();
                    $this->Flash->warning(__('Tentativo di modificare una revisione non attuale.'));
                    $document->text1 = '<h2 style="color:red;">VERSIONE ATTUALE: </h2><hr />'.$document->text1.
                    '<hr /><h2 style="color:red;">VERSIONE NON SALVATA: </h2><hr />'.$this->request->data['text1'];
                }else{
                    return $this->redirect(['action' => 'view' , $document->id_document]);
                }
            }
            $orders = $this->Document->getAllOrdersByClients($document->id_azienda);
            //Recupero il numero di revisioni del documento
            $rev = $this->Document->getDocumentsRevision($document->id_document);

            $revision = $rev->count();

            //Recupero l'albero delle gerarchie
            $gerarchia = $this->Document->getParentsTree();

            //Recupero solo i miei parent
            $parent = $this->Document->getMyParents($id);

            $this->set('id', $id);
            $this->set('document', $document);
            $this->set('orders', $orders);
            //$this->set('clients', $c);
            //$this->set('projects', $p);
            $this->set('revision', $revision);
            $this->set('gerarchia', $gerarchia);
            $this->set('parent', $parent);

        }else{
            $this->Flash->error(__('Id documento non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function delete($id = 0)
    {

        if($id != 0){

            $this->request->allowMethod(['get','post', 'delete']);

            $document = $this->Document->_get($id);
            if(!empty($this->Document->getChildTree($document['id_document'])) ){
              $this->Flash->warning(__('Il Documento id: {0} non può essere eliminato perchè contiene dei documenti.', h($id)));
              return $this->redirect(['action' => 'index']);
            }
            if ($this->Document->_delete($document)) {
                $documents = $this->Document->getMyChild($document->parent)->toArray();
                $this->Document->setPositionForAll($documents);
                $this->Flash->success(__('Il Documento id: {0} è stato correttamente cancellato.', h($id)));
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->success(__('Errore nella eliminazione del documento id: {0} , si prega di riprovare.', h($id)));
                return $this->redirect(['action' => 'index']);
            }

        }else{
            $this->Flash->error(__('Id documento non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }

    public function history($idDoc = "")
    {

        if($idDoc != 0){

            //Recupero le revisioni del documento
            $revisions = $this->Document->getDocumentsRevision($idDoc);

            $this->set('revisions', $revisions);

        }else{
            $this->Flash->error(__('Id documento non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }

    public function view($idDocs)
    {

        if($idDocs != 0){

            $document = $this->Document->getDocumentsByIdDocs($idDocs);

            $document = $document->toArray();
            //echo "<pre>"; print_r($document); echo "</pre>";

            $this->set('document', $document[0]);

        }else{
            $this->Flash->error(__('Id documento non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }

    public function viewRev($id)
    {

        $this->view = 'view';

        if($id != 0){

            $document = $this->Document->getDocumentsById($id);

            $document = $document->toArray();
            //echo "<pre>"; print_r($document); echo "</pre>";

            $this->set('document', $document[0]);

        }else{
            $this->Flash->error(__('Id documento non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }


    public function getUploadedFile($yearOrName,$month = '',$fileName = '')
    {

        if(empty($month) || empty($fileName)){
            $fileName = $yearOrName;
            $this->response->file('files'.DS.$fileName);
        }else{
            $year = $yearOrName;
            $this->response->file('files'.DS.$year.DS.$month.DS.$fileName);
        }

        return $this->response;

    }

    public function generateDocument()
    {
        $dati = $this->request->data;
        $content = '';
        $title = '';
        $tags = array();

        if(!empty($dati['id_document'])){
            if(empty($dati['tags'])){
              $dati['tags'] = array();
            }else{
              $tags = TableRegistry::get('Tags')->find()->where(['id IN' => $dati['tags']])->toArray();
            }

            if($dati['central']){
              $dati['style'] = 'style="text-align: center;"';
            }
            $document = $this->Document->getDocumentsByIdDocs($dati['id_document'])->first();
            $title = $document['title'];

            $content = "<h1 style=\"text-align:center;\">".$document->title."</h1>".$document->text1;
            $document->children = $this->Document->getChildTreeGeneration($dati['id_document']);
            unset($dati['id_document'],$dati['central']);

            $content = $this->Document->generateDocumentFromChildren($document['children'],$content,$dati);

        }

        $this->set('tags',$tags);
        $this->set('content',$content);
        $this->set('title', $title);

    }

}
