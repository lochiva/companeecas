<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Ws (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Aziende\Controller;

use Cake\Routing\Router;
use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Collection\Collection;
use Cake\I18n\Date;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use RuntimeException;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\NotFoundException;
use Aziende\Error\ErrorCodes;

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
        $this->loadComponent('Aziende.Order');
        $this->loadComponent('Aziende.Fornitori');
        $this->loadComponent('Aziende.Clienti');
        $this->loadComponent('Aziende.Guest');
        $this->loadComponent('Aziende.Agreement');
        $this->loadComponent('Aziende.StatementCompany');
        $this->loadComponent('Aziende.Costs');
        $this->loadComponent('Aziende.StatementsNotifications');
    }

    public function isAuthorized($user)
    {
        if ($user['role'] == 'admin') {
            return true;
        }

        $authorizedActions = [
            'area_iv' => [
                'getAziende', 'saveAzienda', 'deleteAzienda', 'loadAzienda', 'getSedi', 'saveSede', 'deleteSede', 'loadSede', 'getContatti', 
                'saveContatto', 'deleteContatto', 'loadContatto', 'getContattiAzienda', 'autocompleteAziende', 'saveAziendaJson', 'sendAnagrafica',
                'sendEditAnagrafica', 'verifyDatiPiva', 'sendNoticeCompaneeAdminEdit', 'convertProvincia', 'convertComune', 'getGuests',
                'saveGuest', 'getSediForSearchGuest', 'deleteGuest', 'getGuest', 'searchCountry', 'searchGuest', 'removeGuestFromFamily', 
                'searchGuestsBySede', 'getGuestsNotificationsCount', 'getGuestsNotifications', 'saveGuestNotificationDone', 'saveAllGuestsNotificationsDone',
                'getAgreements', 'saveAgreement', 'deleteAgreement', 'getAgreement', 'getGuestsForPresenze', 'saveGuestsPresenze', 'loadGuestHistory',
                'getExitTypes', 'getRequestExitTypes', 'getTransferAziendaDefault', 'searchTransferAziende', 'searchTransferSedi', 'getReadmissionAziendaDefault',
                'getReadmissionSedeDefault', 'searchReadmissionAziende', 'searchReadmissionSedi', 'requestExitProcedure', 'authorizeRequestExitProcedure',
                'exitProcedure', 'confirmExit', 'transferProcedure', 'acceptTransfer', 'readmissionProcedure', 'getEducationalQualifications', 
                'autocompleteGuests', 'downloadGuestExitFile', 'getFiles', 'deleteFile', 'saveFiles', 'downloadFile', 'saveSingleCompany', 'checkRendiconti', 
                'getStatementCompanies', 'getPeriod', 'checkCig', 'getCosts', 'getStatementCompany', 'autocompleteCategories', 'downloadFileStatements', 
                'downloadFileCosts', 'downloadZipStatements', 'getPresenzeCount', 'getStatementsByAgreementId', 'getGuestPresenzeAfterDate'
                
            ],
            'ragioneria' => [
                'getAziende', 'loadAzienda', 'getSedi', 'loadSede', 'getContatti', 'loadContatto', 'getContattiAzienda', 'autocompleteAziende', 'verifyDatiPiva', 
                'sendNoticeCompaneeAdminEdit', 'convertProvincia', 'convertComune', 'getGuests', 'getSediForSearchGuest', 'getGuest', 'searchCountry', 
                'searchGuest', 'searchGuestsBySede', 'getGuestsNotificationsCount', 'getGuestsNotifications', 'saveGuestNotificationDone', 
                'saveAllGuestsNotificationsDone', 'getAgreements', 'getAgreement', 'getGuestsForPresenze', 'loadGuestHistory', 'getExitTypes', 'getRequestExitTypes', 
                'getTransferAziendaDefault', 'searchTransferAziende', 'searchTransferSedi', 'getReadmissionAziendaDefault', 'getReadmissionSedeDefault', 
                'searchReadmissionAziende', 'searchReadmissionSedi', 'getEducationalQualifications', 'autocompleteGuests', 'downloadGuestExitFile', 'getFiles', 
                'downloadFile', 'checkRendiconti', 'getStatementCompanies', 'getPeriod', 'checkCig', 'getCosts', 'getStatementCompany', 'autocompleteCategories', 
                'downloadFileStatements', 'downloadFileCosts', 'checkStatusStatementCompany',
                'downloadZipStatements', 'saveStatementsNotificationDone', 'getStatementsNotifications', 'saveAllStatementsNotificationsDone', 'getPresenzeCount'
            ],
            'ente_ospiti' => [
                'getSedi', 'saveSede', 'deleteSede', 'loadSede', 'getContatti', 'saveContatto', 'deleteContatto', 'loadContatto', 'getContattiAzienda', 
                'autocompleteAziende', 'sendAnagrafica', 'sendEditAnagrafica', 'verifyDatiPiva', 'sendNoticeCompaneeAdminEdit', 'convertProvincia', 
                'convertComune', 'getGuests', 'saveGuest', 'getSediForSearchGuest', 'deleteGuest', 'getGuest', 'searchCountry', 'searchGuest', 
                'removeGuestFromFamily', 'searchGuestsBySede', 'getGuestsNotificationsCount', 'getGuestsNotifications', 'saveGuestNotificationDone', 
                'saveAllGuestsNotificationsDone', 'getAgreements', 'saveAgreement', 'deleteAgreement', 'getAgreement', 'getGuestsForPresenze', 
                'saveGuestsPresenze', 'loadGuestHistory', 'getExitTypes', 'getRequestExitTypes', 'getTransferAziendaDefault', 'searchTransferAziende', 
                'searchTransferSedi', 'getReadmissionAziendaDefault', 'getReadmissionSedeDefault', 'searchReadmissionAziende', 'searchReadmissionSedi', 
                'requestExitProcedure', 'authorizeRequestExitProcedure', 'exitProcedure', 'confirmExit', 'transferProcedure', 'acceptTransfer', 
                'readmissionProcedure', 'getEducationalQualifications', 'autocompleteGuests', 'downloadGuestExitFile', 'getFiles', 'deleteFile', 'saveFiles', 
                'downloadFile', 'saveSingleCompany', 'checkRendiconti', 'loadAzienda', 'saveAziendaJson', 'getPresenzeCount', 'getStatementsByAgreementId',
                'getGuestPresenzeAfterDate'
            ],
            'ente_contabile' => [
                'getSedi', 'loadSede', 'getContatti', 'loadContatto', 'getContattiAzienda', 'autocompleteAziende',
                'convertProvincia', 'convertComune', 'getGuests', 'getSediForSearchGuest', 'getGuest', 'searchCountry', 'searchGuest', 
                'removeGuestFromFamily', 'searchGuestsBySede', 'getGuestsNotificationsCount', 'getGuestsNotifications', 'saveGuestNotificationDone', 
                'saveAllGuestsNotificationsDone', 'getAgreements', 'getAgreement', 'getGuestsForPresenze', 'loadGuestHistory', 'getExitTypes', 'getRequestExitTypes', 
                'getTransferAziendaDefault', 'searchTransferAziende', 'searchTransferSedi', 'getReadmissionAziendaDefault', 'getReadmissionSedeDefault', 
                'searchReadmissionAziende', 'searchReadmissionSedi', 'getEducationalQualifications', 'autocompleteGuests','downloadGuestExitFile', 'getFiles', 
                'downloadFile', 'checkRendiconti', 'getStatementCompanies', 'getPeriod', 'checkCig', 'saveStatement', 'getCosts', 'getStatementCompany', 
                'autocompleteCategories', 'saveCost', 'deleteCost', 'downloadFileStatements', 'downloadFileCosts', 'checkStatusStatementCompany',
                'downloadZipStatements', 'getCost', 'getPresenzeCount'
            ]
        ];

        if (
            !empty($user['role']) && 
            !empty($authorizedActions[$user['role']]) && 
            in_array($this->request->getParam('action'), $authorizedActions[$user['role']])
        ) {
            return true;
        }

        // Default deny
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        /*$this->Auth->allow(['getAziende','saveAzienda','deleteAzienda','loadAzienda','getSedi','saveSede','deleteSede','loadSede','getContatti','saveContatto',
                            'deleteContatto','loadContatto']);*/

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

        $user = $this->Auth->user();

        if(isset($user['role']) && $user['role'] == 'companee_admin'){
            $this->Auth->allow(['loadAzienda', 'verifyDatiPiva', 'saveAziendaJson', 'sendNoticeCompaneeAdminEdit']);
        }

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

        $res = $this->Azienda->getAziende($pass);
        //debug($res);die;
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $azienda) {

                $button = "";
                $button.= '<div class="btn-group">';
                $button.= '<a class="btn btn-xs btn-default view" data-toggle="tooltip" title="Visualizza" href="' . Router::url('/aziende/home/info/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" ><i class="fa fa-eye"></i></a>';
                $button.= '<a class="btn btn-xs btn-default edit" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" data-toggle="modal" data-target="#myModalAzienda" data-backdrop="static" data-keyboard="false"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
                $button.= '<a class="btn btn-xs btn-default sedi" data-toggle="tooltip" title="Strutture" href="' . Router::url('/aziende/sedi/index/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '"><i class="fa fa-home"></i></a>';
                /*$ficGtwUid = Configure::read('dbconfig.ficgtw.API_UID');
                if ($ficGtwUid != "") { // Il pulsante di fatture in cloud lo mostro solo se effettivamente è configurato, altrimenti non serve...
                    if($azienda->id_cliente_fattureincloud != 0 || $azienda->id_fornitore_fattureincloud != 0){
                        $button.= '<span data-toggle="tooltip" title="Anagrafica già inviata a Fatture in Cloud"><a class="btn btn-xs btn-default send-anagrafica-disabled anagrafica-sent" data-id="' . $azienda->id . '" ><i class="fa fa-link"></i></a></span>';
                    }else{
                        if($azienda->interno == true){
                            $button.= '<span data-toggle="tooltip" title="Gli interni non possono inviare l\'anagrafica"><a class="btn btn-xs btn-default send-anagrafica-disabled" data-id="' . $azienda->id . '" ><i class="fa fa-link"></i></a></span>';
                        }elseif($azienda->cliente == false && $azienda->fornitore == false){
                            $button.= '<span data-toggle="tooltip" title="Ruolo cliente/fornitore non definito."><a class="btn btn-xs btn-default send-anagrafica-disabled" data-id="' . $azienda->id . '" ><i class="fa fa-link"></i></a></span>';
                        }else{
                            $button.= '<a class="btn btn-xs btn-default send-anagrafica" data-id="' . $azienda->id . '" ><i data-toggle="tooltip" title="Invia anagrafica a Fatture in Cloud" href="#" class="fa fa-link"></i></a>';
                        }
                    }
                }*/
                
				$button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
                $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
                $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
                if ($azienda->id_tipo == 1) {
                    $button.= '<li><a class="contatti" href="' . Router::url('/aziende/agreements/index/' . $azienda->id) . '"><i style="margin-right: 8px;" class="fa fa-file-text-o"></i> Convenzioni</a></li>';
                }
                $button.= '<li><a class="contatti" href="' . Router::url('/aziende/contatti/index/azienda/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '"><i style="margin-right: 8px;" class="fa fa-address-book-o"></i> Contatti</a></li>';
                if ($user['role'] == 'admin' || $user['role'] == 'area_iv') {
                    $button.= '<li><a class="delete" data-id="'.$azienda->id.'" data-denominazione="'.$azienda->denominazione.'" href="#"><i style="margin-right: 10px; margin-left: 2px;" class="fa fa-trash"></i> Elimina</a></li>';
                }
                $button.= '</ul>';
                $button.= '</div>';
                $button.= '</div>';

                $countGuestsAzienda = $this->Azienda->countGuestsForAzienda($azienda->id);
                if ($azienda->id_tipo == 1) {
                    $countPostiForAzienda = $this->Azienda->countPostiForAzienda($azienda->id);
                } elseif ($azienda->id_tipo == 2) {
                    $countPostiForAzienda = $countGuestsAzienda;
                }

                $out['rows'][] = array(
                    htmlspecialchars($azienda->denominazione),
                    //htmlspecialchars($azienda->nome_cognome),
                    htmlspecialchars($azienda->telefono),
                    htmlspecialchars($azienda->email_info),
                    htmlspecialchars($azienda->sito_web),
                    htmlspecialchars($azienda['at']['name']),
                    //htmlspecialchars($azienda->piva),
					//htmlspecialchars($azienda->pa_codice),
                    $countGuestsAzienda.'/'.$countPostiForAzienda,
                    $button
                );
            }

            //$out['rows'] = $rows;

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
        array_walk_recursive($this->request->data, array($this,'trimByReference') );

        $azienda = $this->Azienda->_patchEntity($azienda, $this->request->data);

        if ($this->Azienda->_save($azienda)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteAzienda($id = 0){

        if($id != 0){

            $azienda = $this->Azienda->_get($id);

            if($this->Azienda->_delete($azienda)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadAzienda($id = 0){

        if($id != 0){
            $user = $this->request->session()->read('Auth.User');

                if(!$this->Azienda->verifyUser($user, $id)){
                    $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "L'utente non è autorizzato ad accedere alla risorsa");
                } else {
                    $azienda = $this->Azienda->_get($id);

                    if($this->request->session()->read('Auth.User.role') == 'companee_admin'){
                        unset($azienda->contatti);
                    }
        
                    if($azienda->logo){
                        $path = ROOT.DS.Configure::read('dbconfig.aziende.LOGO_PATH').$azienda->logo;
                        if (file_exists($path)) {
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $dataImg = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
                            $azienda->logo = $base64;
                        } else {
                            $azienda->logo = '';
                        }
                    }
        
                    $data['azienda'] = $azienda;
        
                    $sedi = $this->Sedi->getSedi(['idAzienda' => $id], $azienda->id_tipo);
                    if($sedi){
                        $data['sede'] = $sedi[0];
                    }
        
                    $http = new Client();
        
                    if($azienda->id_cliente_fattureincloud != 0){
        
                        $url = Router::url([
                            'plugin' => 'ficgtw',
                            'controller' => 'ws',
                            'action' => 'getcliente',
                            '_full' => true,
                            '_ssl' => Configure::read('localconfig.HttpsEnabled')
                        ]);
        
                        $res = $http->post(
                            $url,
                            [
                                'id' => $azienda->id_cliente_fattureincloud,
                            ]
                        );
        
                        $cliente = json_decode($res->body());
        
                        $data['cliente'] = $cliente->data;
        
                    }
        
                    if($azienda->id_fornitore_fattureincloud != 0){
        
                        $url = Router::url([
                            'plugin' => 'ficgtw',
                            'controller' => 'ws',
                            'action' => 'getfornitore',
                            '_full' => true,
                            '_ssl' => Configure::read('localconfig.HttpsEnabled')
                        ]);
        
                        $res = $http->post(
                            $url,
                            [
                                'id' => $azienda->id_fornitore_fattureincloud,
                            ]
                        );
        
                        $fornitore = json_decode($res->body());
        
                        $data['fornitore'] = $fornitore->data;
        
                    }
        
                    $this->_result = array('response' => 'OK', 'data' => $data, 'msg' => "Azienda trovata");

                }
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function getSedi($idAzienda = 0, $for = "table"){

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        $user = $this->request->session()->read('Auth.User');

        $pass['query'] = $this->request->query;

        $pass['idAzienda'] = $idAzienda;

        $azienda = TableRegistry::get('Aziende.Aziende')->get($idAzienda);

        $sedi = $this->Sedi->getSedi($pass, $azienda->id_tipo);

        if($for == "table"){

            $totSedi = $this->Sedi->getTotSedi($pass);

            $out['total_rows'] = $totSedi;

            if(!empty($sedi)){

                $rows = [];

                foreach ($sedi as $key => $sede) {

                    $button = "";
                    $button.= '<div class="btn-group">';
                    $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $sede->id . '" data-toggle="modal" data-target="#myModalSede" data-backdrop="static" data-keyboard="false"><i data-toggle="tooltip" title="Modifica" href="#" class="fa fa-pencil"></i></a>';
                    $button.= '<a class="btn btn-xs btn-default guests" data-toggle="tooltip" title="Ospiti" href="' . Router::url('/aziende/guests/index/' . $sede->id) . '"><i class="fa fa-users"></i></a>';
                    $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
                    $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
                    $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
                    if ($azienda->id_tipo == 1) {
                        $button.= '<li><a class="presenze" href="' . Router::url('/aziende/sedi/presenze?sede=' . $sede->id) . '"><i style="margin-right: 5px;margin-left: -3px;" class="fa fa-calendar"></i> Presenze</a></li>';
                    }
                    $button.= '<li><a class="contatti" href="' . Router::url('/aziende/contatti/index/sede/' . $sede->id) . '" data-id="' . $sede->id . '" ><i style="margin-right: 5px;margin-left: -3px;" class="fa fa-address-book-o"></i> Contatti</a></li>';
                    if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ente_ospiti') {
                        $button.= '<li><a class="delete" href="#" data-id="' . $sede->id . '"><i style="margin-right: 7px; margin-left: -2px;" class="fa fa-trash"></i> Elimina</a></li>';
                    }
                    $button.= '</ul>';
                    $button.= '</div>';
                    $button.= '</div>';

                    $countGuests = TableRegistry::get('Aziende.Guests')->countGuestsForSede($sede->id);
                    if ($azienda->id_tipo == 1) {
                        $postiSede = $sede->n_posti_effettivi;
                    } elseif ($azienda->id_tipo == 2) {
                        $postiSede = $countGuests;
                    }

                    $rows[$key][] = htmlspecialchars($sede->code_centro);
                    $rows[$key][] = htmlspecialchars($sede['stm']['name']);
                    if ($azienda->id_tipo == 1) {
                        $rows[$key][] = htmlspecialchars($sede['stc']['name']);
                    }
                    $rows[$key][] = htmlspecialchars($sede->indirizzo);
                    $rows[$key][] = htmlspecialchars($sede->num_civico);
                    $rows[$key][] = htmlspecialchars($sede->cap);
                    $rows[$key][] = htmlspecialchars($sede->c['des_luo']);
                    $rows[$key][] = htmlspecialchars($sede->p['des_luo']);
                    $rows[$key][] = $countGuests.'/'.$postiSede;
                    if ($azienda->id_tipo == 1) {
                        $rows[$key][] = htmlspecialchars($sede['sto']['name']);
                    }
                    $rows[$key][] = $button;
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

        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        $data = $this->request->data;
        
        $azienda = TableRegistry::get('Aziende.Aziende')->get($data['id_azienda']);

        $saveType = '';
        $new = false;
        if($idSede == 0){
            unset($data['id']);
            if ($azienda->id_tipo == 2) {
                $saveType = 'CREATE_CENTER_UKRAINE';
            } else {
                $saveType = 'CREATE_CENTER';
            }
            $new = true;
        }

        $sede = $this->Sedi->_newEntity(); 

        if ($azienda->id_tipo == 2) {
            $data['exdl_28022022'] = 0;
            $data['id_tipo_capitolato'] = 0;
            $data['id_tipologia_centro'] = 0;
            $data['id_tipologia_ospiti'] = 0;
            $data['n_posti_effettivi'] = 0;
            $data['operativita'] = 1;
        }


        //Se operatività = chiusa controllo che la sede non abbia ospiti
        $validOperativita = true;
        if (!$new && empty($data['operativita']) && $this->Sedi->checkSedeHasGuests($idSede)) {
            $validOperativita = false;
        }

        if ($validOperativita) {
            $sede = $this->Sedi->_patchEntity($sede, $data);

            if (!empty($data['comune'])) {
                $sede->comune = $data['comune'];
            }
            if (!empty($data['provincia'])) {
                $sede->provincia = $data['provincia'];
            }

            if ($this->Sedi->_save($sede)) {
                // Salvataggio notifica creazione struttura
                if (!empty($saveType)) {
                    $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
                    $notification = $guestsNotifications->newEntity();
                    $notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
                    $notificationData = [
                        'type_id' => $notificationType->id,
                        'azienda_id' => $sede->id_azienda,
                        'sede_id' => $sede->id,
                        'guest_id' => 0,
                        'user_maker_id' => $this->request->session()->read('Auth.User.id')
                    ];
                    $guestsNotifications->patchEntity($notification, $notificationData);
                    $guestsNotifications->save($notification);
                }

                $this->_result = array('response' => 'OK', 'data' => $new, 'msg' => "Salvato");
            }else{
                $errorMsg = '';
                foreach($sede->errors() as $field => $errors){ 
                    foreach($errors as $rule => $msg){ 
                        $errorMsg .= ' '.$msg;
                    }
                }  
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio. ".$errorMsg);
            }
        } else {
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Attenzione! Non è possibile chiudere una struttura con ospiti presenti.");
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

            $this->_result = array('response' => 'OK', 'data' => $sede, 'msg' => "Struttura trovata");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function getContatti($tipo = "", $id = 0){

        $pass['query'] = $this->request->query;

        $pass['id'] = $id;
        $pass['tipo'] = $tipo;

        $contatti = $this->Contatti->getContattiTable($pass);

        $out['total_rows'] = $contatti['tot'];

        if(!empty($contatti['res'])){

            $user = $this->Auth->user();

            foreach ($contatti['res'] as $key => $contatto) {

                $button = "";
                $button.= '<div class="btn-group">';
                $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $contatto->id . '" data-toggle="modal" data-target="#myModalContatto" data-backdrop="static" data-keyboard="false"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
                if (($user['role'] == 'admin') || (($user['role'] == 'area_iv' || $user['role'] == 'ente_ospiti') && !isset($contatto->userName))) {
                    $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
                    $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
                    $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
                    $button.= '<li><a class="delete" href="#" data-id="' . $contatto->id . '"><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
                    $button.= '</ul>';
                    $button.= '</div>';
                } 
                $button.= '</div>';

                $login = "";
                if($contatto->userName){
                    if($user['role'] == 'admin'){
                        $login.= '<a class="contact-login" href="'.Router::url('/admin/registration/users/changeUser/' . $contatto->id_user).'" title="'.__('Prendi l\'identità').'">';
                        //$login.= '<i class="fa fa-sign-in"><i/> ';
                        $login.= $contatto->userName . ' [' . $contatto->userRole . ']';
                        $login.= '</a>';
                    }else{
                        $login .= $contatto->userName . ' [' . $contatto->userRole . ']';
                    }
                }

                $out['rows'][] = array(
                    htmlspecialchars($contatto->cognome),
                    htmlspecialchars($contatto->nome),
                    htmlspecialchars($contatto->azienda),
                    htmlspecialchars($contatto->ruolo),
                    $login,
                    htmlspecialchars($contatto->telefono),
                    htmlspecialchars($contatto->cellulare),
                    htmlspecialchars($contatto->email),
                    $button
                );
            }

            //debug($out);die;

            $this->_result = $out;

        }else{

            $this->_result = array();
        }

    }

    public function saveContatto($idContatto = 0){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";

        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        $data = $this->request->data;

        $contatto = $this->Contatti->_newEntity();

        if($idContatto == 0){
            unset($data['id']);

            $contatti = TableRegistry::get('Aziende.Contatti');

            $lastContatto = $contatti->find()
                ->where(['id_azienda' => $data['id_azienda'], 'deleted' => '0'])
                ->order(['ordering DESC'])
                ->first();
            
            if($lastContatto){
                $contatto->ordering = $lastContatto->ordering + 1;
            }
        }

        if(!empty($data['skills'])){
            foreach ($data['skills'] as $skill) {
                $data['Skills'][] = array('id' => $skill);
            }
        }else{
            $data['Skills'] = array();
        }
        unset($data['skills']);
        $contatto = $this->Contatti->_patchEntity($contatto, $data);

        if ($this->Contatti->_save($contatto)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteContatto($id = 0){

        if($id != 0){
            $user = $this->Auth->user();


            $contatto = $this->Contatti->_get($id, ['contain' => ['Users']]);

            if ($user['role'] === 'admin' || empty($contatto->user)) {
                if($this->Contatti->_delete($contatto)){
                    $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
                }else{
                    $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
                }
            } else {
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "L'utente non è abilitato alla cancellazione.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadContatto($id = 0){

        if($id != 0){

            $contatto = $this->Contatti->_get($id);

            $this->_result = array('response' => 'OK', 'data' => $contatto, 'msg' => "Contatto trovato");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function getContattiAzienda($id = 0, $role = 0)
    {
      if($id != 0){

          $res = $this->Contatti->getContattiAzienda($id,$role);

          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Contatti trovati");

      }else{
          $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
      }


    }

    public function getOrders($idOrAction = 0){

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        $pass['query'] = $this->request->query;
        $orders = $this->Order->getOrders($pass,$idOrAction);

        //debug($orders);
        $out = array('rows'=>[], 'total_rows'=>$orders['tot'] );
        if(!empty($orders['res'])){
          foreach($orders['res'] as $key => $order){

            $button = "";
            $button.= '<div class="btn-group">';
            $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $order['id'] . '" data-toggle="modal" data-target="#myModalOrder"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
            $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
            $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
            $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
            $button.= '<li><a class="delete" href="#" data-id="' . $order['id'] . '"><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
            $button.= '</ul>';
            $button.= '</div>';
            $button.= '</div>';
            $out['rows'][$key] = [
              htmlspecialchars($order['name']),
              htmlspecialchars($order['note']),
              htmlspecialchars($order['contatto']),
              $order['created']->i18nFormat('yyyy-MM-dd HH:mm:ss'),
              ($order['id_status'] == 2 ?  $order['closed']->i18nFormat('yyyy-MM-dd HH:mm:ss') : ''),
              '<span class="badge orderStatusBG-'.$order['id_status'].'">'.htmlspecialchars($order['status']).'</span>',
              $button
            ];
            if($idOrAction === 'all'){
              array_unshift($out['rows'][$key], '<a href="'.Router::url('/aziende/home/info/'.$order['id_azienda']).'">'.$order['azienda'].'</a>');
            }
          }
        }


        $this->_result = $out;

    }

    public function loadOrder($id = 0){

        if($id != 0){

            $order = $this->Order->_get($id);

            $this->_result = array('response' => 'OK', 'data' => $order, 'msg' => "Order trovato");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function saveOrder($idOrder = 0){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";

        if($idOrder == 0){
            unset($this->request->data['id']);
        }

        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        $order = $this->Order->saveOrder($this->request->data);

        if ($order) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteOrder($id = 0){

        if($id != 0){

            $order = $this->Order->_get($id);

            if($this->Order->_delete($order)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function getOrdersAzienda($id = 0, $selectedId = '')
    {
      if($id != 0){

          $res = $this->Order->getOrdersAzienda($id, 1000, $selectedId);

          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Ordini trovati");

      }else{
          $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
      }


    }

    public function startOrdersStatus()
    {
        if($this->Auth->user('role') != 'admin'){
              $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Non hai i permessi sufficienti.");
              return;
        }
        $orders = TableRegistry::get('Aziende.Orders')->find()->toArray();
        $res = TableRegistry::get('Aziende.OrdersHistory')->initializeHistory($orders);
        TableRegistry::get('Aziende.Orders')->updateAll(['id_status' => 1],['id' > 0]);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Sono stati initializzati ".$res." ordini");
    }

    public function autocompleteAziende($type = 'all')
    {
        $nome = $this->request->query['q'];
        $res = array();

        if(strlen($nome) < 3){
          $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Devi inserire almeno tre lettere.");
        }else{
          $res = $this->Azienda->getAziendaAutocomplete($nome,$type);
          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
        }
    }

    public function autocompleteOrders($idAzienda = 0)
    {
        $nome = $this->request->query['q'];
        $res = array();

        if(strlen($nome) < 3){
          $this->_result = array('response' => 'KO', 'data' => $res, 'msg' => "Devi inserire almeno tre lettere.");
        }else{
          $res = $this->Order->getOrdersAutocomplete($nome,$idAzienda);
          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
        }
    }

    public function getFornitoriFatture($idOrAction=0)
    {

        $pass['query'] = $this->request->query;
        $fatture = $this->Fornitori->getFatture($pass,$idOrAction);

        $out = array('rows'=>[], 'total_rows'=>$fatture['tot'] );
        if(!empty($fatture['res'])){
          foreach($fatture['res'] as $key => $fattura){

            $button = "";
            $button.= '<div class="btn-group">';
            $button.= '<a class="btn btn-xs btn-default edit-passive-invoice" href="#" data-id="' . $fattura['id'] . '" data-toggle="modal" data-target="#myModalFatturaPassiva"><i data-toggle="tooltip" title="Modifica" href="#" class="fa fa-pencil"></i></a>';
            if($fattura->id_fattureincloud != 0){
                $button.= '<div data-toggle="tooltip" title="Dati fattura già inviati a Fatture in Cloud" style="display:inline;"><a class="btn btn-xs btn-default send-invoice-disabled" href="#" data-id="' . $fattura['id'] . '" style="background-color:#54ce85;" ><i class="fa fa-link"></i></a></div>';
			}else{
                if($fattura->issuer_id_fattureincloud != 0){
                    $button.= '<a class="btn btn-xs btn-default send-invoice" href="#" data-id="' . $fattura['id'] . '" data-toggle="tooltip" title="Invia dati fattura a Fatture in Cloud"><i class="fa fa-link"></i></a>';
                }else{
                    $button.= '<div data-toggle="tooltip" title="Anagrafica azienda non presente su fatture in cloud" style="display:inline;"><a class="btn btn-xs btn-default send-invoice-disabled" href="#" data-id="' . $fattura['id'] . '" style="background-color:#e8e8e8;" ><i class="fa fa-link" style="color:#9b9b9b;"></i></a></div>';
                }
			}
            $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
            $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
            $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
            $button.= '<li><a class="clone" href="#" data-id="' . $fattura['id'] . '" data-toggle="modal" data-target="#myModalFatturaPassiva"><i style="margin-right: 7px;" href="#" class="fa fa-clone"></i> Duplica</a></li>';
            $button.= '<li><a class="delete-passive-invoice" href="#" data-id="' . $fattura['id'] . '"><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
            $button.= '</ul>';
            $button.= '</div>';
            $button.= '</div>';

            $out['rows'][$key] = [
              htmlspecialchars($fattura['payer']),
              htmlspecialchars($fattura['num']),
              (!empty($fattura['emission_date'])? $fattura['emission_date']->i18nFormat('dd/MM/yyyy') : '' ),
              htmlspecialchars($fattura['description']),
              htmlspecialchars($fattura['purpose']),
              htmlspecialchars($fattura['amount_topay']),
              (!empty($fattura['due_date'])? $fattura['due_date']->i18nFormat('dd/MM/yyyy') : '' ),
              (!empty($fattura['attachment']) ?
              '<a href="'.Router::url('/aziende/fornitori/getAttachment/'.htmlspecialchars($fattura['attachment'])).'" target="_blank">Allegato</a>'  : ''),
              '<span class="badge invoicePaid-'.$fattura['is_paid'].'">'.$fattura['is_paid']."</span>",
              $button
            ];
            if($idOrAction === 'all'){
              array_unshift($out['rows'][$key], htmlspecialchars($fattura['issuer']).'</a>');
            }
          }
        }


        $this->_result = $out;

    }

    public function getClientiFatture($idOrAction=0)
    {

        $pass['query'] = $this->request->query;
        $fatture = $this->Clienti->getFatture($pass,$idOrAction);

        $out = array('rows'=>[], 'total_rows'=>$fatture['tot'] );
        if(!empty($fatture['res'])){
          foreach($fatture['res'] as $key => $fattura){

            $button = "";
            $button.= '<div class="btn-group">';
            $button.= '<a class="btn btn-xs btn-default edit-active-invoice" href="#" data-id="' . $fattura['id'] . '" data-toggle="modal" data-target="#myModalFatturaAttiva"><i data-toggle="tooltip" title="Modifica" href="#" class="fa fa-pencil"></i></a>';
            if($fattura->id_fattureincloud != 0){
                $button.= '<div data-toggle="tooltip" title="Fattura già generata" style="display:inline;"><a class="btn btn-xs btn-default send-invoice-disabled" href="#" data-id="' . $fattura['id'] . '" style="background-color:#54ce85;" ><i class="fa fa-link"></i></a></div>';
			}else{
                if($fattura->payer_id_fattureincloud != 0){
                    $button.= '<a class="btn btn-xs btn-default send-invoice" href="#" data-id="' . $fattura['id'] . '" data-toggle="tooltip" title="Genera fattura"><i class="fa fa-link"></i></a>';
                }else{
                    $button.= '<div data-toggle="tooltip" title="Anagrafica azienda non presente su fatture in cloud" style="display:inline;"><a class="btn btn-xs btn-default send-invoice-disabled" href="#" data-id="' . $fattura['id'] . '" style="background-color:#e8e8e8;" ><i class="fa fa-link" style="color:#9b9b9b;"></i></a></div>';
                }
			}
            $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
            $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
            $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
            $button.= '<li><a class="clone" href="#" data-id="' . $fattura['id'] . '" data-toggle="modal" data-target="#myModalFatturaAttiva"><i style="margin-right: 7px;" href="#" class="fa fa-clone"></i> Duplica</a></li>';
            $button.= '<li><a class="delete-active-invoice" href="#" data-id="' . $fattura['id'] . '"><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
            $button.= '</ul>';
            $button.= '</div>';
            $button.= '</div>';

            $out['rows'][$key] = [
              htmlspecialchars($fattura['issuer']),
              htmlspecialchars($fattura['num']),
              (!empty($fattura['emission_date'])? $fattura['emission_date']->i18nFormat('dd/MM/yyyy') : '' ),
              htmlspecialchars($fattura['amount_topay']),
              (!empty($fattura['due_date'])? $fattura['due_date']->i18nFormat('dd/MM/yyyy') : '' ),
              (!empty($fattura['attachment']) ?
              '<a href="'.Router::url('/aziende/fornitori/getAttachment/'.htmlspecialchars($fattura['attachment'])).'" target="_blank">Allegato</a>'  : ''),
              '<span class="badge invoicePaid-'.$fattura['is_paid'].'">'.$fattura['is_paid']."</span>",
              $button
            ];
            if($idOrAction === 'all'){
                array_splice($out['rows'][$key], 1, 0, htmlspecialchars($fattura['payer']).'</a>');
            }
          }
        }


        $this->_result = $out;

    }

    public function loadFattura($id = 0){

        if($id != 0){

            $fattura = TableRegistry::get('Aziende.Invoices')->get($id,['contain' => ['Issuer','Orders']]);

            if($fattura['metodo'] == 'not'){
				$fattura['metodo'] = '';
			}

            $this->_result = array('response' => 'OK', 'data' => $fattura, 'msg' => "Order trovato");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function loadFatturaAttiva($id = 0){

        if($id != 0){

            $fattura = TableRegistry::get('Aziende.Invoices')->get($id,['contain' => ['Payer','Orders', 'InvoicesArticles']]);

            if($fattura['metodo'] == 'not'){
				$fattura['metodo'] = '';
			}

            $this->_result = array('response' => 'OK', 'data' => $fattura, 'msg' => "Order trovato");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function saveFatturaFornitore(){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";
        if(empty($this->request->data['id'])){
            unset($this->request->data['id']);
        }
        $msg = '';

        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        if(!empty($this->request->data['attachment_file']['tmp_name'])){
            $attachment = $this->Fornitori->uploadAttachment($this->request->data['attachment_file']);
            if($attachment){
              $this->request->data['attachment']= $attachment;
            }else{
              $msg = "Errore durante il salvataggio dell'allegato";
            }
        }
        if(!empty($this->request->data['xml_file']['tmp_name'])){
            $xml = $this->Fornitori->uploadAttachment($this->request->data['xml_file']);
            if($xml){
              $this->request->data['xml']= $xml;
            }else{
              $msg = "Errore durante il salvataggio del file xml.";
            }
        }
        $invoice = $this->Fornitori->saveInvoice($this->request->data);

        if ($invoice) {
            if(!empty($invoice->id_fattureincloud)){
                $this->sendInvoice($invoice->id, true);
            }
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => $msg);
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function saveFatturaCliente(){

        //echo "<pre>"; print_r($this->request->data); echo "</pre>";
        if(empty($this->request->data['id'])){
            unset($this->request->data['id']);
        }
        $msg = '';

        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        if(!empty($this->request->data['attachment_file']['tmp_name'])){
            $attachment = $this->Clienti->uploadAttachment($this->request->data['attachment_file']);
            if($attachment){
              $this->request->data['attachment']= $attachment;
            }else{
              $msg = "Errore durante il salvataggio dell'allegato";
            }
        }
        $invoice = $this->Clienti->saveInvoice($this->request->data);

        if ($invoice) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => $msg);
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteFattura($id = 0)
    {

        if($id != 0){

            if($this->Fornitori->deleteInvoice($id)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function deleteFatturaAttiva($id = 0)
    {

        if($id != 0){

            if($this->Clienti->deleteInvoice($id)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function deleteArticleInvoice()
    {
        $id_articolo = $this->request->data['article_id'];
        $articles = TableRegistry::get('Aziende.invoicesArticles');
        $article = $articles->get($id_articolo);
        $article->deleted = 1;

        if($articles->save($article)){
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Articolo cancellato con successo.");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione dell'articolo.");
        }
    }

    public function fattureCausaliChart($year=1 ,$month = 0)
    {
        $data = $this->Fornitori->getFattureChartPerCausale($year,$month);

        $this->_result = array('response' => 'OK', 'data' => $data, 'msg' => "");

    }

    public function saveAziendaJson()
    {
        $this->request->allowMethod('post');

        try {
            if ($this->request->is('json')) {
            
                $data = $this->request->data;

                $user = $this->request->session()->read('Auth.User');

                if(!empty($data['id']) && !$this->Azienda->verifyUser($user, $data['id'])){
                    $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "L'utente non è autorizzato.");
                } else {
                    if(empty($data['id'])){
                        unset($data['id']);
                    }
            
                    // //Se ci sono sedi con operatività = chiusa controllo che non abbiano ospiti
                    $validOperativita = true;
                    if (!empty($data['sedi'])) {
                        $sedi = json_decode($data['sedi'], true) ?? [];
                        foreach ($sedi as $sede) {
                            if (!empty($sede['id']) && empty($sede['operativita']) && $this->Sedi->checkSedeHasGuests($sede['id'])) {
                                $validOperativita = false;
                                break;
                            }
                        }
                    }
                    if ($validOperativita) {
                        if ($this->Azienda->saveAziendaJson($data)) {
                            if((isset($data['id_cliente_fattureincloud']) && $data['id_cliente_fattureincloud'] != 0) || (isset($data['id_fornitore_fattureincloud']) && $data['id_fornitore_fattureincloud'] != 0)){
                                $msg = false;
                                //Aggiorno o creo cliente su fattureincloud
                                $dataC = $data;
                                $dataC['fornitore'] = false;
                                if($dataC['cliente'] && $dataC['id_cliente_fattureincloud'] != 0){
                                    $msg = $this->sendEditAnagrafica($dataC);
                                }elseif($dataC['cliente'] && $dataC['id_cliente_fattureincloud'] == 0){
                                    $msg = $this->sendAnagrafica($dataC['id']);
                                }
                                //Aggiorno o creo fornitore su fattureincloud
                                $dataF = $data;
                                $dataF['cliente'] = false;
                                if(!$msg && $dataF['fornitore'] && $dataF['id_fornitore_fattureincloud'] != 0){
                                    $msg = $this->sendEditAnagrafica($dataF);
                                }elseif(!$msg && $dataF['fornitore'] && $dataF['id_fornitore_fattureincloud'] == 0){
                                    $msg = $this->sendAnagrafica($dataF['id']);
                                }
                                if(!$msg){
                                    $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
                                }else{
                                    $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore durante il salvataggio di Fatture in Cloud: ".$msg);
                                }
                            }else{
                                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
                            }
                        }else{
                            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore durante il salvataggio");
                        }
                    } else {
                        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Attenzione! Non è possibile chiudere una struttura con ospiti presenti.");
                    }
                }
            } else {
                throw new \Cake\Http\Exception\MethodNotAllowedException();
            }
        } catch(\Exception $e){
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => $e->getMessage());
        }

    }

	public function sendAnagrafica($idAzienda){

		$aziende = TableRegistry::get('Aziende.Aziende');
		$azienda = $aziende->get($idAzienda);
		//debug($azienda);die;
		$data = array();

		$sedi = TableRegistry::get('Aziende.Sedi');
		$sede = $sedi->getSedeFatturaincloud($idAzienda);

		if($sede){
            
			$indirizzoVia = $sede->indirizzo.', '.$sede->num_civico;
			$indirizzoCap = $sede->cap;
			$indirizzoCitta = $sede->comune;
			$indirizzoProvincia = $sede->provincia;
		}else{
			$indirizzoVia = '';
			$indirizzoCap = '';
			$indirizzoCitta = '';
			$indirizzoProvincia = '';
		}

		$data = [
			'nome' => $azienda->denominazione,
			'referente' => $azienda->cognome.' '.$azienda->nome,
			'piva' => $azienda->piva,
			'cf' => $azienda->cf,
			'paese_iso' => $azienda->cod_paese,
			'mail' => $azienda->email_info,
			'tel' => $azienda->telefono,
			'indirizzo_via' => $indirizzoVia,
			'indirizzo_cap' => $indirizzoCap,
			'indirizzo_citta' => $indirizzoCitta,
			'indirizzo_provincia' => $indirizzoProvincia,
		];

		$http = new Client();

		//Verifico se anagrafica gia presente su fatture in cloud

		if($azienda->cf != '' || $azienda->piva != ''){

			$url = Router::url([
				'plugin' => 'ficgtw',
				'controller' => 'ws',
				'action' => 'getidclientefornitore',
				'_full' => true,
				'_ssl' => Configure::read('localconfig.HttpsEnabled')
			]);

			$res = $http->post(
				$url,
				[
					'cf' => $azienda->cf,
					'piva' => $azienda->piva
				]
			);

			$clienteFornitoreId = json_decode($res->body());
		}

		$message = '';

		if($azienda->cliente){
			if(!isset($clienteFornitoreId) || $clienteFornitoreId->data->clienteId == ''){
				$url = Router::url([
					'plugin' => 'ficgtw',
				    'controller' => 'ws',
				    'action' => 'addclientefornitore',
				    'cliente',
					'_full' => true,
					'_ssl' => Configure::read('localconfig.HttpsEnabled')
				]);

				$response = $http->post(
		            $url,
		            $data
		        );

				$response = json_decode($response->body());

				if(isset($response->data->success) && $response->data->success){

					$azienda->id_cliente_fattureincloud = $response->data->id;

					if($aziende->save($azienda)){
						$message .= 'Anagrafica cliente salvata.';
					}else{
						$message .= 'Errore nel salvataggio dell\'anagrafica cliente.';
					}

				}else{
					$msg = $response->msg;
				}
			}else{
				$azienda->id_cliente_fattureincloud = $clienteFornitoreId->data->clienteId;
				$message .= 'Anagrafica cliente remota già esistente. ';
				if($aziende->save($azienda)){
					$message .= 'Id salvato in locale. ';
				}else{
					$message .= 'Errore nel salvataggio dell\'id in locale. ';
				}
			}

		}

		if(!isset($msg) && $azienda->fornitore){

			if(!isset($clienteFornitoreId) || $clienteFornitoreId->data->fornitoreId == ''){

				$url = Router::url([
					'plugin' => 'ficgtw',
				    'controller' => 'ws',
				    'action' => 'addclientefornitore',
				    'fornitore',
					'_full' => true,
					'_ssl' => Configure::read('localconfig.HttpsEnabled')
				]);

			$response2 = $http->post(
	            $url,
	            $data
	        );

				$response2 = json_decode($response2->body());

				if(isset($response2->data->success) && $response2->data->success){

					$azienda->id_fornitore_fattureincloud = $response2->data->id;

					if($aziende->save($azienda)){
						$message .= 'Anagrafica fornitore salvata. ';
					}else{
						$message .= 'Errore nel salvataggio dell\'anagrafica fornitore. ';
					}

				}else{
					$msg = $response2->msg;
				}

			}else{
				$azienda->id_fornitore_fattureincloud = $clienteFornitoreId->data->fornitoreId;
				$message .= 'Anagrafica fornitore remota già esistente. ';
				if($aziende->save($azienda)){
					$message .= 'Id salvato in locale. ';
				}else{
					$message .= 'Errore nel salvataggio dell\'id in locale. ';
				}
			}

		}

		if(isset($msg)){
			$this->_result['response'] = 'KO';
			$this->_result['data'] = -1;
			$this->_result['msg'] = $msg;
		}else{
			if($azienda->cliente == 0 && $azienda->fornitore == 0){
				$this->_result['response'] = 'KO';
				$this->_result['data'] = -1;
				$this->_result['msg'] = 'L\'azienda non è nè un cliente nè un fornitore.';
			}else{
				$this->_result['response'] = 'OK';
				$this->_result['data'] = -1;
				$this->_result['msg'] = $message;
			}
		}

	}

	public function sendEditAnagrafica($data){

		$sedi = TableRegistry::get('Aziende.Sedi');
		$sede = $sedi->getSedeFatturaincloud($data['id']);

		$sendData = [
			'nome' => $data['denominazione'],
			'referente' => $data['cognome'].' '.$data['nome'],
			'piva' => $data['piva'],
			'cf' => $data['cf'],
			'paese_iso' => $data['cod_paese'],
			'mail' => $data['email_info'],
			'tel' => $data['telefono'],
			'indirizzo_via' => !empty($sede) ? $sede->indirizzo.', '.$sede->num_civico : '', 
			'indirizzo_cap' => !empty($sede) ? $sede->cap : '',
			'indirizzo_citta' => !empty($sede) ? $sede->comune : '',
			'indirizzo_provincia' => !empty($sede) ? $sede->provincia : '',
		];

		$http = new Client();
		$msg = false;
		if($data['cliente']){

			$sendData['id'] = $data['id_cliente_fattureincloud'];

			$url = Router::url([
				'plugin' => 'ficgtw',
				'controller' => 'ws',
				'action' => 'editclientefornitore',
				'cliente',
				'_full' => true,
				'_ssl' => Configure::read('localconfig.HttpsEnabled')
			]);;

			$response = $http->post(
	            $url,
	            $sendData
	        );

			$response = json_decode($response->body());

			if(isset($response->data->success) && $response->data->success){
			}else{
				$msg = $response->msg;
			}

		}

		if(!$msg && $data['fornitore']){

			$sendData['id'] = $data['id_fornitore_fattureincloud'];

			$url = Router::url([
				'plugin' => 'ficgtw',
				'controller' => 'ws',
				'action' => 'editclientefornitore',
				'fornitore',
				'_full' => true,
				'_ssl' => Configure::read('localconfig.HttpsEnabled')
			]);

			$response2 = $http->post(
	            $url,
	            $sendData
	        );

			$response2 = json_decode($response2->body());

			if(isset($response2->data->success) && $response2->data->success){
			}else{
				$msg = $response2->msg;
			}

		}

		if(!$msg){
			if(!isset($response) && !isset($response2)){
				$msg = 'L\'azienda non è nè un cliente nè un fornitore.';
			}
		}

		return $msg;
	}


    public function sendInvoice($idFattura, $edit = false){

		$fatture = TableRegistry::get('Aziende.Invoices');
		$fattura = $fatture->get($idFattura);

        $aziende = TableRegistry::get('Aziende.Aziende');
		$azienda = $aziende->get($fattura['id_issuer']);

        $invoicesPurposes = TableRegistry::get('Aziende.InvoicesPurposes');
		$invPurp = $invoicesPurposes->get($fattura['id_purpose']);

		$sendData = [
            'tipo' => 'spesa',
			'id_fornitore' => $azienda['id_fornitore_fattureincloud'],
			'autocompila_anagrafica' => true,
			'salva_anagrafica' => false,
            'data' => isset($fattura['emission_date']) ? $fattura['emission_date']->format('d/m/Y') : '',
			'descrizione' => $fattura['description'],
      		'categoria' => $invPurp['name'],
            'importo_netto' => $fattura['amount_noiva'],
			'importo_iva' => $fattura['amount_iva'],
			'valuta' => 'EUR',
			'valuta_cambio' => 1,
			'ritenuta_acconto' => $fattura['ritenuta_acconto'],
			'deducibilita_tasse' => 100,
            'detraibilita_iva' => 100,
            'ammortamento' => 1,
            'numero_fattura' => $fattura['num'],
			'lista_pagamenti' => [
				[
				'data_scadenza' => isset($fattura['due_date']) ? $fattura['due_date']->format('d/m/Y') : '',
				'importo' => $fattura['amount_topay'],
                'metodo' => $fattura['metodo'],
				'data_saldo' => isset($fattura['paid_date']) ? $fattura['paid_date']->format('d/m/Y') : '',
				]
			],
		];

		$http = new Client();
        $msg = false;

        if(!empty($fattura['id_fattureincloud'])){
            $sendData['id'] = $fattura['id_fattureincloud'];

            $url = Router::url([
                'plugin' => 'ficgtw',
                'controller' => 'ws',
                'action' => 'editfatturapassiva',
                'cliente',
                '_full' => true,
                '_ssl' => Configure::read('localconfig.HttpsEnabled')
            ]);
        }else{  
            $url = Router::url([
                'plugin' => 'ficgtw',
                'controller' => 'ws',
                'action' => 'addfatturapassiva',
                'cliente',
                '_full' => true,
                '_ssl' => Configure::read('localconfig.HttpsEnabled')
            ]);
        }
        
		$response = $http->post(
            $url,
            $sendData
        );

		$response = json_decode($response->body());

		if(isset($response->data->success) && $response->data->success){
            $fattura->id_fattureincloud = $response->data->new_id;
            $fatture->save($fattura);
		}else{
			$msg = $response->msg;
		}

        if($msg){
			$this->_result['response'] = 'KO';
			$this->_result['data'] = -1;
			$this->_result['msg'] = $msg;
		}else{
			$this->_result['response'] = 'OK';
			$this->_result['data'] = -1;
			$this->_result['msg'] = 'Fattura generata correttamente.';
		}
    }
    
    public function sendInvoiceAttiva($idFattura){

		$fatture = TableRegistry::get('Aziende.Invoices');
		$fattura = $fatture->get($idFattura, ['contain' => ['InvoicesArticles']]);

        $aziende = TableRegistry::get('Aziende.Aziende');
		$azienda = $aziende->get($fattura['id_payer']);

        $invoicesPurposes = TableRegistry::get('Aziende.InvoicesPurposes');

		$sendData = [
            'id_cliente' => $azienda['id_cliente_fattureincloud'],
            'nome' => $azienda['denominazione'],
			'autocompila_anagrafica' => true,
			'salva_anagrafica' => false,
            'data' => isset($fattura['emission_date']) ? $fattura['emission_date']->format('d/m/Y') : '',
			'valuta' => 'EUR',
			'valuta_cambio' => 1,
			'rit_acconto' => $fattura['ritenuta_acconto'],
            'numero' => $fattura['num'],
			'lista_pagamenti' => [
				[
				'data_scadenza' => isset($fattura['due_date']) ? $fattura['due_date']->format('d/m/Y') : '',
				'importo' => $fattura['amount_topay'],
                'metodo' => $fattura['metodo'],
				'data_saldo' => isset($fattura['paid_date']) ? $fattura['paid_date']->format('d/m/Y') : '',
				]
            ],
            'lista_articoli'=> []
        ];
        
        foreach($fattura['invoices_articles'] as $articolo){
            $invPurp = $invoicesPurposes->get($articolo['id_purpose']);

            $sendData['lista_articoli'][] = [
                'nome' => $articolo['name'],
                'prezzo_netto' => $articolo['amount_noiva'],
				'prezzo_lordo' => $articolo['amount'],
                'cod_iva' => $articolo['cod_iva'],
                'quantita' => $articolo['quantity'],
                'categoria' => $invPurp['name'],
                'descrizione' => $articolo['description']
			];
        }

		$http = new Client();
        $msg = false;
        $url = Router::url([
            'plugin' => 'ficgtw',
            'controller' => 'ws',
            'action' => 'addfattura',
            'cliente',
            '_full' => true,
            '_ssl' => Configure::read('localconfig.HttpsEnabled')
        ]);

		$response = $http->post(
            $url,
            $sendData
        );

		$response = json_decode($response->body());

		if(isset($response->data->success) && $response->data->success){
            $fattura->id_fattureincloud = $response->data->new_id;
            $fatture->save($fattura);
		}else{
			$msg = $response->msg;
		}

        if($msg){
			$this->_result['response'] = 'KO';
			$this->_result['data'] = -1;
			$this->_result['msg'] = $msg;
		}else{
			$this->_result['response'] = 'OK';
			$this->_result['data'] = -1;
			$this->_result['msg'] = 'Fattura generata correttamente.';
		}
	}

    public function verifyDatiPiva($piva){
        $client = new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
        $response = $client->checkVat(
            array(
                'countryCode' => 'IT',
                'vatNumber' => $piva
            )
        );

        if($response){
			$this->_result = ['response' => 'OK', 'data' => $response, 'msg' => ''];
		}else{
			$this->_result = ['response' => 'KO', 'data' => '', 'msg' => 'Errore'];
		}
    }


    public function sendNoticeCompaneeAdminEdit()
    {
        $companeeAdminId = $this->request->session()->read('Auth.User.id');
        $contact = TableRegistry::get('Aziende.Contatti')->find()->where(['id_user' => $companeeAdminId])->first(); 
        $azienda = TableRegistry::get('Aziende.Aziende')->get($contact->id_azienda);

        $users999 = TableRegistry::get('Users')->find()->where(['level' => '999'])->toArray();

        $noticeTable = TableRegistry::get('Notifications');
        
        foreach($users999 as $user){ 
            if($companeeAdminId != $user->id){
                $noticeData = [
                    'id_creator' => $companeeAdminId,
                    'id_dest' => $user->id,
                    'message' => "Modificati dati dell'azienda ".$azienda->denominazione." (id: ".$azienda->id.")."
                ];

                $noticeTable->sendNotice($noticeData);
            }
        }
    }

    public function saveOrderContatti()
    {
        $data = $this->request->data;

        $contatti = TableRegistry::get('Aziende.Contatti');

        foreach($data['subtabcontatto'] as $order => $id){ 
            $contatto = $contatti->get($id);
            $contatto->ordering = $order;

            $contatti->save($contatto);
        }

        $this->_result = ['response' => 'OK', 'data' => '', 'msg' => 'Ordine contatti salvato correttamente.'];
    }

    public function saveOrderSedi()
    {
        $data = $this->request->data;

        $sedi = TableRegistry::get('Aziende.Sedi');

        foreach($data['subtabsede'] as $order => $id){
            $sede = $sedi->get($id);
            $sede->ordering = $order;

            $sedi->save($sede);
        }

        $this->_result = ['response' => 'OK', 'data' => '', 'msg' => 'Ordine sedi salvato correttamente.'];
    }

    public function importXmlPassiveInvoice()
    {
        $xml = $this->request->data['xml_file'];

        //check file type
        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE),$xml['tmp_name']); 
        $type = explode('/', $type)[1];

        $data = [];

        if($type != 'xml'){
            $this->_result['msg'] = "Formato del file non valido. Il file caricato deve essere di tipo XML.";
            return;
	  	}else{
            $get = file_get_contents($xml['tmp_name']);
            $xmlArr = simplexml_load_string($get);      

            if(!empty($xmlArr->FatturaElettronicaHeader) && !empty($xmlArr->FatturaElettronicaBody)){
                $aziende = TableRegistry::get('Aziende.Aziende');

                $state = (string)$xmlArr->FatturaElettronicaHeader->CedentePrestatore->DatiAnagrafici->IdFiscaleIVA->IdPaese;
                $piva = (string)$xmlArr->FatturaElettronicaHeader->CedentePrestatore->DatiAnagrafici->IdFiscaleIVA->IdCodice;
                $cf = (string)$xmlArr->FatturaElettronicaHeader->CedentePrestatore->DatiAnagrafici->CodiceFiscale;

                $where = [];

                if(!empty($piva)){
                    $where['OR']['piva'] = $piva;
                    $where['OR']['CONCAT("'.$state.'", piva) ='] = $piva;
                }
                if(!empty($cf)){
                    $where['OR']['cf'] = $cf;
                }
                
                $fornitore = $aziende->find()
                                ->where([$where])
                                ->first(); 

                $data['id_issuer'] = '';
                
                if($fornitore){
                    $data['id_issuer'] = $fornitore->id;
                    $data['denominazione_issuer'] = $fornitore->denominazione;
                }

                $emissionDate = explode('-', (string)$xmlArr->FatturaElettronicaBody->DatiGenerali->DatiGeneraliDocumento->Data);
                $dueDate = explode('-', (string)$xmlArr->FatturaElettronicaBody->DatiPagamento->DettaglioPagamento->DataScadenzaPagamento);

                $data['emission_date'] = !empty($emissionDate) ? $emissionDate[2].'/'.$emissionDate[1].'/'.$emissionDate[0] : '';
                $data['num'] = (string)$xmlArr->FatturaElettronicaBody->DatiGenerali->DatiGeneraliDocumento->Numero;
                $data['amount_noiva'] = number_format((string)$xmlArr->FatturaElettronicaBody->DatiBeniServizi->DatiRiepilogo->ImponibileImporto, 2, ',', '');
                $data['amount_iva'] = number_format((string)$xmlArr->FatturaElettronicaBody->DatiBeniServizi->DatiRiepilogo->Imposta, 2, ',', '');
                $data['amount'] = number_format((string)$xmlArr->FatturaElettronicaBody->DatiGenerali->DatiGeneraliDocumento->ImportoTotaleDocumento, 2, ',', '');
                $data['amount_topay'] = number_format((string)$xmlArr->FatturaElettronicaBody->DatiPagamento->DettaglioPagamento->ImportoPagamento, 2, ',', '');
                $data['description'] = (string)$xmlArr->FatturaElettronicaBody->DatiGenerali->DatiGeneraliDocumento->Causale;
                $data['due_date'] = !empty($dueDate) ? $dueDate[2].'/'.$dueDate[1].'/'.$dueDate[0] : '';

                $this->_result['result'] = 'OK';
                $this->_result['data'] = $data;
                $this->_result['msg'] = "Xml caricato correttamente.";
                return;
            }else{
                $this->_result['msg'] = "File XML non valido.";
                return;
            }
        }
    }

    public function convertProvincia($provincia)
    {
        $luoghi = TableRegistry::get('Luoghi');

        $codProv = $luoghi->find()
            ->where(['c_cat' => '', 'des_luo' => $provincia])
            ->first();

        if($codProv){
            $this->_result['response'] = 'OK';
            $this->_result['data'] = $codProv['c_luo'];
        }else{
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Nessuna provincia trovata.";
        }
    }

    public function convertComune($comune)
    {
        $luoghi = TableRegistry::get('Luoghi');

        $codCom = $luoghi->find()
            ->where(['c_cat !=' => '', 'des_luo' => $comune])
            ->first();

        if($codCom){
            $this->_result['response'] = 'OK';
            $this->_result['data'] = $codCom['c_luo'];
        }else{
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Nessun comune trovata.";
        }
    }

    public function getGuests($sedeId)
    {
        $user = $this->request->session()->read('Auth.User');
        $sede = TableRegistry::get('Aziende.Sedi')->get($sedeId);

        if(!$this->Azienda->verifyUser($user, $sede['id_azienda'])){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $pass['query'] = $this->request->query;

        $azienda = TableRegistry::get('Aziende.Aziende')->get($sede['id_azienda']);

        if(isset($pass['query']['filter'][8])){
			if($pass['query']['filter'][8] == 'No'){
				$pass['query']['filter'][8] = 0;
			}elseif($pass['query']['filter'][8] == 'Sì'){
				$pass['query']['filter'][8] = 1;
			}
		}

        if(isset($pass['query']['filter'][10])){
			if($pass['query']['filter'][10] == 'No'){
				$pass['query']['filter'][10] = 0;
			}elseif($pass['query']['filter'][10] == 'Sì'){
				$pass['query']['filter'][10] = 1;
			}
		}

        $showOld = filter_var($pass['query']['showOld'], FILTER_VALIDATE_BOOLEAN);

        $res = $this->Guest->getGuests($sedeId, $azienda['id_tipo'], $showOld, $pass);

        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            $today = date('Y-m-d');

            foreach ($res['res'] as $key => $guest) {  

                $buttons = "";
				$buttons .= '<div class="button-group" style="min-width:50px;">';
                $buttons .= '<a href="'.Router::url('/aziende/guests/guest?sede='.$sedeId.'&guest='.$guest['id']).'" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Modifica ospite"><i class="fa fa-pencil"></i></a>'; 
                if ($user['role'] == 'admin' || $user['role'] == 'area_iv') {
                    $buttons .= '<a href="#" role="button" class="btn btn-xs btn-danger delete-guest" data-id="'.$guest['id'].'" data-toggle="tooltip" title="Elimina ospite"><i class="fa fa-trash"></i></a>'; 
                } elseif ($user['role'] == 'ente_ospiti') {
                    if (empty($guest['original_guest_id']) && $guest['status_id'] == 1 && $guest['created']->format('Y-m-d') == $today) {
                        $buttons .= '<a href="#" role="button" class="btn btn-xs btn-danger delete-guest" data-id="'.$guest['id'].'" data-toggle="tooltip" title="Elimina ospite"><i class="fa fa-trash"></i></a>'; 
                    } else {
                        $buttons .= '<span data-toggle="tooltip" title="L\'eliminazione di un ospite è possibile unicamente il giorno dell\'inserimento e in assenza di trasferimenti"><a href="#" role="button" class="btn btn-xs btn-danger delete-guest disabled" data-id="'.$guest['id'].'"><i class="fa fa-trash"></i></a></span>'; 
                    }
                }
                $buttons .= '</div>';

                //Icona di avvertenza se superata data dello stato bozza
                $alertDraftIcon = '';
                $draftExpiration = empty($guest['draft_expiration']) ? '' : $guest['draft_expiration']->format('Y-m-d');
                if ($guest['draft'] && $today > $draftExpiration) {
                    $alertDraftIcon = '<span class="alert-draft" data-toggle="tooltip" title="Inserire il CUI o l\'ID Vestanet"><i class="fa fa-exclamation-triangle"></i></span>';
                }

                //Stato ospite
                $statusColor = $guest['exit_request_status_id'] === null ? $guest['gs']['color'] : $guest['gers']['color'];
                $statusName = $guest['exit_request_status_id'] === null ? $guest['gs']['name'] : $guest['gs']['name'] . ' - ' . $guest['gers']['name'];
                $status = '<span class="guest-status" style="background-color: '.$statusColor.'">'.$statusName.'</span>';

                //Colore riga in base alle presenze (se ospite in stato "in struttura" e non sospeso e ente tipo cas)
                $classPresenze = '';
                if ($guest['status_id'] == 1 && !$guest['suspended'] && $azienda['id_tipo'] == 1) {
                    $lastPresenzaDate = '';
                    $today = date('Y-m-d');
                    $lastPresenza = TableRegistry::get('Aziende.Presenze')->getGuestLastPresenzaByDate($guest['id'], $today);
                    if (!empty($lastPresenza)) {
                        $lastPresenzaDate = $lastPresenza['date']->format('Y-m-d');
                    } elseif (!empty($guest['check_in_date'])) {
                        $lastPresenzaDate = $guest['check_in_date']->format('Y-m-d');
                    }
                    if (!empty($lastPresenzaDate)) {
                        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));
                        if ($lastPresenzaDate < $today && $lastPresenzaDate >= $threeDaysAgo) {
                            $classPresenze = 'warning-presenze';
                        } elseif ($lastPresenzaDate < $threeDaysAgo) {
                            $classPresenze = 'danger-presenze';
                        }
                    }
                }

                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.(empty($guest['check_in_date']) ? '' : $guest['check_in_date']->format('d/m/Y')).'</td>';
                if ($azienda['id_tipo'] == 1) {
                    $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$guest['cui'].'</td>';
                    $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$guest['vestanet_id'].'</td>';
                }
                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$guest['name'].'</td>';
                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$guest['surname'];
                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.(empty($guest['birthdate']) ? '' : $guest['birthdate']->format('d/m/Y')).'</td>';
                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$guest['sex'].'</td>';
                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$guest['l']['des_luo'].'</td>';
                if ($azienda['id_tipo'] == 1) {
                    $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.($guest['draft'] ? 'Sì' : 'No').'</td>';
                    $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$alertDraftIcon.' '.(empty($guest['draft_expiration']) ? '' : $guest['draft_expiration']->format('d/m/Y')).'</td>';
                    $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.($guest['suspended'] ? 'Sì' : 'No').'</td>';
                }
                $out['rows'][$key][] = '<td class="'.$classPresenze.'">'.$status.'</td>';
                $out['rows'][$key][] = $buttons;

            }

        }

        $this->_result = $out;
    }

    public function saveGuest()
    {
        $data = $this->request->data;

        $guests = TableRegistry::get('Aziende.Guests');
        $sede = TableRegistry::get('Aziende.Sedi')->get($data['sede_id']);
        $azienda = TableRegistry::get('Aziende.Aziende')->get($sede->id_azienda);

		if(empty($data['id'])){
            $entity = $guests->newEntity();
            $data['status_id'] = 1;
            if ($azienda->id_tipo == 1) {
                $saveType = 'CREATE_GUEST';
            } else if ($azienda->id_tipo == 2) {
                $saveType = 'CREATE_GUEST_UKRAINE';
            }
		}else{
			$entity = $guests->get($data['id']);
            if ($azienda->id_tipo == 1) {
                $saveType = 'UPDATE_GUEST';
            } else if ($azienda->id_tipo == 2) {
                $saveType = 'UPDATE_GUEST_UKRAINE';
            }
        }

        $data['check_in_date'] = empty($data['check_in_date']) || $data['check_in_date'] == 'null' ? NULL : new Time(substr($data['check_in_date'], 0, 33));

        $presenze = [];
        if (!empty($data['id']) && !empty($data['check_in_date'])) {
            // controllo che non esistano presenze segnate per una data precedente alla data di check-in che si desidera salvare
            $presenze = TableRegistry::get('Aziende.Presenze')->getPresenzeGuestPrecedentiCheckIn($data['id'], $data['check_in_date']);
        }

        if (empty($presenze)) {
            $data['minor'] = filter_var($data['minor'], FILTER_VALIDATE_BOOLEAN);
            $data['minor_family'] = filter_var($data['minor_family'], FILTER_VALIDATE_BOOLEAN);
            $data['minor_alone'] = filter_var($data['minor_alone'], FILTER_VALIDATE_BOOLEAN);
            $data['family_guest_id'] = $data['family_guest'];
            $data['birthdate'] = new Time(substr($data['birthdate'], 0, 33));
            $data['electronic_residence_permit'] = filter_var($data['electronic_residence_permit'], FILTER_VALIDATE_BOOLEAN);
            if ($data['ente_type'] == 1) {
                $data['draft'] = filter_var($data['draft'], FILTER_VALIDATE_BOOLEAN);
                $data['suspended'] = filter_var($data['suspended'], FILTER_VALIDATE_BOOLEAN);
                $data['draft_expiration'] = empty($data['draft_expiration']) || $data['draft_expiration'] == 'null' ? '' : new Time(substr($data['draft_expiration'], 0, 33));
            } elseif ($data['ente_type'] == 2) {
                unset($data['draft']);
                unset($data['suspended']);
                unset($data['draft_expiration']);
            }

            $data['family'] = json_decode($data['family']);

            $familyAdult = false;
            if (!empty($data['family'])) {
                foreach($data['family'] as $familyGuest) {
                    if (!$familyGuest->minor) {
                        $familyAdult = true;
                    }
                }
            }

            // Ospiti minori non soli necessitano di una famiglia con adulto
            if(!$data['minor'] || $data['minor_alone'] || $familyAdult) {
                $guests->patchEntity($entity, $data);

                if($guests->save($entity)){
                    // Salvataggio ospiti famiglia
                    if(count($data['family']) > 0 && !($entity->minor && $entity->minor_alone)){
                        $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');

                        $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $entity->id])->first();

                        $familyId = '';
                        if($guestHasFamily){
                            // Ospite ha gia una famiglia a cui associare
                            $familyId = $guestHasFamily['family_id'];
                        }else{
                            // Cerco se uno degli ospiti associati ha già una famiglia a cui associare
                            foreach($data['family'] as $guest){ 
                                if(!empty($guest->id)){
                                    $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
                                    if($guestHasFamily){
                                        $familyId = $guestHasFamily['family_id'];
                                        $newGuestFamily = $guestsFamilies->newEntity();
                                        $newGuestFamily->family_id = $familyId;
                                        $newGuestFamily->guest_id = $entity->id;
                                        $guestsFamilies->save($newGuestFamily);
                                        break;
                                    }
                                }
                            }
                            if($familyId == ''){
                                // Creo nuova famiglia a cui associare
                                $newGuestFamily = $guestsFamilies->newEntity();
                                $familyId = (int)$guestsFamilies->find()->order('family_id DESC')->first()['family_id'] + 1;
                                $newGuestFamily->family_id = $familyId;
                                $newGuestFamily->guest_id = $entity->id;
                                $guestsFamilies->save($newGuestFamily);
                            }
                        }

                        foreach($data['family'] as $guest){ 
                            $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();

                            if(empty($guestHasFamily)){
                                $newGuestFamily = $guestsFamilies->newEntity();
                                $newGuestFamily->family_id = $familyId;
                                $newGuestFamily->guest_id = $guest->id;
                                $guestsFamilies->save($newGuestFamily);
                            }
                        }
                    } else {
                        // Se ospite minore solo rimuovo famiglia se presente
                        $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
                        $guestFamily = $guestsFamilies->find()->where(['guest_id' => $entity->id])->first();
                        if ($guestFamily) {
                            $guestsFamilies->removeGuestFromFamily($guestFamily, $entity->sede_id);
                        }
                    }

                    // Creazione notifica
                    $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
                    $notification = $guestsNotifications->newEntity();
                    $notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
                    $notificationData = [
                        'type_id' => $notificationType->id,
                        'azienda_id' => $sede->id_azienda,
                        'sede_id' => $sede->id,
                        'guest_id' => $entity->id,
                        'user_maker_id' => $this->request->session()->read('Auth.User.id')
                    ];
                    $guestsNotifications->patchEntity($notification, $notificationData);
                    $guestsNotifications->save($notification);

                    if ($saveType == 'CREATE_GUEST' || $saveType == 'CREATE_GUEST_UKRAINE') {
                        // Aggiornamento storico
                        $guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
                        $history = $guestsHistory->newEntity();

                        $historyData['guest_id'] = $entity->id;
                        $historyData['azienda_id'] = $sede->id_azienda;
                        $historyData['sede_id'] = $sede->id;
                        $historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
                        $historyData['operation_date'] = date('Y-m-d');
                        $historyData['guest_status_id'] = 1;

                        $guestsHistory->patchEntity($history, $historyData);
                        $guestsHistory->save($history);
                    }

                    $this->_result['response'] = "OK";
                    $this->_result['data'] = $entity->id;
                    $this->_result['msg'] = "Ospite salvato con successo.";
                }else{
                    $message = "Errore nel salvataggio dell'ospite."; 
                    $fieldLabelsList = $guests->getFieldLabelsList();
                    foreach($entity->errors() as $field => $errors){ 
                        foreach($errors as $rule => $msg){ 
                            $message .= "\n" . $fieldLabelsList[$field].': '.$msg;
                        }
                    }  
                    $this->_result['response'] = "KO";
                    $this->_result['msg'] = $message;
                }
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nel salvataggio dell'ospite: L'ospite è un minore e non si dichiara solo pertanto è necessario associarlo ad un nucleo familiare con adulto.";
            }
        } else { 
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nel salvataggio dell'ospite: data di check-in non valida. Esistono delle presenze segnate per l'ospite con data precedente alla data di check-in che si desidera salvare.";
        }
    }

    public function getSediForSearchGuest($guestId)
    {
        $guests = TableRegistry::get('Aziende.Guests');

        $guest = $guests->get($guestId);

        if (empty($guest['original_guest_id'])) {
            $where = [
                'OR' => [
                    'Guests.id' => $guest['id'],
                    'Guests.original_guest_id' => $guest['id']
                ]
            ];
        } else {
            $where = [
                'OR' => [
                    'Guests.id' => $guest['original_guest_id'],
                    'Guests.original_guest_id' => $guest['original_guest_id']
                ]
            ];
        }

        // Se ruolo ente, ricerca ospiti solo per quell'ente
        $user = $this->request->session()->read('Auth.User');
        if ($user['role'] == 'ente_ospiti' || $user['role'] == 'ente_contabile') {
            $contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
            $where['a.id'] = $contatto['id_azienda'];
        }

        $res = $guests->find()
            ->select($guests)
            ->select(['s.indirizzo', 's.num_civico', 'c.des_luo', 'c.s_prv', 'a.denominazione', 'gs.name'])
            ->where($where)
            ->join([
                [
                    'table' => 'sedi',
                    'alias' => 's',
                    'type' => 'LEFT',
                    'conditions' => 's.id = Guests.sede_id'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'c',
                    'type' => 'LEFT',
                    'conditions' => 'c.c_luo = s.comune'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'LEFT',
                    'conditions' => 'a.id = s.id_azienda'
                ],
                [
                    'table' => 'guests_statuses',
                    'alias' => 'gs',
                    'type' => 'LEFT',
                    'conditions' => 'gs.id = Guests.status_id'
                ]
            ])
            ->order(['Guests.check_in_date' => 'DESC', 'Guests.check_out_date' => 'DESC'])
            ->toArray();

        if($res){
            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = "";
        }else{
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nel recupero dei dati.";
        }
    }

    public function deleteGuest()
    {
        $id = $this->request->data['id'];

        if ($id) {
            $guests = TableRegistry::get('Aziende.Guests');
            $guest = $guests->get($id);

            $user = $this->request->session()->read('Auth.User');
            $today = date('Y-m-d');

            if (
                $user['role'] == 'admin' || 
                (empty($guest['original_guest_id']) && $guest['status_id'] == 1 && $guest['created']->format('Y-m-d') == $today)
            ) {

                $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
                $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $id])->first();

                //Controllo se ospite unico adulto di un nucleo familiare
                $delete = true;
                if (!$guest['minor']) {
                    if ($guestHasFamily) {
                        $countFamilyAdults = $guestsFamilies->countFamilyAdults($guestHasFamily['family_id'], $guest['sede_id']);
                        if ($countFamilyAdults == 1) {
                            $delete = false;
                        }
                    }
                }

                if ($delete) {
                    $guest->deleted = 1;
                    if ($guests->save($guest)) {
                        // Rimuovo relazione familiare
                        if ($guestHasFamily) {
                            $guestsFamilies->removeGuestFromFamily($guestHasFamily, $guest['sede_id']);
                        }
                        
                        $this->_result['response'] = "OK";
                        $this->_result['msg'] = "Eliminazione dell'ospite avvenuta con successo";
                    } else {
                        $this->_result['response'] = "KO";
                        $this->_result['msg'] = "Errore nell'eliminazione dell'ospite";
                    }
                } else {
                    $this->_result['response'] = "KO";
                        $this->_result['msg'] = "Errore nell'eliminazione dell'ospite: l'ospite è l'unico adulto del nucleo familiare";
                }
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Non è possibile eliminare l'ospite.";
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nell'eliminazione dell'ospite: id mancante.";
        }
    }

    public function getGuest($id)
	{
        $user = $this->request->session()->read('Auth.User');
        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($id, ['contain' => ['FamilyGuests', 'Countries', 'EducationalQualifications']]);
        $sede = TableRegistry::get('Aziende.Sedi')->get($guest['sede_id']);

        if(!$this->Azienda->verifyUser($user, $sede['id_azienda'])){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

		if($guest){
            if (!empty($guest['educational_qualification']) && $guest['educational_qualification']['parent'] > 0) {
                $guest['educational_qualification_child'] = $guest['educational_qualification'];
                $guest['educational_qualification'] = TableRegistry::get('Aziende.GuestsEducationalQualifications')->get($guest['educational_qualification']['parent']);
            } else {
                $guest['educational_qualification_child'] = '';
            }
            if ($guest->status_id != 1 || $guest->exit_request_status_id !== null) {
                //Dati per messaggi di stato
                $lastHistory = TableRegistry::get('Aziende.GuestsHistories')->getLastGuestHistoryByStatus($guest->id, $guest->status_id);
                if ($lastHistory->exit_type_id) {
                    $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($lastHistory->exit_type_id); 
                    $guest['history_exit_type_id'] = $exitType['id'];
                    $guest['history_exit_type_name'] = $exitType['name'];

                    $guest['history_exit_type_modello_decreto'] = $exitType['modello_decreto'];
                    $guest['history_exit_type_modello_notifica'] = $exitType['modello_notifica'];

                    $guest['history_exit_type_required_request'] = $exitType['required_request'];

                    $sig = TableRegistry::get('Surveys.SurveysInterviewsGuests');

                    $decreti = $sig->find()
                        ->select($sig)
                        ->select(['id_survey' => 'SurveysInterviews.id_survey'])
                        ->leftJoinWith('SurveysInterviews')
                        ->where(['SurveysInterviewsGuests.guest_id' => $guest->id, 'SurveysInterviews.id_survey' => $exitType['modello_decreto']])
                        ->first();

                    $notifiche = $sig->find()
                        ->select($sig)
                        ->select(['id_survey' => 'SurveysInterviews.id_survey'])
                        ->leftJoinWith('SurveysInterviews')
                        ->where(['SurveysInterviewsGuests.guest_id' => $guest->id, 'SurveysInterviews.id_survey' => $exitType['modello_notifica']])
                        ->first();
                    $guest['decreti'] = empty($decreti) ? false : $decreti;
                    $guest['notifiche'] = empty($notifiche) ? false : $notifiche;

                    // Decreti e notifiche 
                } else {
                    $guest['history_exit_type_id'] = '';
                    $guest['history_exit_type_name'] = '';
                }
                if ($lastHistory->destination_id) {
                    $sede = TableRegistry::get('Aziende.Sedi')->get($lastHistory->destination_id, ['contain' => ['Comuni', 'Aziende']]);
                    $guest['history_destination'] = $sede['azienda']['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['comune']['s_prv'].') ['.$sede['code_centro'].']';
                    $guest['history_destination_id'] = $lastHistory->destination_id;
                } else {
                    $guest['history_destination'] = '';
                    $guest['history_destination_id'] = '';
                }
                if ($lastHistory->provenance_id) {
                    $sede = TableRegistry::get('Aziende.Sedi')->get($lastHistory->provenance_id, ['contain' => ['Comuni', 'Aziende']]);
                    $guest['history_provenance'] = $sede['azienda']['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['comune']['s_prv'].') ['.$sede['code_centro'].']';
                } else {
                    $guest['history_provenance'] = '';
                }
                if ($guest->check_out_date) {
                    $guest['check_out_date'] = $guest->check_out_date->format('d/m/Y');
                } else {
                    $guest['check_out_date'] = '';
                }
                $guest['history_date'] = $lastHistory->operation_date->format('d/m/Y');
                $guest['history_file'] = $lastHistory->file;
                $guest['history_note'] = $lastHistory->note;
                $guest['history_cloned_guest'] = $lastHistory->cloned_guest_id;
            }
            //Presenza oggi
            $guest['presenza'] = TableRegistry::get('Aziende.Presenze')->getGuestPresenzaByDate($guest->id, date('Y-m-d'));

            //recupero ospiti della stessa famiglia
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();

            $guest['family_id'] = '';
            $guest['family'] = [];

            if($guestHasFamily){
                $familyId = $guestHasFamily['family_id'];
                $guest['family_id'] = $familyId;
                $guest['family'] = $guestsFamilies->getGuestsByFamily($familyId, $guest->sede_id, $guest->id);
            }

            //Presenza di una declinazione futura dell'ospite
            $guest['exists_in_future'] = $guests->checkIfExistsFutureGuest($guest);

			$this->_result['response'] = "OK";
			$this->_result['data'] = $guest;
			$this->_result['msg'] = 'Ospite recuperato correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero dell\'ospite.';
		}		
    }

    public function searchCountry($search) 
    {
        $countries = TableRegistry::get('Luoghi')->find()
			->select(['id' => 'Luoghi.c_luo', 'label' => 'Luoghi.des_luo'])
			->where([
				'Luoghi.in_luo' => 1,
				'Luoghi.des_luo LIKE' => '%'.$search.'%',
			])
			->order('Luoghi.des_luo ASC')
			->toArray();

		if($countries){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $countries;
			$this->_result['msg'] = 'Nazioni recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna nazione trovata.';
		}		
	}

    public function searchGuest($search, $guestId = '') 
    {
        $where = [
            'OR' => [
                'CONCAT(cui, " - ", name, " ", surname) LIKE' => '%'.$search.'%',
                'CONCAT(cui, " ", name, " ", surname) LIKE' => '%'.$search.'%',
                'CONCAT(cui, " ", surname, " ", name) LIKE' => '%'.$search.'%',
                'CONCAT(name, " ", cui, " ", surname) LIKE' => '%'.$search.'%',
                'CONCAT(name, " ", surname, " ", cui) LIKE' => '%'.$search.'%',
                'CONCAT(surname, " ", cui, " ", name) LIKE' => '%'.$search.'%',
                'CONCAT(surname, " ", name, " ", cui) LIKE' => '%'.$search.'%'
            ]
        ];
        if (!empty($guestId)) {
            $where['id !='] = $guestId;
        }
        $guests = TableRegistry::get('Aziende.Guests')->find()
			->select(['id', 'label' => 'CONCAT(cui, " - ", name, " ", surname)'])
			->where($where)
			->order('label ASC')
			->toArray();

		if($guests){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $guests;
			$this->_result['msg'] = 'Ospiti recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ospite trovato.';
		}
	}

    public function removeGuestFromFamily()
    {
        $id = $this->request->data['id'];

        if($id){
            $guest = TableRegistry::get('Aziende.Guests')->get($id);
            if (!$guest['minor']) {
                $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
                $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $id])->first();
                if($guestHasFamily){
                    $countFamilyAdults = $guestsFamilies->countFamilyAdults($guestHasFamily['family_id'], $guest['sede_id']);
                    if ($countFamilyAdults > 1) {
                        if($guestsFamilies->removeGuestFromFamily($guestHasFamily, $guest['sede_id'])){
                            $this->_result['response'] = "OK";
                            $this->_result['msg'] = "Rimozione dell'ospite dalla famiglia avvenuta con successo";
                        }else{
                            $this->_result['response'] = "KO";
                            $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia.";
                        }
                    }else{
                        $this->_result['response'] = "KO";
                        $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia: l'ospite è l'unico adulto.";
                    }
                } else {
                    $this->_result['response'] = "KO";
                    $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia: l'ospite non è associato a nessuna famiglia.";
                }
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia: l'ospite è un minore.";
            }
        }else{
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia: id mancante.";
        }
    }

    public function searchGuestsBySede($sedeId, $search, $guestId = "") 
    {
        $user = $this->request->session()->read('Auth.User');
        $sede = TableRegistry::get('Aziende.Sedi')->get($sedeId);

        if(!$this->Azienda->verifyUser($user, $sede['id_azienda'])){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $guestHasFamily = false;
        if(!empty($guestId)){
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $guestId])->first();
        }

        $guests = TableRegistry::get('Aziende.Guests')->searchGuestsForFamily($sedeId, $search, $guestId, $guestHasFamily);

		if($guests){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $guests;
			$this->_result['msg'] = 'Ospiti recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ospite trovato.';
		}		
    }

    public function getGuestsNotificationsCount($enteType)
    {
        $user = $this->request->session()->read('Auth.User');

        $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
        $notificationsCount = $guestsNotifications->countGuestsNotifications($enteType);

        $this->_result['response'] = "OK";
        $this->_result['data'] = $notificationsCount;
        $this->_result['msg'] = 'Notifiche recuperate con sucesso.';
    }

    public function getGuestsNotifications($enteType)
    {
        $user = $this->request->session()->read('Auth.User');

        $pass['query'] = $this->request->query;

        if(isset($pass['query']['filter'][5])){
			if($pass['query']['filter'][5] == 'No'){
				$pass['query']['filter'][5] = 0;
			}elseif($pass['query']['filter'][5] == 'Sì'){
				$pass['query']['filter'][5] = 1;
			}
		}

        $res = $this->Guest->getGuestsNotifications($enteType, $pass);

        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $notification) {

                if ($notification['done']) {
                    $checkDone = '<td class="text-center"><input type="checkbox" checked class="inline-check-done" data-id="'.$notification['id'].'" data-field="done"></td>';
                } else {
                    $checkDone = '<td class="text-center"><input type="checkbox" class="inline-check-done" data-id="'.$notification['id'].'" data-field="done"></td>';
                }

				$out['rows'][] = [
                    '<a href="'.Router::url('/aziende/home/info/'.$notification['a']['id']).'">'.$notification['a']['denominazione'].'</a>',
                    '<a href="'.Router::url('/aziende/sedi/index/'.$notification['a']['id']).'">'.$notification['s']['indirizzo'].' '.$notification['s']['num_civico'].' - '.$notification['l']['des_luo'].'</a>',
                    '<a href="'.Router::url('/aziende/guests/guest?sede='.$notification['s']['id'].'&guest='.$notification['g']['id']).'">'.$notification['g']['name'].' '.$notification['g']['surname'].'</a>',
                    $notification['u']['nome'].' '.$notification['u']['cognome'],
                    $notification['t']['msg_singular'],
                    $checkDone,
                    //$notification['done'] ? $notification['u2']['nome'].' '.$notification['u2']['cognome'] : '',
                    //$notification['done'] ? (empty($notification['done_date']) ? '' : $notification['done_date']->format('d/m/Y')) : '',
				];

            }

        }

        $this->_result = $out;
    }

    public function saveGuestNotificationDone()
    {
        $data = $this->request->data;

        if (!empty($data['id'])) {
            $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
            $entity = $guestsNotifications->get($data['id']);

            $dataToSave = ['done' => $data['value']];
            if ($data['value']) {
                $dataToSave['user_done_id'] = $this->request->session()->read('Auth.User.id');
                $dataToSave['done_date'] = date('Y-m-d');
            } else {
                $dataToSave['user_done_id'] = NULL;
                $dataToSave['done_date'] = NULL;
            }

            $guestsNotifications->patchEntity($entity, $dataToSave);

            if ($guestsNotifications->save($entity)) {
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'Valore salvato correttamente.';
            } else { 
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Errore nel salvataggio del valore.';
            }
        } else { 
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nel salvataggio del valore: dati mancanti.';
        }
    }

    public function saveAllGuestsNotificationsDone($enteType = '')
    {
        if (!empty($enteType)) {
            $pass['query'] = $this->request->query;

            $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
            $notifications = $this->Guest->getGuestsNotificationsForBulkMarking($enteType, $pass);

            if (!empty($notifications)) {
                $dataToSave = [
                    'done' => 1,
                    'user_done_id' => $this->request->session()->read('Auth.User.id'),
                    'done_date' => date('Y-m-d')
                ];

                $error = false;

                foreach ($notifications as $notification) {
                    $guestsNotifications->patchEntity($notification, $dataToSave);

                    if (!$guestsNotifications->save($notification)) {
                        $error = true;
                    }
                }

                if (!$error) {
                    $this->_result['response'] = 'OK';
                    $this->_result['msg'] = 'Notifiche salvate correttamente.';
                } else { 
                    $this->_result['response'] = 'KO';
                    $this->_result['msg'] = 'Errore nel salvataggio di una o più notifiche.';
                }
            } else {
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Nessuna notifica trovata.';
            }
        } else { 
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nel salvataggio delle notifiche: tipologia ente mancante.';
        }
    }

    public function getAgreements($aziendaId)
    {
        $user = $this->request->session()->read('Auth.User');

        if(!$this->Azienda->verifyUser($user, $aziendaId)){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $pass['query'] = $this->request->query;

        $res = $this->Agreement->getAgreements($aziendaId, $pass);

        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $agreement) {  

                $buttons = "";
				$buttons .= '<div class="button-group">';
                $buttons .= '<a href="#" class="btn btn-xs btn-warning edit-agreement" data-id="'.$agreement['id'].'" data-denominazione="' . $agreement['Aziende']['denominazione'] . '" 
                data-toggle="tooltip" title="Modifica convenzione"><i class="fa fa-pencil"></i></a>'; 
				$buttons .= '</div>';

				$out['rows'][] = [
                    $agreement['spa']['name'],
                    empty($agreement['date_agreement']) ? '' : $agreement['date_agreement']->format('d/m/Y'),
                    empty($agreement['date_agreement_expiration']) ? '' : $agreement['date_agreement_expiration']->format('d/m/Y'),
                    empty($agreement['date_extension_expiration']) ? '' : $agreement['date_extension_expiration']->format('d/m/Y'),
                    number_format($agreement['guest_daily_price'], 2, ',', ''),
                    $agreement['capacity_increment'],
					$buttons
				];

            }

        }

        $this->_result = $out;
    }

    public function saveAgreement()
    {
        $data = $this->request->data;

        $agreements = TableRegistry::get('Aziende.Agreements');

		if(empty($data['id'])){
            $entity = $agreements->newEntity(['associated' => ['AgreementsCompanies']]);
		}else{
			$entity = $agreements->get($data['id'], ['contain' => ['AgreementsCompanies']]);
            $oldData = $entity->toArray();
        } 

        if (!empty($data['date_agreement'])) {
            $data['date_agreement'] = implode('-', array_reverse(explode('/', $data['date_agreement'])));
        }
        if (!empty($data['date_agreement_expiration'])) {
            $data['date_agreement_expiration'] = implode('-', array_reverse(explode('/', $data['date_agreement_expiration'])));
        }
        if (!empty($data['date_extension_expiration'])) {
            $data['date_extension_expiration'] = empty($data['date_extension_expiration']) || $data['date_extension_expiration'] == 'null' ? '' : implode('-', array_reverse(explode('/', $data['date_extension_expiration'])));
        }
        if (!empty($data['guest_daily_price'])) {
            $data['guest_daily_price'] = str_replace(',', '.', $data['guest_daily_price']);
        }

        if(!isset($data['companies'])) {
            $data['companies'] = [];
        }

        $entity = $agreements->patchEntity($entity, $data, ['associated' => ['AgreementsCompanies']]);

		if($agreements->save($entity, ['associated' => ['AgreementsCompanies']])){
            $rendiconto = $this->Agreement->checkRendiconti($entity->id);

            // Relazione convenzione - sede
            $agreementsSedi = TableRegistry::get('Aziende.AgreementsToSedi');
            $sedi = TableRegistry::get('Aziende.Sedi')->find()->where(['id_azienda' => $entity->azienda_id])->toArray();

            $agreementsSedi->deleteAll(['agreement_id' => $entity->id]);

            if (!empty($data['sedi'])) {
                foreach ($data['sedi'] as $sedeId => $sede) {
                    $active = isset($sede['active']);

                    if ($active) {
                        // Imposto non attive le relazioni della sede con altre convenzioni
                        $agreementsSedi->updateAll(
                            ['active' => false],
                            ['agreement_id !=' => $entity->id, 'sede_id' => $sedeId]
                        );
                        $company_id = isset($sede['agreement_company_id']) && !empty($sede['agreement_company_id']) ? $sede['agreement_company_id'] : $rendiconto[0]->id;
                    } else {
                        $company_id = 0;
                    }

                    // Salvo i dati della relazione della sede con la convenzione
                    $agreementSede = $agreementsSedi->newEntity();
                    $dataToSave = [
                        'agreement_id' => $entity->id,
                        'sede_id' => $sedeId,
                        'active' => $active,
                        'capacity' => empty($sede['capacity']) ? 0 : $sede['capacity'],
                        'capacity_increment' => empty($sede['capacity_increment']) ? 0 : $sede['capacity_increment'],
                        'agreement_company_id' => $company_id
                    ];
                    $agreementsSedi->patchEntity($agreementSede, $dataToSave);
                    if ($agreementsSedi->save($agreementSede)) {
                        if ($active) {
                            foreach ($sedi as $key => $sede) {
                                if ($sede->id == $sedeId) {
                                    unset($sedi[$key]);
                                }
                            }
                        }
                    }
                }
            }

            // Notifiche mancanza convenzione per struttura
            $missing = false;
            if (!empty($sedi)) {
                $notifications = TableRegistry::get('Aziende.GuestsNotifications');
                $notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => 'MISSING_AGREEMENT_CENTER'])->first();
                foreach ($sedi as $sede) {
                    //Se la struttura è operativa controllo se ha una convenzione attiva
                    if ($sede->operativita) {
                        $agreement = $agreementsSedi->find()->where(['sede_id' => $sede->id, 'active' => 1])->first();
                        if (empty($agreement)) {
                            $missing = true;
                            $sedeNotified = $notifications->find()->where(['type_id' => $notificationType->id, 'sede_id' => $sede->id, 'done' => 0])->first();
                            if (empty($sedeNotified)) {
                                $notification = $notifications->newEntity();
                                $notificationData = [
                                    'type_id' => $notificationType->id,
                                    'azienda_id' => $sede->id_azienda,
                                    'sede_id' => $sede->id,
                                    'guest_id' => 0,
                                    'user_maker_id' => $this->request->session()->read('Auth.User.id')
                                ];
                                $notifications->patchEntity($notification, $notificationData);
                                $notifications->save($notification);
                            } else {
                                $notificationData = [
                                    'user_maker_id' => $this->request->session()->read('Auth.User.id')
                                ];
                                $notifications->patchEntity($sedeNotified, $notificationData);
                                $notifications->save($sedeNotified);
                            }
                        }
                    }
                }
            }

            /*
            // Notifica nuovo processo di approvazione
            $role = $this->request->session()->read('Auth.User.role');
            if ($role == 'ente_ospiti' && $entity->approved && ($oldData != $entity->toArray())) {
                $notifications = TableRegistry::get('Aziende.GuestsNotifications');
                $notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => 'APPROVE_NEEDED_AGREEMENT'])->first();
                $notification = $notifications->newEntity();
                $notificationData = [
                    'type_id' => $notificationType->id,
                    'azienda_id' => $entity->azienda_id,
                    'sede_id' => 0,
                    'guest_id' => 0,
                    'user_maker_id' => $this->request->session()->read('Auth.User.id')
                ];
                $notifications->patchEntity($notification, $notificationData);
                $notifications->save($notification);
           }
           */

            $this->_result['response'] = "OK";
            $this->_result['data'] = $missing;
            $this->_result['msg'] = "Convenzione salvata con successo.";
        }else{
            $message = "Errore nel salvataggio dell'ospite."; 
            $fieldLabelsList = $agreements->getFieldLabelsList();
            foreach($entity->errors() as $field => $errors){ 
                foreach($errors as $rule => $msg){ 
                    $message .= "\n" . $fieldLabelsList[$field].': '.$msg;
                }
            }  
            $this->_result['response'] = "KO";
            $this->_result['msg'] = $message;
        }
    }

    public function deleteAgreement()
    {
        $data = $this->request->data;

		if(!empty($data['id'])){

            $activeSediCount = TableRegistry::get('Aziende.AgreementsToSedi')->countActiveSediForAgreement($data['id']);

            if ($activeSediCount == 0) {

                $agreements = TableRegistry::get('Aziende.Agreements');

                $agreement = $agreements->get($data['id']);

                if ($agreement['approved'] == 0) {

                    $statements = TableRegistry::get('Aziende.Statements')->getStatementsByAgreement($data['id']);
                    
                    if (empty($statements)) {

                        if ($agreements->softDelete($agreement)) {
                            $this->_result['response'] = "OK";
                            $this->_result['msg'] = "Convenzione cancellata con successo.";
                        } else {
                            $this->_result['response'] = "KO";
                            $this->_result['msg'] = "Errore nella cancellazione della convenzione.";
                        }

                    } else {
                        $this->_result['response'] = "KO";
                        $this->_result['msg'] = "Errore nella cancellazione della convenzione: la convenzione è usata nei rendiconti.";
                    }
                }else{ 
                    $this->_result['response'] = "KO";
                    $this->_result['msg'] = "Errore nella cancellazione della convenzione: la convenzione è in stato approvato pertanto non può essere cancellata.";
                }
            }else{ 
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nella cancellazione della convenzione: la convenzione ha delle sedi collegate.";
            }
        }else{ 
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nella cancellazione della convenzione: ID mancante.";
        }
    }

    public function getAgreement($id)
	{
        $agreement = TableRegistry::get('Aziende.Agreements')->get($id, ['contain' => ['AgreementsToSedi' => 'AgreementsCompanies', 'AgreementsCompanies', 'Aziende']]);

        $agreement['date_agreement'] = empty($agreement['date_agreement']) ? '' : $agreement['date_agreement']->format('d/m/Y');
        $agreement['date_agreement_expiration'] = empty($agreement['date_agreement_expiration']) ? '' : $agreement['date_agreement_expiration']->format('d/m/Y');
        $agreement['date_extension_expiration'] = empty($agreement['date_extension_expiration']) ? '' : $agreement['date_extension_expiration']->format('d/m/Y');

		if($agreement){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $agreement;
			$this->_result['msg'] = 'Convenzione recuperata correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero della convenzione.';
		}		
    }

    public function getGuestsForPresenze()
    {
        $data = $this->request->query;

        if (!empty($data['sede']) && !empty($data['date'])) {
            $res = $this->Guest->getGuestsDataForPresenze($data['sede'], $data['date']);

            $this->_result['response'] = "OK";
			$this->_result['data'] = $res;
			$this->_result['msg'] = 'Ospiti recuperati con successo.';
        } else {
            $this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero degli ospiti: dati mancanti.';
        }
    }

    public function saveGuestsPresenze()
    {
        $data = $this->request->data;
        $guests = json_decode($data['guests']);

        $user = $this->request->session()->read('Auth.User');

        if (
            $data['date'] < date('Y-m-d', strtotime('+1 day')) && //data non nel futuro
            (
                $user['role'] != 'ente_ospiti' || // ruolo admin
                $data['date'] == date('Y-m-d') || // data oggi
                $data['date'] == date('Y-m-d', strtotime('-1 day')) && date('H:i') < '12:01' // data ieri ma oggi prima delle 12:01
            )
        ) {
            if (!empty($guests)) {
                $presenze = TableRegistry::get('Aziende.Presenze');

                // Controllo coerenza dati
                $validData = true;

                foreach ($guests as $guest) {
                    if (!empty($guest->presenza_id)) {
                        $presenza = $presenze->get($guest->presenza_id);
                        if ($presenza->date->format('Y-m-d') !== $data['date']) {
                            $validData = false;
                            break;
                        }
                    }
                }

                if ($validData) {

                    // Salvataggio dati
                    $error = false;

                    foreach ($guests as $guest) {
                        if (!empty($guest->presenza_id)) {
                            $presenza = $presenze->get($guest->presenza_id);
                        } else {
                            $presenza = $presenze->newEntity();
                        }
                        $presenzaData = [
                            'guest_id' => $guest->guest_id,
                            'date' => $data['date'],
                            'sede_id' => $data['sede'],
                            'presente' => filter_var($guest->presente, FILTER_VALIDATE_BOOLEAN),
                            'note' => $guest->note
                        ];
                        $presenze->patchEntity($presenza, $presenzaData);
                        if (!$presenze->save($presenza)) {
                            $error = true;
                        }
                    }

                    if ($error) {
                        $this->_result['response'] = "KO";
                        $this->_result['msg'] = "Errore nel salvataggio delle presenze.";
                    }else{ 
                        $res = $this->Guest->getGuestsDataForPresenze($data['sede'], $data['date']);

                        $this->_result['response'] = "OK";
                        $this->_result['data'] = $res;
                        $this->_result['msg'] = "Presenze salvate con successo.";
                    }
                } else {
                    $this->_result['response'] = "KO";
                    $this->_result['msg'] = "[1932] Errore nel salvataggio delle presenze: dati non validi. Si prega di ricaricare la pagina e riprovare.";
                }
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nel salvataggio delle presenze: non ci sono ospiti da salvare.";
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nel salvataggio delle presenze: il salvataggio è disabilitato.";
        }
    }

    public function loadGuestHistory($guestId)
    {
        $guest = TableRegistry::get('Aziende.Guests')->get($guestId);

        $guestsHistory = TableRegistry::get('Aziende.GuestsHistories');

        $history = $guestsHistory->getHistoryGuest($guestId);

        foreach($history as $h){
            $h->operation_date = empty($h->operation_date) ? '' : $h->operation_date->format('d/m/Y');
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = $history;
        $this->_result['msg'] = "";
    }

    public function getExitTypes($aziendaTipo, $all = 0)
	{
		$table = TableRegistry::get('Aziende.GuestsExitTypes');

        $role = $this->request->session()->read('Auth.User.role');

        $where = ['ente_type' => $aziendaTipo];
        
        if ($role == 'ente') {
            $where['startable_by_ente'] = 1;
        }

        if (!$all) {
            $where['required_request'] = 0;
        }

        $exitTypes = $table->find()->where($where)->toArray();

        $res = [];
        if (!empty($exitTypes)) {
            foreach ($exitTypes as $type) {
                $res[$type['id']] = $type;
            }
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = $res;
        $this->_result['msg'] = 'Tipologie uscita recuperate con successo.';
    }

    public function getRequestExitTypes($aziendaTipo)
	{
		$table = TableRegistry::get('Aziende.GuestsExitTypes');
	
        $requestExitTypes = $table->find()->where(['ente_type' => $aziendaTipo, 'required_request' => 1])->toArray();

        $res = [];
        if (!empty($requestExitTypes)) {
            foreach ($requestExitTypes as $type) {
                $res[$type['id']] = $type;
            }
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = $res;
        $this->_result['msg'] = 'Tipologie richiesta uscita recuperate con successo.';
    }

    public function getTransferAziendaDefault($sedeId) 
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaBySede($sedeId);

		if($azienda){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $azienda;
			$this->_result['msg'] = 'Ente recuperato con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ente trovato.';
		}		
    }

    public function searchTransferAziende($search = "") 
    {
        $aziende = TableRegistry::get('Aziende.Aziende')->searchAziende($search);

		if($aziende){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $aziende;
			$this->_result['msg'] = 'Enti recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ente trovato.';
		}		
    }

    public function searchTransferSedi($sedeId, $aziendaId, $search = "") 
    {
        $sedi = TableRegistry::get('Aziende.Sedi')->searchSedi($aziendaId, $search, $sedeId);

		if($sedi){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $sedi;
			$this->_result['msg'] = 'Strutture recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna struttura trovata.';
		}		
    }

    public function getReadmissionAziendaDefault($sedeId) 
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaBySede($sedeId);

		if($azienda){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $azienda;
			$this->_result['msg'] = 'Ente recuperato con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ente trovato.';
		}		
    }

    public function getReadmissionSedeDefault($sedeId) 
    {
        $azienda = TableRegistry::get('Aziende.Sedi')->getSedeForSearch($sedeId);

		if($azienda){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $azienda;
			$this->_result['msg'] = 'Ente recuperato con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ente trovato.';
		}		
    }

    public function searchReadmissionAziende($search = "") 
    {
        $aziende = TableRegistry::get('Aziende.Aziende')->searchAziende($search);

		if($aziende){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $aziende;
			$this->_result['msg'] = 'Enti recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun ente trovato.';
		}		
    }

    public function searchReadmissionSedi($aziendaId, $search = "") 
    {
        $sedi = TableRegistry::get('Aziende.Sedi')->searchSedi($aziendaId, $search);

		if($sedi){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $sedi;
			$this->_result['msg'] = 'Strutture recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna struttura trovata.';
		}		
    }

    public function requestExitProcedure()
    {
        $data = $this->request->data;

        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $guestsToRequestExit = [$guest];

        //richiesta uscita famigliari
        if ($data['request_exit_family']) {
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
            $familyGuests = $guests->find()
                ->where(['gf.family_id' => $guestFamily->family_id, 'Guests.id !=' => $guest->id, 'Guests.status_id' => '1'])
                ->join([
                    [
                        'table' => 'guests_families',
                        'alias' => 'gf',
                        'type' => 'left',
                        'conditions' => 'gf.guest_id = Guests.id'
                    ]
                ])
                ->toArray();

            $guestsToRequestExit = array_merge($guestsToRequestExit, $familyGuests);
        }

        $errorMsg = '';
        $responseStatus = 'OK';

        //controllo sulla correttezza dei dati degli ospiti
        foreach ($guestsToRequestExit as $g) { 
            $g->modified = new Time();
            if (!$guests->save($g)) {
                if (empty($errorMsg)) {
                    $errorMsg = "Errore nella configurazione dell'ospite."; 
                }
                $errorMsg .= "\n\n".$g->name." ".$g->surname;
                $fieldLabelsList = $guests->getFieldLabelsList();
                foreach($g->errors() as $field => $errors){ 
                    foreach($errors as $rule => $msg){ 
                        $errorMsg .= "\n" . $fieldLabelsList[$field].': '.$msg;
                    }
                }
                $responseStatus = 'KO';
            }
        }

        if (empty($errorMsg)) {
            $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($data['exit_type_id']); 

            $today = new Time();

            //salvataggio file
            $filePath = '';
            $errorFile = false;
            if (!empty($data['file']) && !empty($data['file']['tmp_name'])) {
                $basePath = ROOT.DS.Configure::read('dbconfig.aziende.EXIT_FILES_PATH');
                $fileName = uniqid().'_'.$data['file']['name'];
                $path = date('Y').DS.date('m').DS;
                $filePath = $path.$fileName;

                if (!is_dir($basePath.$path) && !mkdir($basePath.$path, 0755, true)) {
                    $errorFile = true;
                } else if (!move_uploaded_file($data['file']['tmp_name'], $basePath.$filePath)) {
                    $errorFile = true;
                }
            }

            if (!$errorFile) {
                //richiesta uscita ospiti
                foreach ($guestsToRequestExit as $g) {
                    $error = $this->Guest->requestExitGuest($g, $data, $today, $filePath);
                    if ($error) {
                        $errorMsg .= $g->name." ".$g->surname.": ".$error."\n";
                        if ($g->id == $guest->id) {
                            $responseStatus = 'KO';
                        }
                        $res['family_exit_request_status'][$g->id] = 0;
                    } else {
                        $res['family_exit_request_status'][$g->id] = 1;
                    }
                }

                $res['history_exit_request_status'] = 1;
                $res['history_exit_type_id'] = $exitType['id'];
                $res['history_exit_type_name'] = $exitType['name'];
                $res['history_file'] = $filePath;
                $res['history_note'] = $data['note'];
                $res['modello_decreto'] = $exitType['modello_decreto'];
                $res['modello_notifica'] = $exitType['modello_notifica'];
            } else {
                $errorMsg = "Errore nel salvataggio del documento di uscita.";
                $responseStatus = 'KO';
            }

            if (!$errorMsg) {
                $this->_result['response'] = "OK";
                $this->_result['data'] = $res;
                $this->_result['msg'] = 'Procedura di richiesta uscita dell\'ospite avviata con successo.';
            } else {
                $this->_result['response'] = $responseStatus;
                $this->_result['data'] = $res;
                $this->_result['msg'] =  $errorMsg;
            }
        } else {
            $this->_result['response'] = $responseStatus;
            $this->_result['msg'] =  $errorMsg;
        }
    }

    public function authorizeRequestExitProcedure()
    {
        $data = $this->request->data;

        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $guestsToAuthorizeRequestExit = [$guest];

        //autorizzazione richiesta uscita famigliari
        if ($data['authorize_request_exit_family']) {
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
            $familyGuests = $guests->find()
                ->where(['gf.family_id' => $guestFamily->family_id, 'Guests.id !=' => $guest->id, 'Guests.exit_request_status_id' => '1'])
                ->join([
                    [
                        'table' => 'guests_families',
                        'alias' => 'gf',
                        'type' => 'left',
                        'conditions' => 'gf.guest_id = Guests.id'
                    ]
                ])
                ->toArray();

            $guestsToAuthorizeRequestExit = array_merge($guestsToAuthorizeRequestExit, $familyGuests);
        }

        $errorMsg = '';
        $responseStatus = 'OK';

        //controllo sulla correttezza dei dati degli ospiti
        foreach ($guestsToAuthorizeRequestExit as $g) { 
            $g->modified = new Time();
            if (!$guests->save($g)) {
                if (empty($errorMsg)) {
                    $errorMsg = "Errore nella configurazione dell'ospite."; 
                }
                $errorMsg .= "\n\n".$g->name." ".$g->surname;
                $fieldLabelsList = $guests->getFieldLabelsList();
                foreach($g->errors() as $field => $errors){ 
                    foreach($errors as $rule => $msg){ 
                        $errorMsg .= "\n" . $fieldLabelsList[$field].': '.$msg;
                    }
                }
                $responseStatus = 'KO';
            }
        }

        if (empty($errorMsg)) {
            $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($data['exit_type_id']); 

            $today = new Time();

            //salvataggio file
            $filePath = '';
            $errorFile = false;
            if (!empty($data['file']) && !empty($data['file']['tmp_name'])) {
                $basePath = ROOT.DS.Configure::read('dbconfig.aziende.EXIT_FILES_PATH');
                $fileName = uniqid().'_'.$data['file']['name'];
                $path = date('Y').DS.date('m').DS;
                $filePath = $path.$fileName;

                if (!is_dir($basePath.$path) && !mkdir($basePath.$path, 0755, true)) {
                    $errorFile = true;
                } else if (!move_uploaded_file($data['file']['tmp_name'], $basePath.$filePath)) {
                    $errorFile = true;
                }
            }

            if (!$errorFile) {
                //autorizzazione richiesta uscita ospiti
                foreach ($guestsToAuthorizeRequestExit as $g) {
                    $error = $this->Guest->authorizeRequestExitGuest($g, $data, $today, $filePath);
                    if ($error) {
                        $errorMsg .= $g->name." ".$g->surname.": ".$error."\n";
                        if ($g->id == $guest->id) {
                            $responseStatus = 'KO';
                        }
                        $res['family_exit_request_status'][$g->id] = 1;
                    } else {
                        $res['family_exit_request_status'][$g->id] = 2;
                    }
                }

                $res['history_exit_request_status'] = 2;
                $res['history_exit_type_id'] = $exitType['id'];
                $res['history_exit_type_name'] = $exitType['name'];
                $res['history_file'] = $filePath;
                $res['history_note'] = $data['note'];
            } else {
                $errorMsg = "Errore nel salvataggio del documento di revoca.";
                $responseStatus = 'KO';
            }

            if (!$errorMsg) {
                $this->_result['response'] = "OK";
                $this->_result['data'] = $res;
                $this->_result['msg'] = 'Procedura di richiesta uscita dell\'ospite completata con successo.';
            } else {
                $this->_result['response'] = $responseStatus;
                $this->_result['data'] = $res;
                $this->_result['msg'] =  $errorMsg;
            }
        } else {
            $this->_result['response'] = $responseStatus;
            $this->_result['msg'] =  $errorMsg;
        }
    }

    public function exitProcedure()
    {
        $data = $this->request->data;

        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $guestsToExit = [$guest];

        //uscita famigliari
        if ($data['exit_family']) {
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
            $familyGuests = $guests->find()
                ->where(['gf.family_id' => $guestFamily->family_id, 'Guests.id !=' => $guest->id, 'Guests.status_id' => '1'])
                ->join([
                    [
                        'table' => 'guests_families',
                        'alias' => 'gf',
                        'type' => 'left',
                        'conditions' => 'gf.guest_id = Guests.id'
                    ]
                ])
                ->toArray();

            $guestsToExit = array_merge($guestsToExit, $familyGuests);
        }

        $errorMsg = '';
        $responseStatus = 'OK';

        //controllo sulla correttezza dei dati degli ospiti
        foreach ($guestsToExit as $g) { 
            $g->modified = new Time();
            if (!$guests->save($g)) {
                if (empty($errorMsg)) {
                    $errorMsg = "Errore nella configurazione dell'ospite."; 
                }
                $errorMsg .= "\n\n".$g->name." ".$g->surname;
                $fieldLabelsList = $guests->getFieldLabelsList();
                foreach($g->errors() as $field => $errors){ 
                    foreach($errors as $rule => $msg){ 
                        $errorMsg .= "\n" . $fieldLabelsList[$field].': '.$msg;
                    }
                }
                $responseStatus = 'KO';
            }
        }

        if (empty($errorMsg)) {
            $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($data['exit_type_id']); 

            //richiesta conferma uscita
            if ($exitType['required_confirmation']) {
                $status = 2;
            } else {
                $status = 3;
            }

            $today = new Time();

            //salvataggio file
            $filePath = '';
            $errorFile = false;
            if (!empty($data['file']) && !empty($data['file']['tmp_name'])) {
                $basePath = ROOT.DS.Configure::read('dbconfig.aziende.EXIT_FILES_PATH');
                $fileName = uniqid().'_'.$data['file']['name'];
                $path = date('Y').DS.date('m').DS;
                $filePath = $path.$fileName;

                if (!is_dir($basePath.$path) && !mkdir($basePath.$path, 0755, true)) {
                    $errorFile = true;
                } else if (!move_uploaded_file($data['file']['tmp_name'], $basePath.$filePath)) {
                    $errorFile = true;
                }
            }

            if (!$errorFile) {
                //uscita ospiti
                foreach ($guestsToExit as $g) {
                    $error = $this->Guest->exitGuest($g, $data, $today, $status, $filePath);
                    if ($error) {
                        $errorMsg .= $g->name." ".$g->surname.": ".$error."\n";
                        if ($g->id == $guest->id) {
                            $responseStatus = 'KO';
                        }
                        $res['family_status'][$g->id] = 1;
                    } else {
                        $res['family_status'][$g->id] = $status;
                    }
                }

                $res['history_status'] = $status;
                $res['history_exit_type'] = $exitType['name'];
                $res['check_out_date'] = $today->format('d/m/Y');
                $res['history_file'] = $filePath;
                $res['history_note'] = $data['note'];
                $res['modello_decreto'] = $exitType['modello_decreto'];
                $res['modello_notifica'] = $exitType['modello_notifica'];
                $res['required_request'] = $exitType['required_request'];
            } else {
                $errorMsg = "Errore nel salvataggio del documento di uscita.";
                $responseStatus = 'KO';
            }

            if (!$errorMsg) {
                $this->_result['response'] = "OK";
                $this->_result['data'] = $res;
                if ( $status == 2) {
                    $this->_result['msg'] = 'Procedura di uscita dell\'ospite avviata con successo.';
                } else {
                    $this->_result['msg'] = 'Procedura di uscita dell\'ospite completata con successo.';
                }
            } else {
                $this->_result['response'] = $responseStatus;
                $this->_result['data'] = $res;
                $this->_result['msg'] =  $errorMsg;
            }
        } else {
            $this->_result['response'] = $responseStatus;
            $this->_result['msg'] =  $errorMsg;
        }
    }

    public function confirmExit()
    {
        $data = $this->request->data;
    
        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $guestsToExit = [$guest];

        //uscita famigliari
        if ($data['confirm_exit_family']) {
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
            $familyGuests = $guests->find()
                ->where(['gf.family_id' => $guestFamily->family_id, 'Guests.id !=' => $guest->id, 'Guests.status_id' => '2'])
                ->join([
                    [
                        'table' => 'guests_families',
                        'alias' => 'gf',
                        'type' => 'left',
                        'conditions' => 'gf.guest_id = Guests.id'
                    ]
                ])
                ->toArray();

            $guestsToExit = array_merge($guestsToExit, $familyGuests);
        }

        $errorMsg = '';
        $responseStatus = 'OK';

        //controllo sulla correttezza dei dati degli ospiti
        foreach ($guestsToExit as $g) { 
            $g->modified = new Time();
            if (!$guests->save($g)) {
                if (empty($errorMsg)) {
                    $errorMsg = "Errore nella configurazione dell'ospite."; 
                }
                $errorMsg .= "\n\n".$g->name." ".$g->surname;
                $fieldLabelsList = $guests->getFieldLabelsList();
                foreach($g->errors() as $field => $errors){ 
                    foreach($errors as $rule => $msg){ 
                        $errorMsg .= "\n" . $fieldLabelsList[$field].': '.$msg;
                    }
                }
                $responseStatus = 'KO';
            }
        }

        if (empty($errorMsg)) {
            $lastHistory = TableRegistry::get('Aziende.GuestsHistories')->getLastGuestHistoryByStatus($guest->id, 2);
            $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($lastHistory->exit_type_id); 

            $today = new Time();

            //uscita ospiti
            foreach ($guestsToExit as $g) {
                $error = $this->Guest->confirmExitGuest($g, $data, $today);
                if ($error) {
                    $errorMsg .= $g->name." ".$g->surname.": ".$error."\n";
                    if ($g->id == $guest->id) {
                        $responseStatus = 'KO';
                    }
                    $res['family_status'][$g->id] = 2;
                } else {
                    $res['family_status'][$g->id] = 3;
                }
            }

            $res['history_status'] = 3;
            $res['history_exit_type'] = $exitType['name'];
            $res['check_out_date'] = $guest->check_out_date->format('d/m/Y');
            $res['history_file'] = $lastHistory->file;
            $res['history_note'] = $lastHistory->note;

            if (!$errorMsg) {
                $this->_result['response'] = "OK";
                $this->_result['data'] = $res;
                $this->_result['msg'] = 'Uscita dell\'ospite confermata con successo.';
            } else {
                $this->_result['response'] = $responseStatus;
                $this->_result['data'] = $res;
                $this->_result['msg'] =  $errorMsg;
            }
        } else {
            $this->_result['response'] = $responseStatus;
            $this->_result['msg'] =  $errorMsg;
        }
    }

    public function transferProcedure()
    {
        $data = $this->request->data;

        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $checkOutDate = new Time(substr($data['check_out_date'], 0, 33));

        if (
            (empty($guest['check_in_date']) || $checkOutDate->format('Y-m-d') >= $guest['check_in_date']->format('Y-m-d')) && 
            $checkOutDate->format('Y-m-d') <= date('Y-m-d')
        ) {
            $guestsToTransfer = [$guest];

            //trasferimento famigliari
            if ($data['transfer_family']) {
                $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
                $guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
                $familyGuests = $guests->find()
                    ->where(['gf.family_id' => $guestFamily->family_id, 'Guests.id !=' => $guest->id, 'Guests.status_id' => '1'])
                    ->join([
                        [
                            'table' => 'guests_families',
                            'alias' => 'gf',
                            'type' => 'left',
                            'conditions' => 'gf.guest_id = Guests.id'
                        ]
                    ])
                    ->toArray();

                $guestsToTransfer = array_merge($guestsToTransfer, $familyGuests);
            }

            $errorMsg = '';
            $responseStatus = 'OK';

            //controllo sulla correttezza dei dati degli ospiti
            foreach ($guestsToTransfer as $g) { 
                $g->modified = new Time();
                if (!$guests->save($g)) {
                    if (empty($errorMsg)) {
                        $errorMsg = "Errore nella configurazione dell'ospite."; 
                    }
                    $errorMsg .= "\n\n".$g->name." ".$g->surname;
                    $fieldLabelsList = $guests->getFieldLabelsList();
                    foreach($g->errors() as $field => $errors){ 
                        foreach($errors as $rule => $msg){ 
                            $errorMsg .= "\n" . $fieldLabelsList[$field].': '.$msg;
                        }
                    }
                    $responseStatus = 'KO';
                }
            }

            if (empty($errorMsg)) {
                $sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

                // se rimane nello stesso ente non serve conferma trasferimento
                if ($sede->id_azienda == $data['azienda']) {
                    $status = 6;
                    $statusCloned = 1;
                } else {
                    $status = 4;
                    $statusCloned = 5;
                }

                //trasferimento ospiti
                foreach ($guestsToTransfer as $g) {
                    $error = $this->Guest->transferGuest($g, $data, $checkOutDate);
                    if ($error) {
                        $errorMsg .= $g->name." ".$g->surname.": ".$error."\n";
                        if ($g->id == $guest->id) {
                            $responseStatus = 'KO';
                        }
                        $res['family_status'][$g->id] = 1;
                    } else {
                        $res['family_status'][$g->id] = $status;
                    }
                }

                $destination = TableRegistry::get('Aziende.Sedi')->get($data['sede'], ['contain' => ['Comuni', 'Aziende']]);

                $res['history_status'] = $status;
                $res['history_date'] = $checkOutDate;
                $res['history_destination'] = $destination['azienda']['denominazione'].' - '.$destination['indirizzo'].' '.$destination['num_civico'].', '.$destination['comune']['des_luo'].' ('.$destination['comune']['s_prv'].') ['.$destination['code_centro'].']';
                $res['history_destination_id'] = $data['sede'];
                $res['history_note'] = $data['note'];
                if ($status == 6) {
                    $lastHistory = TableRegistry::get('Aziende.GuestsHistories')->getLastGuestHistoryByStatus($guest->id, $status);
                    $res['history_cloned_guest'] = empty($lastHistory) ? '' : $lastHistory->cloned_guest_id;
                } else {
                    $res['history_cloned_guest'] = '';
                }

                if (!$errorMsg) {
                    $this->_result['response'] = "OK";
                    $this->_result['data'] = $res;
                    $this->_result['msg'] = "Trasferimento dell'ospite avviato con successo.";
                }  else {
                    $this->_result['response'] = $responseStatus;
                    $this->_result['data'] = $res;
                    $this->_result['msg'] = $errorMsg;
                }
            } else {
                $this->_result['response'] = $responseStatus;
                $this->_result['msg'] =  $errorMsg;
            }
        } else {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Errore: la data di check-out deve essere maggiore o uguale alla data di check-in e non può essere nel futuro.";
        }
    }

    public function acceptTransfer()
    {
        $data = $this->request->data;

        $checkInDate = new Time(substr($data['check_in_date'], 0, 33));

        if ($checkInDate->format('Y-m-d') <= date('Y-m-d')) {
            $guests = TableRegistry::get('Aziende.Guests');
            $guest = $guests->get($data['guest_id']);

            $guestsToTransfer = [$guest];

            //trasferimento famigliari
            if ($data['accept_transfer_family']) {
                $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
                $guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
                $familyGuests = $guests->find()
                    ->where(['gf.family_id' => $guestFamily->family_id, 'Guests.id !=' => $guest->id, 'Guests.status_id' => '5'])
                    ->join([
                        [
                            'table' => 'guests_families',
                            'alias' => 'gf',
                            'type' => 'left',
                            'conditions' => 'gf.guest_id = Guests.id'
                        ]
                    ])
                    ->toArray();

                $guestsToTransfer = array_merge($guestsToTransfer, $familyGuests);
            }

            $errorMsg = '';
            $responseStatus = 'OK';

            //controllo sulla correttezza dei dati degli ospiti
            foreach ($guestsToTransfer as $g) { 
                $g->modified = new Time();
                if (!$guests->save($g)) {
                    if (empty($errorMsg)) {
                        $errorMsg = "Errore nella configurazione dell'ospite."; 
                    }
                    $errorMsg .= "\n\n".$g->name." ".$g->surname;
                    $fieldLabelsList = $guests->getFieldLabelsList();
                    foreach($g->errors() as $field => $errors){ 
                        foreach($errors as $rule => $msg){ 
                            $errorMsg .= "\n" . $fieldLabelsList[$field].': '.$msg;
                        }
                    }
                    $responseStatus = 'KO';
                }
            }

                if (empty($errorMsg)) {

                $sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

                //accetta trasferimento ospiti
                foreach ($guestsToTransfer as $g) {
                    $error = $this->Guest->acceptTransferGuest($g, $data, $checkInDate);
                    if ($error) {
                        $errorMsg .= $g->name." ".$g->surname.": ".$error."\n";
                        if ($g->id == $guest->id) {
                            $responseStatus = 'KO';
                        }
                        $guest['family_status'][$g->id] = 5;
                    } else {
                        $guest['family_status'][$g->id] = 1;
                    }
                }

                $guest->status_id = 1;
                $guest->check_in_date = $checkInDate;

                if (!$errorMsg) {
                    $this->_result['response'] = "OK";
                    $this->_result['data'] = $guest;
                    $this->_result['msg'] = "Ingresso dell'ospite confermato con successo.";
                }  else {
                    $this->_result['response'] = $responseStatus;
                    $this->_result['data'] = $guest;
                    $this->_result['msg'] = $errorMsg;
                }
            } else {
                $this->_result['response'] = $responseStatus;
                $this->_result['msg'] =  $errorMsg;
            }
        } else {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Errore: la data di check-in non può essere nel futuro.";
        }
    }

    public function readmissionProcedure()
    {
        $data = $this->request->data;

        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $today = new Time();

        //trasferimento ospiti
        $errorMsg = '';
        $responseStatus = 'OK';
        $res = $this->Guest->readmissionGuest($guest, $data, $today);
        if ($res['error']) {
            $errorMsg .= $guest->name." ".$guest->surname.": ".$res['error']."\n";
            $responseStatus = 'KO';
        }

        if (!$errorMsg) {
            $this->_result['response'] = "OK";
            $this->_result['data'] = ['guest_id' => $res['id'], 'sede_id' => $data['sede']];
            $this->_result['msg'] = "Riammissione dell'ospite completata con successo.";
        }  else {
            $this->_result['response'] = $responseStatus;
            $this->_result['msg'] = $errorMsg;
        }
    }

    public function getEducationalQualifications($parentId = 0)
    {
        $qualifications = TableRegistry::get('Aziende.GuestsEducationalQualifications')->getByParent($parentId);

        $this->_result['response'] = "OK";
        $this->_result['data'] = $qualifications;
        $this->_result['msg'] = 'Titoli di studio recuperati con successo.';
    }

    public function autocompleteGuests()
    { 
        $search = empty($this->request->query['q']) ? '' : $this->request->query['q'];
        $guests = [];

        $where['CONCAT(g.cui, " - ", g.vestanet_id, " - ", g.name, " ", g.surname, " (", DATE_FORMAT(g.birthdate, "%d/%m/%Y"), ")") LIKE'] =  '%'.$search.'%';
        $where['s.deleted'] = 0;
        $where['a.deleted'] = 0;

        // Se ruolo ente, ricerca ospiti solo per quell'ente
        $user = $this->request->session()->read('Auth.User');
        if ($user['role'] == 'ente_ospiti' || $user['role'] == 'ente_contabile') {
            $contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
            $where['a.id'] = $contatto['id_azienda'];
        }

        $guestsTable = TableRegistry::get('Aziende.Guests');
        $guestsTable->alias('g');
        $res = $guestsTable->find()
            ->select([
                'g.id', 
                'text' => 'CONCAT(g.cui, " - ", g.vestanet_id, " - ", g.name, " ", g.surname, " (", DATE_FORMAT(g.birthdate, "%d/%m/%Y"), ")")', 'sede' => 'GROUP_CONCAT(g.sede_id SEPARATOR ",")', 
                'original_guest' => 'IF(g.original_guest_id IS NULL, g.id, g.original_guest_id)'
            ])
            ->where($where)
            ->order(['CONCAT(name, " ", surname)' => 'ASC'])
            ->join([
                [
                    'table' => 'sedi',
                    'alias' => 's',
                    'type' => 'LEFT',
                    'conditions' => 's.id = g.sede_id'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'LEFT',
                    'conditions' => 'a.id = s.id_azienda'
                ]
            ])
            ->group(['original_guest'])
            ->toArray();

        $guests = [];
        foreach($res as $g){
            $guests[] = [
                'id' => $g['sede'].'|'.$g['id'],
                'text' => $g['text']
            ];
        }

        $this->_result['response'] = 'OK';
        $this->_result['data'] = $guests;
        $this->_result['msg'] = "Elenco risultati.";
    }

    public function downloadGuestExitFile()
    {
        $filePath = $this->request->query['file'];

        if (!empty($filePath)) {
            $basePath = ROOT.DS.Configure::read('dbconfig.aziende.EXIT_FILES_PATH');

            $name = implode('_', array_slice(explode('_', $filePath), 1));

            if(file_exists($basePath.$filePath)){
                $this->response->file($basePath.$filePath , array(
                    'download'=> true,
                    'name'=> $name
                ));
                setcookie('downloadStarted', '1', false, '/');
                return $this->response;
            }else{
                setcookie('downloadStarted', '1', false, '/');
                $this->_result['msg'] = 'Il file richiesto non esiste.';
            }
        } else {
            setcookie('downloadStarted', '1', false, '/');
            $this->_result['msg'] = 'Parametro mancante.';
        }
    }

    public function getFiles($sedeId = null) {
        if(isset($sedeId)) {
            $data = $this->request->data['data'];
            $file = TableRegistry::get('Aziende.PresenzeUpload')->find('all')->where(['sede_id' => $sedeId, 'date' => $data, 'deleted' => false])->first();

            if ($file) {
                $file->fullPath = $file->full_path;
                $file->date = $file['date']->format('Y-m-d');
                $this->_result['response'] = 'OK';
                $this->_result['data'] = $file;
                $this->_result['msg'] = "Elenco risultati.";
            } else {
                $this->_result['response'] = 'KO';
                $this->_result['data'] = -1;
                $this->_result['msg'] = "Nessun file presente";
            }
        }
    }

    public function deleteFile($fileId) {

        $table = TableRegistry::get('Aziende.PresenzeUpload');
        $entity = $table->get($fileId);

        $ret = TableRegistry::get('Aziende.PresenzeUpload')->softDelete($entity);

        if ($ret) {
            $this->_result['response'] = 'OK';
            $this->_result['data'] = 1;
            $this->_result['msg'] = "File cancellato";
        } else {
            $this->_result['response'] = 'KO';
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Impossibile cancellare il file";
        }

    }

    public function saveFiles() {
        $table = TableRegistry::get('Aziende.PresenzeUpload');

        $basePath = Configure::read('dbconfig.aziende.SIGNATURE_UPLOAD_PATH');
        $path = date('Y').DS.date('m').DS.date('d');

        $fileData = json_decode($this->request->data('file'), true);

        $data = $this->request->data;

        if(!array_key_exists('attachment', $data)){
            //I dati inviati superano il post max size
            $post_max_size = (int)(ini_get('post_max_size'));
            ErrorCodes::logMessage(ErrorCodes::ERROR_POST_MAX_SIZE, [$post_max_size]);
            $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_POST_MAX_SIZE, [$post_max_size]);
            return;       
        }

        if(count($data['attachment']) == 1 && $data['attachment']['error'] === 4){
            //Non è stato caricato alcun file
            ErrorCodes::logMessage(ErrorCodes::ERROR_NO_FILE_UPLOADED, []);
            $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_NO_FILE_UPLOADED, []);    
            return;
        }


        if($data['attachment']['error'] === 1){
            //Il file supera la dimensione massima di upload max filesize
            $max_upload = (int)(ini_get('upload_max_filesize'));
            ErrorCodes::logMessage(ErrorCodes::ERROR_UPLOAD_MAX_FILESIZE, [$data['attachment']['name'], $max_upload]);
            $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_UPLOAD_MAX_FILESIZE, [$data['attachment']['name'], $max_upload]);
            return;    
        }
        if ($data['attachment']['type'] !== "application/pdf") {
            ErrorCodes::logMessage(ErrorCodes::ERROR_FILE_TYPE, [$data['attachment']['name'], $data['attachment']['type'], '.pdf']);
            $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_FILE_TYPE, [$data['attachment']['name'], $data['attachment']['type'], '.pdf']);
            return;   
        }

        $dir = ROOT.DS.$basePath.$path;

        if (!is_dir($dir)) {
            try {
                if (!mkdir($dir, 0755, true)) {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                ErrorCodes::logMessage(ErrorCodes::ERROR_FOLDER_CREATION, [$dir, $e->getMessage()]);
                $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_FOLDER_CREATION, [$dir, $e->getMessage()]);
                return;
            }
        }

        $fileName = uniqid().'_'.$data['attachment']['name'];

        try {
            if (!move_uploaded_file($data['attachment']['tmp_name'], $dir . DS . $fileName)) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            ErrorCodes::logMessage(ErrorCodes::ERROR_FILE_MOVE, [$data['attachment']['name'], $dir, $e->getMessage()]);
            $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_FILE_MOVE, [$data['attachment']['name'], $dir, $e->getMessage()]);
            return;
        }

        try {
            $entity = $table->newEntity($fileData);
            $entity->file = $data['attachment']['name'];
            $entity->filepath = $path.DS.$fileName;

            if (!$save = $table->save($entity)) {
                throw new \Exception(json_encode($entity->getErrors()));
            } else {
                $save->fullPath = $save->full_path;
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'File caricati con successo.';
                $this->_result['data'] = $save;
            }
        } catch(\Exception $e) {
            ErrorCodes::logMessage(ErrorCodes::ERROR_FILE_DB_SAVE, [$data['attachment'], $e->getMessage()]);
            $this->_result['msg'] = ErrorCodes::getViewMessage(ErrorCodes::ERROR_FILE_DB_SAVE, [$data['attachment'], $e->getMessage()]);
            return;
        }
    }

    public function downloadFile($id)
    {
        $table = TableRegistry::get('Aziende.PresenzeUpload');

        $fileData = $table->get($id);

        $file = new File(ROOT . DS . Configure::read('dbconfig.aziende.SIGNATURE_UPLOAD_PATH') . $fileData['filepath'], false);

        if($file->exists()){
            $this->response->file($file->path , array(
                'download'=> true,
                'name'=> $file->name
            ));
            setcookie('downloadStarted', '1', false, '/');
            return $this->response;
        }else{
            setcookie('downloadStarted', '1', false, '/');
            $this->_result['msg'] = 'Il file richiesto non esiste.';
        }
    }

    public function saveSingleCompany($id = null) {
        $table = TableRegistry::get('Aziende.AgreementsCompanies');
        
        if(isset($id)) {
            $entity = $table->get($id);

        } else {
            $entity = $table->newEntity();
        }
        $entity = $table->patchEntity($entity, $this->request->data);

        $ret = $table->save($entity);

        if ($ret) {
            $this->_result['response'] = 'OK';
            $this->_result['data'] = $ret;
            $this->_result['msg'] = "";
        } else {
            $this->_result['response'] = 'KO';
            $this->_result['data'] = -1;
            $this->_result['msg'] = "Impossibile salvare il rendiconto";
        }
    }

    public function checkRendiconti($id)
	{
        if(isset($id)) {
            $ret = $this->Agreement->checkRendiconti($id);
            if($ret) {
                $this->_result['response'] = "OK";
                $this->_result['data'] = $ret;
                $this->_result['msg'] = '';
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Impossibile salvare il rendiconto di default';
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Id nullo, impossibile recuperare i rendiconti';
        }
    }

    public function getStatementCompanies() {

        $pass['query'] = $this->request->query;

        $res = $this->StatementCompany->getStatements($pass);

        $out['total_rows'] = $res['tot'];

        if (!empty($res['res'])) {
            //echo "<pre>";
            //print_r($res['res']);
            //die();



            foreach ($res['res'] as $value) {
                ########### buttons START
                $button = '';

                $button .= '<div class="btn-group">';

                $button .= '<a class="btn btn-xs btn-default view-statement" href="' . Router::url(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'view', $value->statement->id, $value->id]) . '" >
                    <i data-toggle="tooltip" title="Visualizza" class="fa fa-eye"></i>
                    </a>';

                $button .= '<a class="btn btn-xs btn-default download-statement" href="#" data-statement="' . $value->statement->id . '">
                    <i data-toggle="tooltip" title="Scarica" class="fa fa-download"></i>
                    </a>';

                $button .= '</div>';
                ########### buttons END

                $date = '';



                $out['rows'][] = array(
                    $value->company->name,
                    isset($value->company->agreement) ? $value->company->agreement->cig : "",
                    $value->statement->period_label,
                    isset($value->status) ? $value->status->name : "",
                    $value->history['created'],
                    $button
                );
            }
                $this->_result = $out;
            
        }else{
            $this->_result = array();
        }
    }

    public function getPeriod($id)
	{
        if(isset($id)) {
            $ret = TableRegistry::get('Aziende.Periods')->get($id);

            if($ret) {
                $ret->start_date = $ret->start_date->format('Y-m-d');
                $ret->end_date = $ret->end_date->format('Y-m-d');
                $this->_result['response'] = "OK";
                $this->_result['data'] = $ret;
                $this->_result['msg'] = '';
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Impossibile salvare il rendiconto di default';
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Id nullo, impossibile recuperare i dati del periodo';
        }
    }

    public function checkCig($string) {
        if(isset($string)) {
            $where = [];

            $user = $this->Auth->user();

            if ($user['role'] == 'ente_ospiti' || $user['role'] == 'ente_contabile') {
                $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaByUser($user['id']);
                $where = ['azienda_id' => $azienda['id']];
            }
            
            $ret = TableRegistry::get('Aziende.Agreements')->find('all')
                ->where(['cig' => $string, 'deleted' => 0])
                ->where($where)
                ->contain(['AgreementsCompanies'])
                ->first();

            if($ret) {
                if (count($ret->companies)) {
                    $this->_result['response'] = "OK";
                    $this->_result['data'] = $ret;
                    $this->_result['msg'] = '';
                } else {
                    $this->_result['response'] = "KO";
                    $this->_result['msg'] = 'Il CIG inserito è valido, tuttavia non ci sono convenzioni associate.';
                }

            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Il CIG selezionato non è valido oppure non è abilitato per questo utente';
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Impossibile recuperare i dati relativi al CIG';
        }

    }

    public function saveStatement() {
        $table = TableRegistry::get('Aziende.Statements');
        $statement = $table->newEntity(['associated' => 'StatementCompany']);

        $data = $this->request->data;

        // Controlla se esiste già un rendiconto per lo stesso CIG e periodo

        $checkQuery = $table->find('all')->where(['agreement_id' => $data['agreement_id'], 'deleted'=>0 ]);

        if($data['period_id'] == 1) {
            $checkQuery->where(['period_start_date' => $data['period_start_date'], 'period_end_date' => $data['period_end_date']]);

        } else {
            $checkQuery->where(['period_id' => $data['period_id']]);
        }
        $check = $checkQuery->toArray();

        if ($check) {
            $this->_result['data'] = "";
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Attenzione, esiste già un rendiconto per questo periodo e CIG.';
        } else {
            $companies = TableRegistry::get('Aziende.AgreementsCompanies')->find('all')
            ->where(['agreement_id' => $data['agreement_id']])
            ->toArray();
    
            $data['companies'] = [];
    
            foreach ($companies as $company) {
                $data['companies'][] = ['company_id' => $company['id']];
    
            }
    
            $ret = $table->patchEntity($statement, $data, ['associated' => 'StatementCompany']);
    
            if ($table->save($statement, ['associated' => 'StatementCompany'])) {

                //Salvataggio stato nello storico
                foreach ($statement->companies as $statementCompany) {
                    $this->StatementCompany->saveStatusHistory($statementCompany->id, 1, '');
                }

                $this->_result['response'] = "OK";
                $this->_result['data'] = $ret;
                $this->_result['msg'] = '';
            } else {
                $this->_result['data'] = "";
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Impossibile salvare il rendiconto';
            }
        }
    }

    public function getCosts($all, $id) {
         if(isset($id)) {
            $toRet = $this->Costs->getCosts($all, $id);

            if($toRet) {
                $this->_result['response'] = "OK";
                $this->_result['data'] = $toRet;
                $this->_result['msg'] = '';
            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Impossibile recuperare le spese';
            }
            
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Id mancante, impossibile recuperare le spese';
        }

    }

    public function getStatementCompany($id) {
        if (isset($id)) {
            $table = TableRegistry::get('Aziende.StatementCompany');
            $company =  $table->get($id, [
                'contain' => [
                    'Status', 
                    'History' => ['Users', 'Status'],
                    'Statements' => ['Agreements']
                ]
            ]);

            if ($company) {
                $presenze = TableRegistry::get('Aziende.Presenze')->countPresenze($company->statement, $company->company_id);
                $default = TableRegistry::get('Aziende.AgreementsCompanies')->get($company->company_id, ['fields' => ['isDefault']]);

                $company['is_default'] = isset($default->isDefault) ? $default->isDefault : false;

                if(isset($company['billing_date'])) {
                    $company['billing_date'] = $company['billing_date']->format('Y-m-d');
                    $company['billing_net_amount'] = number_format($company['billing_net_amount'], 2, '.', '');
                    $company['billing_vat_amount'] = number_format($company['billing_vat_amount'], 2, '.', '');
                }
                $company['presenze'] = $presenze['presenze'];
                $company['minori'] = $presenze['minori'];
                $company['guest_daily_price'] = $presenze['guest_daily_price'];
                
                $this->_result['response'] = "OK";
                $this->_result['data'] = $company;
                $this->_result['msg'] = '';

            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Impossibile recuperare i dati della fatturazione';
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Id mancante, impossibile recuperare i dati della fatturazione';
        }
    }

    public function autocompleteCategories()
    { 
        $search = empty($this->request->query['q']) ? '' : $this->request->query['q'];

        $cats = TableRegistry::get('Aziende.CostsCategories')->find('all')->select(['id' => 'id', 'text'=>'name'])->where(['name LIKE' => '%'.$search.'%'])->toArray();
     
        $this->_result['response'] = 'OK';
        $this->_result['data'] = $cats;
        $this->_result['msg'] = "Elenco risultati.";
    }

    public function saveCost($id = null) {
        $this->request->allowMethod(['post']);

        $table =  TableRegistry::get('Aziende.Costs');
        
        $cost = $this->request->getData();

        $attachment = $this->request->getUploadedFile('file');

        if (isset($id)) {
            $entity = $table->get($id);
        } else {
            $entity = $table->newEntity();
        }

        $entity = $table->patchEntity($entity, $cost);

        if($table->save($entity)) {        
            
            // Controllo se è stato allegato un file
            if(strlen($attachment->getClientFilename())) {
                $uploadPath = ROOT.DS.Configure::read('dbconfig.aziende.COSTS_UPLOAD_PATH');
    
                $filePath = $entity->statement_company . DS . $entity->id;

                $dir = new Folder($uploadPath . $filePath, true, 0755);
                
                $fName = uniqid().'_'.$attachment->getClientFilename();

                try {
                    $attachment->moveTo($uploadPath . $filePath . DS . $fName);

                    $entity->attachment = $filePath . DS . $fName;
                    $entity->filename = $entity->id . '_' . $attachment->getClientFilename();
    
                    $table->save($entity);

                    $toRet = $this->Costs->getCosts(false, $entity->statement_company);

                    $this->_result['response'] = "OK";
                    $this->_result['data'] = $toRet;
                    $this->_result['msg'] = 'Spesa salvata correttamente';

                } catch(RuntimeException $e) {
                    $table->delete($entity);
                    $this->_result['response'] = "KO";
                    $this->_result['data'] = "";
                    $this->_result['msg'] = "Impossibile salvare la spesa, si è verificato un errore durante l'upload del file";
                }

            } else {
                $toRet = $this->Costs->getCosts(false, $entity->statement_company);

                $this->_result['response'] = "OK";
                $this->_result['data'] = $toRet;
                $this->_result['msg'] = 'Spesa salvata correttamente';
            }
        } else {
            $msg = "";
            $errors = $entity->errors();
            foreach ($errors as $key => $value) {
                $msg .= $key . ": "; 
                foreach ($value as $error) {
                    $msg .=  $error . ".\n";
                }
            }

            $this->_result['response'] = "KO";
            $this->_result['data'] = "";
            $this->_result['msg'] = "Impossibile salvare la spesa.\n" . $msg;
        }
    }

    public function deleteCost($id) {
        $table =  TableRegistry::get('Aziende.Costs');
        $cost = $this->request->getData();


        $entity = $table->get($id);
        $entity->deleted = 1;

        if($table->save($entity)) {
            $toRet = $this->Costs->getCosts(false, $entity->statement_company);

            $this->_result['response'] = "OK";
            $this->_result['data'] = $toRet;
            $this->_result['msg'] = 'Spesa salvata correttamente';

        } else {
            $this->_result['response'] = "KO";
            $this->_result['data'] = "";
            $this->_result['msg'] = 'Impossibile salvare la spesa';
        }
    }

    public function downloadFileStatements($what, $id)
    {
        $table = TableRegistry::get('Aziende.StatementCompany');

        $attachment = $table->get($id);
        
        if ($what == 'invoice' ) {
            $file = $attachment['uploaded_path'];
            $name = $attachment['filename'];

        } else if ($what == 'compliance') {
            $file = $attachment['compliance'];
            $name = $attachment['compliance_filename'];
            
        }
        

        $uploadPath = ROOT.DS.Configure::read('dbconfig.aziende.STATEMENTS_UPLOAD_PATH') . $file;

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

    public function downloadFileCosts($id)
    {
        $table = TableRegistry::get('Aziende.Costs');

        $attachment = $table->get($id);
        
        $file = $attachment['attachment'];

        $uploadPath = ROOT.DS.Configure::read('dbconfig.aziende.COSTS_UPLOAD_PATH') . $file;

        if(file_exists($uploadPath)){
            $this->response->file($uploadPath , array(
                'download'=> true,
                'name'=> $attachment['filename']
            ));
            setcookie('downloadStarted', '1', false, '/');
            return $this->response;
        }else{
            setcookie('downloadStarted', '1', false, '/');
            $this->_result['msg'] = 'Il file richiesto non esiste.';
        }
    }

    public function checkStatusStatementCompany($id) {
        $this->request->allowMethod('get');

        if ($this->request->is('ajax') && $this->request->is('json')) {

            try {
                $table = TableRegistry::get('Aziende.StatementCompany');
                $msg = "";
                
                if(isset($id)) {
                    $entity = $table->get($id, ['contain' => ['AgreementsCompanies']]);

                    if($entity->company->isDefault) {
                        $missingFile = false;
                        $missingCompliance = false;
                        if (empty($entity->uploaded_path)) {
                            $msg .= "Manca il file della fattura\n";
                            $missingFile = true;
                        }
                        if (empty($entity->compliance)) {
                            $msg .= "Manca il file della dichiarazione\n";
                            $missingCompliance = true;
                        }
            
                        if ($missingFile || $missingCompliance) {
                            $this->_result['response'] = 'KO';
                            $this->_result['data'] = -1;
                            $this->_result['msg'] = "Impossibile salvare il rendiconto\n" . $msg;
                        } else {
                            $this->_result['response'] = 'OK';
                            $this->_result['data'] = 1;
                            $this->_result['msg'] = "È possibile procedere.";
                        }

                    } else {
                        $this->_result['response'] = 'OK';
                        $this->_result['data'] = 1;
                        $this->_result['msg'] = "È possibile procedere.";
                    }
                }
            } catch (\Exception $e) {
                $this->_result['response'] = 'KO';
                $this->_result['data'] = -1;
                $this->_result['msg'] = $e->getMessage();
            }
        } else {
            throw new \Cake\Http\Exception\MethodNotAllowedException;
        }
    }

    /*
    Dato statement_company.id
    - Root contiene: 
        - Per ogni company una cartella che contiene
            - La testa (statement_company.uploaded_path)
            - Cartella Costi che contiene: 
                - Una cartella per ogni costs.category_id che contiene
                    - Tutti costs.attachment
    */
    public function downloadZipStatements($statement_id = null) {
        // path dei file di costo
        $costsFilesPath = ROOT.DS.Configure::read('dbconfig.aziende.COSTS_UPLOAD_PATH');
        // path della testa
        $statementsFilesPath = ROOT.DS.Configure::read('dbconfig.aziende.STATEMENTS_UPLOAD_PATH');
        // path firme
        $signaturesPath = ROOT.DS.Configure::read('dbconfig.aziende.SIGNATURE_UPLOAD_PATH');

        if (isset($statement_id)) {
            $statement = TableRegistry::get('Aziende.Statements')->get($statement_id, ['contain' => ['Agreements' => 'AgreementsToSedi', 'StatementCompany' => 'AgreementsCompanies']]);

            $folderPath = $statement->id;
            $archiveName = 'rendiconto_' . $statement->agreement->cig . '_' . $statement->period_label . '.zip';
            $archivePath = $statementsFilesPath.$folderPath.$archiveName;

            // Firme
            if (!empty($statement->agreement->agreements_to_sedi)) {
                $agrToSe = new Collection($statement->agreement->agreements_to_sedi);
                $agrToSe = $agrToSe->extract('sede_id')->toList();
    
                $firme = TableRegistry::get('Aziende.PresenzeUpload')->find('all')
                ->select(['id', 'sede_id', 'date', 'file', 'filepath', 'deleted'])
                ->select(['code_centro' => 'Sedi.code_centro'])
                ->leftJoinWith('Sedi')
                ->where(['PresenzeUpload.sede_id IN' => $agrToSe])
                ->where(['PresenzeUpload.date'])
                ->where(function (QueryExpression $exp, Query $q) use ($statement) {
                    return $exp->between('PresenzeUpload.date', $statement->period_start_date, $statement->period_end_date);
                })
                ->groupBy('code_centro')
                ->toArray();
            } else {
                $firme = [];
            }

            //Eliminazione vecchio archivio se presente
            if (file_exists($archivePath)) {
                unlink($archivePath);
            }

            // Conta la quantità totale di file da allegare
            $files = [];

            //Creazione archivio zip
            $archive = new \ZipArchive();

            if ($archive->open($archivePath, \ZIPARCHIVE::CREATE)) {
                
                if (!empty($statement->companies)) {
                    foreach ($statement->companies as $company) {
                        if (!empty($company->uploaded_path)) {
                            if (file_exists($statementsFilesPath . $company->uploaded_path)) {
                                $files[$statementsFilesPath . $company->uploaded_path][] = $company->company->name . DS . $company->filename;
                            } else {
                                $this->Flash->error('File fattura mancante per l\'ente ' . $company->company->name);
                                $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                                return $this->response;
                            }
    
                        }
                        if (!empty($company->compliance)) {
                            if (file_exists($statementsFilesPath . $company->compliance)) {
                                $files[$statementsFilesPath . $company->compliance][] = $company->company->name . DS . $company->compliance_filename;
                            } else {
                                $this->Flash->error('File dichiarazione mancante per l\'ente ' . $company->company->name);
                                $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                                return $this->response;
                            }
    
                        }
                        $costs = TableRegistry::get('Aziende.CostsCategories')->find('all')
                        ->where()
                        ->contain('Costs', function ($q) use ($company){
                            return $q->where(
                                ['Costs.statement_company' => $company->id, 'Costs.deleted' => 0]
                            );
                        })
                        ->toArray();
    
                        // Per ogni categoria di costo creo una cartella e metto i file
                        foreach ($costs as $category) {
                            foreach ($category['costs'] as $cost) {
                                if (!empty($cost->attachment)) {
                                    if (file_exists($costsFilesPath . $cost->attachment)) {   
                                        $files[$costsFilesPath . $cost->attachment][] = $company->company->name . DS . $category->name . DS . $cost->filename;
                                    } else {
                                        $this->Flash->error('Allegato mancante per la spesa ' . $cost->description . ' del ' . $cost->date );
                                        $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                                        return $this->response;
                                    }
                                }
                            }
                        }
    
                        if ($company->company->isDefault) {
                            $archive->addEmptyDir($company->company->name . DS . 'fogli_firme');
                            foreach ($firme as $key => $sede) {
                                foreach ($sede as $firma) {
                                    $d = new Date($firma->date);
                                    if (file_exists($signaturesPath . $firma->filepath)) {    
                                        $files[$signaturesPath . $firma->filepath][] = $company->company->name . DS . 'fogli_firme' . DS . $key . DS . $firma->date->format('Y-m-d') . '_' . $firma->file;
                                    } else {
                                        $this->Flash->error('Foglio firme mancante per la struttura  ' . $key . ' il ' . $firma->date );
                                        $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                                        return $this->response;

                                    }
                                    
                                }
    
                            }
                        }
                    }
                }
                if (count($files) > 0) {
                    foreach ($files as $key => $values) {
                        foreach ($values as $file) {
                            $archive->addFile($key, $file);
                        }
                        
                    }
                    $archive->close();
                    try {
                        $this->response->file($archivePath, array(
                            'download'=> true,
                            'name'=> $archiveName
                        ));
                        setcookie('downloadStarted', '1', false, '/');
                        return $this->response;
    
                    } catch (NotFoundException $e) {
                        setcookie('downloadStarted', '1', false, '/');
                        $this->Flash->error('Impossibile creare il file ZIP');
                        $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                        return $this->response;
                    }

                } else {
                    setcookie('downloadStarted', '1', false, '/');
                    $this->Flash->error('Impossibile creare il file ZIP. Non ci sono file da inserire.');
                    $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                    return $this->response;
                }

            } else {
                setcookie('downloadStarted', '1', false, '/');
                $this->Flash->error('Errore nello scaricamento dello ZIP: errore nella creazione dell\'archivio ZIP.');
                $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
                return $this->response;
            }

        } else {
            setcookie('downloadStarted', '1', false, '/');
            $this->Flash->error('Errore nello scaricamento dello ZIP: dati mancanti.');
            $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
            return $this->response;
        }
    }

    public function getStatementsNotifications() {
        $pass['query'] = $this->request->query;

        $res = $this->StatementsNotifications->getStatementsNotifications($pass);

        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){
            //echo "<pre>"; print_r($res['res']); die();

            foreach ($res['res'] as $key => $notification) {

                if ($notification['done']) {
                    $checkDone = '<td class="text-center"><input type="checkbox" checked class="inline-check-done" data-id="'.$notification['id'].'" data-field="done"></td>';
                } else {
                    $checkDone = '<td class="text-center"><input type="checkbox" class="inline-check-done" data-id="'.$notification['id'].'" data-field="done"></td>';
                }

                ########### buttons START
                $button= '<td class="text-center">';

                $button.= '<a class="btn btn-xs btn-default view-statement" href="'. Router::url(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'view', $notification->Statements['id'], $notification->StatementCompany['id']]) .'" >
                <i data-toggle="tooltip" title="Visualizza" class="fa fa-eye"></i>
                </a>';
                $button.= '</td>';
                ########### buttons END

				$out['rows'][] = [
                    $notification->AgreementsCompanies['name'],
                    $notification->Agreements['cig'],
                    $notification->Statements['period_label'],
                    $notification->Statements['period_start_date'],
                    $notification->Statements['period_end_date'],
                    'Invio rendiconto',
                    $checkDone,
                    $button
				];

            }

        }
        $this->_result = $out;
    }

    public function saveStatementsNotificationsDone()
    {
        $data = $this->request->data;

        if (!empty($data['id'])) {
            $statementsNotifications = TableRegistry::get('Aziende.StatementsNotifications');
            $entity = $statementsNotifications->get($data['id']);

            $entity->done = $data['value'];

            if ($data['value']) {
                $entity->user_done_id = $this->request->session()->read('Auth.User.id');
                $entity->done_date = date('Y-m-d');
            } else {
                $entity->user_done_id = NULL;
                $entity->done_date = NULL;
            }


            if ($statementsNotifications->save($entity)) {
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'Valore salvato correttamente.';
            } else { 
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Errore nel salvataggio del valore.';
            }
        } else { 
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nel salvataggio del valore: dati mancanti.';
        }
    }

        public function saveAllStatementsNotificationsDone() {

        $pass['query'] = $this->request->query;

        $table = TableRegistry::get('Aziende.StatementsNotifications');
        $notifications = $this->StatementsNotifications->getStatementsNotificationsForBulkMarking($pass);

        if (!empty($notifications)) {
            $dataToSave = [
                'done' => 1,
                'user_done_id' => $this->request->session()->read('Auth.User.id'),
                'done_date' => date('Y-m-d')
            ];

            $error = false;

            foreach ($notifications as $notification) {
                $table ->patchEntity($notification, $dataToSave);

                if (!$table ->save($notification)) {
                    $error = true;
                }
            }

            if (!$error) {
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'Notifiche salvate correttamente.';
            } else { 
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Errore nel salvataggio di una o più notifiche.';
            }
        } else {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Nessuna notifica trovata.';
        }
    }

    public function getCost($cost_id) {
        $this->request->allowMethod(['get']);

        $cost = TableRegistry::getTableLocator()->get('Aziende.Costs')->get($cost_id, ['contain' => ['CostsCategories']]);

        if ($cost) {
            $this->_result['response'] = 'OK';
            $this->_result['data'] = $cost;
            $this->_result['msg'] = '';
        } else { 
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Impossibile recuperare i dati relativi al costo';
        }

    }

    public function getPresenzeCount($id) {
        if (isset($id)) {
            $table = TableRegistry::get('Aziende.Statements');
            $stat =  $table->get($id, [
                'contain' => [
                    'Agreements'
                ]
            ]);

            if ($stat) {
                $presenze = TableRegistry::get('Aziende.Presenze')->countPresenze($stat);

                $ret['presenze'] = $presenze['presenze'];
                $ret['minori'] = $presenze['minori'];
                $ret['guest_daily_price'] = $presenze['guest_daily_price'];
                
                $this->_result['response'] = "OK";
                $this->_result['data'] = $ret;
                $this->_result['msg'] = '';

            } else {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = 'Impossibile recuperare i dati della fatturazione';
            }
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Id mancante, impossibile recuperare i dati della fatturazione';
        }
    }

    public function getStatementsByAgreementId($id = 0)
    {
        if (!empty($id)) {
            $res = [];
            $statements = TableRegistry::get('Aziende.Statements')->getStatementsByAgreement($id);
            if (!empty($statements)) {
                foreach ($statements as $s) {
                    $res[] = [
                        'label' => $s->period_label,
                        'start' => $s->period_start_date->format('d/m/Y'),
                        'end' => $s->period_end_date->format('d/m/Y')
                    ];
                }
            }
            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = "Controllo dei rendiconti avvenuto con successo.";
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nel controllo dei rendiconti: dati mancanti.";
        }
    }

    public function getGuestPresenzeAfterDate()
    {
        $data = $this->request->query;
        if (!empty($data['guest_id']) && !empty($data['date'])) {
            $res = [];

            $guestsTable = TableRegistry::get('Aziende.Guests');
            $presenzeTable = TableRegistry::get('Aziende.Presenze');

            $data['date'] = (new Time(substr($data['date'], 0, 33)))->format('Y-m-d');

            $guest = $guestsTable->get($data['guest_id']);
            $presenze = $presenzeTable->getGuestPresenzeByDate($data['guest_id'], $data['date']);

            if (!empty($presenze)) {
                $p = [];
                foreach ($presenze as $presenza) {
                    $p[] = $presenza->date->format('d/m/Y');
                }
                $res[] = [
                    'guest' => $guest->name . ' ' . $guest->surname,
                    'presenze' => $p
                ];
            }

            if ($data['family'] == 1) {
                //recupero ospiti della stessa famiglia
                $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
                $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
                if($guestHasFamily){
                    $familyId = $guestHasFamily['family_id'];
                    $family = $guestsFamilies->getGuestsByFamily($familyId, $guest->sede_id, $guest->id);
                }
                foreach ($family as $fg) {
                    $presenze = $presenzeTable->getGuestPresenzeByDate($fg->id, $data['date']);
                    if (!empty($presenze)) {
                        $p = [];
                        foreach ($presenze as $presenza) {
                            $p[] = $presenza->date->format('d/m/Y');
                        }
                        $res[] = [
                            'guest' => $fg->name . ' ' . $fg->surname,
                            'presenze' => $p
                        ];
                    }
                }
            }

            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = 'Controllo delle presenze avvenuto con successo.';
        } else {
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Errore nel controllo delle presenze: dati mancanti';
        }
    }

}
