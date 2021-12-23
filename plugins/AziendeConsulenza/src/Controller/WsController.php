<?php
namespace Aziende\Controller;

use Cake\Routing\Router;
use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
/**
 * Aziende Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Contatti');
        //$this->loadComponent('Csrf');
        /* Inposto il log che viene richiamato solo settando scopes su 'deleted'
        *  per loggare le cancellazioni.
        */
        Log::config('aziende_log', [
              'className' => 'File',
              'path' => LOGS,
              'levels' => [],
              'scopes' => ['deleted'],
              'file' => 'plugin_aziende.log',
          ]);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['getAziende','saveAzienda','deleteAzienda','loadAzienda','getSedi','saveSede','deleteSede','loadSede','getContatti','saveContatto','deleteContatto','loadContatto']);

        $this->layout = 'ajax';
        $this->viewPath = 'Async';
        $this->view = 'default';
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");


    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }

    /**
     * Index method
     *
     * @return void
     */
    public function getAziende()
    {

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";
        $user = $this->request->session()->read('Auth.User');
        $pass['query'] = $this->request->query;

        $aziende = $this->Azienda->getAziende($pass);

        $totAziende = $this->Azienda->getTotAziende($pass);

        $out['total_rows'] = $totAziende;

        if(!empty($aziende)){

            foreach ($aziende as $key => $azienda) {

                $button = "";
                $button.= '<div class="btn-group">';
                $button.= '<span data-toggle="tooltip" data-placement="top" title="Dettaglio cliente"><a class="btn btn-sm btn-flat btn-default view" href="' . Router::url('/aziende/home/info/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" ><i class="fa fa-eye"></i></a></span>';
                $button.= '<span data-toggle="tooltip" data-placement="top" title="Modifica"><a data-toggle="modal" class="btn btn-sm btn-flat btn-default edit" href="#" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" data-target="#myModalAzienda"><i class="fa fa-pencil"></i></a></span>';
                $button.= '<span data-toggle="tooltip" data-placement="top" title="Pianificazione"><a class="btn btn-sm btn-flat btn-default pianificazione" href="' . Router::url('/consulenza/pianificazione/edit/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" ><i class="fa fa-th-list"></i></a></span>';
                $button.= '<span data-toggle="tooltip" data-placement="top" title="Gestione sedi"><a class="btn btn-sm btn-flat btn-default sedi" href="' . Router::url('/aziende/sedi/index/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '"><i class="fa fa-industry"></i></a></span>';
                $button.= '<span data-toggle="tooltip" data-placement="top" title="Gestione contatti"><a class="btn btn-sm btn-flat btn-default contatti" href="' . Router::url('/aziende/contatti/index/azienda/' . $azienda->id) . '" data-id="' . $azienda->id . '" ><i class="fa fa-users"></i></a></span>';
                if($user['role'] == 'admin')
                    $button.= '<span data-toggle="tooltip" data-placement="top" title="Elimina"><a class="btn btn-sm btn-flat btn-danger delete" href="#" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '"><i class="fa  fa-trash-o"></i></a></span>';
                $button.= '</div>';

                $rows[] = array(
                    $azienda->denominazione,
                    $azienda->nome,
                    $azienda->cognome,
                    $azienda->famiglia,
                    $azienda->telefono,
                    $azienda->cod_sispac,
                    $button
                );
            }

            $out['rows'] = $rows;

            $this->_result = $out;

        }else{

            $this->_result = array();
        }


    }

    public function saveAzienda($id = 0){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";

        if($id == 0){
            unset($this->request->data['id']);
        }

        $azienda = $this->Azienda->_newEntity();

        $azienda = $this->Azienda->_patchEntity($azienda, $this->request->data);

        if ($ret=$this->Azienda->_save($azienda)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio. Possibili cause: il Codice Sispac è già presente (deve essere univoco)");
        }

    }

    public function deleteAzienda($id = 0){

        if($id != 0){
            $user = $this->request->session()->read('Auth.User');

            $azienda = $this->Azienda->_get($id);
            $nome = $azienda["denominazione"];

            if($user['role']!='admin'){

              $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: utente non autorizzato.");
              Log::warning( 'Tentativo di cancellazione dell\'azienda "'.$nome.'" con id "'.$id.'" a opera dell\'utente non autorizzato "'.$user['username'].'"',['scope' => ['deleted']]);

            }else{

              if($this->Azienda->_checkBeforDelete($id)){
                if($this->Azienda->_delete($azienda)){

                    $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
                    Log::info( 'Avvenuta cancellazione dell\'azienda "'.$nome.'" con id "'.$id.'" a opera dell\'utente "'.$user['username'].'"',['scope' => ['deleted']]);
                }else{
                    $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
                }
              }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: l'azienda ha ancora delle task in calendario.");
                Log::warning( 'Tentativo di cancellazione dell\'azienda "'.$nome.'" id "'.$id.'" con ancora Task in calendario a opera dell\'utente "'.$user['username'].'"',['scope' => ['deleted']]);
              }
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadAzienda($id = 0){

        if($id != 0){

            $azienda = $this->Azienda->_get($id);

            $this->_result = array('response' => 'OK', 'data' => $azienda, 'msg' => "Azienda trovata");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function getSedi($idAzienda = 0, $for = "table"){

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        $pass['query'] = $this->request->query;

        $pass['idAzienda'] = $idAzienda;

        $sedi = $this->Sedi->getSedi($pass);

        if($for == "table"){

            $totSedi = $this->Sedi->getTotSedi($pass);

            //echo "<pre>"; print_r($sedi); echo "</pre>";
            //echo "<pre>"; print_r($totSedi); echo "</pre>";

            $out['total_rows'] = $totSedi;

            if(!empty($sedi)){

                foreach ($sedi as $key => $sede) {

                    $button = "";
                    $button.= '<div class="btn-group">';
                    $button.= '<a class="btn btn-sm btn-primary edit" href="#" data-id="' . $sede->id . '" data-toggle="modal" data-target="#myModalSede"><i class="fa  fa-pencil"></i></a>';
                    $button.= '<a class="btn btn-sm btn-primary contatti" href="' . Router::url('/aziende/contatti/index/sede/' . $sede->id) . '" data-id="' . $sede->id . '" ><i class="fa fa-users"></i></a>';
                    $button.= '<a class="btn btn-sm btn-danger delete" href="#" data-id="' . $sede->id . '" ><i class="fa  fa-trash-o"></i></a>';
                    $button.= '</div>';

                    $rows[] = array(
                        $sede->tipoSede->tipo,
                        $sede->indirizzo,
                        $sede->num_civico,
                        $sede->cap,
                        $sede->comune,
                        $sede->provincia,
                        $button
                    );
                }

                $out['rows'] = $rows;

                $this->_result = $out;

            }else{

                $this->_result = array();
            }

        }else{

            $this->_result = array('response' => 'OK', 'data' => $sedi, 'msg' => "ok");

        }
    }

    public function saveSede($idSede = 0){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";

        if($idSede == 0){
            unset($this->request->data['id']);
        }

        $sede = $this->Sedi->_newEntity();

        $sede = $this->Sedi->_patchEntity($sede, $this->request->data);

        if ($this->Sedi->_save($sede)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteSede($id = 0){

        if($id != 0){

            $sede = $this->Sedi->_get($id);

            if($this->Sedi->_delete($sede)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadSede($id = 0){

        if($id != 0){

            $sede = $this->Sedi->_get($id);

            $this->_result = array('response' => 'OK', 'data' => $sede, 'msg' => "Azienda trovata");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function getContatti($tipo = "", $id = 0){

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        $pass['query'] = $this->request->query;

        $pass['id'] = $id;
        $pass['tipo'] = $tipo;

        $contatti = $this->Contatti->getContatti($pass);
        $totContatti = $this->Contatti->getTotContatti($pass);

        //echo "<pre>"; print_r($contatti); echo "</pre>";
        //echo "<pre>"; print_r($totContatti); echo "</pre>";

        //exit;

        $out['total_rows'] = $totContatti;

        if(!empty($contatti)){

            foreach ($contatti as $key => $contatto) {

                $button = "";
                $button.= '<div class="btn-group">';
                $button.= '<a class="btn btn-sm btn-primary edit" href="#" data-id="' . $contatto->id . '" data-toggle="modal" data-target="#myModalContatto"><i class="fa  fa-pencil"></i></a>';
                //$button.= '<a class="btn btn-sm btn-primary contatti" href="' . Router::url('/aziende/contatti/index/sede/' . $sede->id) . '" data-id="' . $sede->id . '" ><i class="fa fa-users"></i></a>';
                $button.= '<a class="btn btn-sm btn-danger delete" href="#" data-id="' . $contatto->id . '" ><i class="fa  fa-trash-o"></i></a>';
                $button.= '</div>';

                $rows[] = array(
                    $contatto->cognome,
                    $contatto->nome,
                    $contatto->ruolo->ruolo,
                    $contatto->telefono,
                    $contatto->cellulare,
                    $contatto->email,
                    $button
                );
            }

            $out['rows'] = $rows;

            $this->_result = $out;

        }else{

            $this->_result = array();
        }

    }

    public function saveContatto($idContatto = 0){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";

        if($idContatto == 0){
            unset($this->request->data['id']);
        }

        $contatto = $this->Contatti->_newEntity();

        $contatto = $this->Contatti->_patchEntity($contatto, $this->request->data);

        if ($this->Contatti->_save($contatto)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteContatto($id = 0){

        if($id != 0){

            $contatto = $this->Contatti->_get($id);

            if($this->Contatti->_delete($contatto)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadContatto($id = 0){

        if($id != 0){

            $contatto = $this->Contatti->_get($id);

            $this->_result = array('response' => 'OK', 'data' => $contatto, 'msg' => "Azienda trovata");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function autocomplete($type = ""){

        //echo $type . " " . $this->request->query['term'];

        switch ($type) {
            case 'azienda':
                $term = $this->request->query['term'];

                $res = $this->Azienda->autocomplete($term);

                $this->_result =  $res;

                break;

            case 'famiglia':
                $term = $this->request->query['term'];

                $res = $this->Azienda->autocompleteFamiglia($term);

                $this->_result =  $res;

                break;

            default:
                $this->_result = array();
                break;
        }


    }

}
