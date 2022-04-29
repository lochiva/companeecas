<?php
namespace Aziende\Controller;

use Cake\Routing\Router;
use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\I18n\Time;

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
    }

    public function isAuthorized($user)
    {
        if($user['role'] == 'admin' || $user['role'] == 'ente'){
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

        $pass['query'] = $this->request->query;

        $res = $this->Azienda->getAziende($pass);
        //debug($res);die;
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $azienda) {

                $button = "";
                $button.= '<div class="btn-group">';
                $button.= '<a class="btn btn-xs btn-default view" data-toggle="tooltip" title="Visualizza" href="' . Router::url('/aziende/home/info/' . $azienda->id) . '" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" ><i class="fa fa-eye"></i></a>';
                $button.= '<a class="btn btn-xs btn-default edit" data-id="' . $azienda->id . '" data-denominazione="' . $azienda->denominazione . '" data-toggle="modal" data-target="#myModalAzienda"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
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
                $button.= '<li><a class="delete" data-id="'.$azienda->id.'" data-denominazione="'.$azienda->denominazione.'" href="#"><i style="margin-right: 10px; margin-left: 2px;" class="fa fa-trash"></i> Elimina</a></li>';
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

            $azienda = $this->Azienda->_get($id);

            if($this->request->session()->read('Auth.User.role') == 'companee_admin'){
                unset($azienda->contatti);
            }

            if($azienda->logo){
                $path = ROOT.DS.Configure::read('dbconfig.aziende.LOGO_PATH').$azienda->logo;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $dataImg = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
                $azienda->logo = $base64;
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

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

    public function getSedi($idAzienda = 0, $for = "table"){

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

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
                    $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $sede->id . '" data-toggle="modal" data-target="#myModalSede"><i data-toggle="tooltip" title="Modifica" href="#" class="fa fa-pencil"></i></a>';
                    $button.= '<a class="btn btn-xs btn-default guests" data-toggle="tooltip" title="Ospiti" href="' . Router::url('/aziende/guests/index/' . $sede->id) . '"><i class="fa fa-users"></i></a>';
                    $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
                    $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
                    $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
                    if ($azienda->id_tipo == 1) {
                        $button.= '<li><a class="presenze" href="' . Router::url('/aziende/sedi/presenze?sede=' . $sede->id) . '"><i style="margin-right: 5px;margin-left: -3px;" class="fa fa-calendar"></i> Presenze</a></li>';
                    }
                    $button.= '<li><a class="contatti" href="' . Router::url('/aziende/contatti/index/sede/' . $sede->id) . '" data-id="' . $sede->id . '" ><i style="margin-right: 5px;margin-left: -3px;" class="fa fa-address-book-o"></i> Contatti</a></li>';
                    $button.= '<li><a class="delete" href="#" data-id="' . $sede->id . '"><i style="margin-right: 7px; margin-left: -2px;" class="fa fa-trash"></i> Elimina</a></li>';
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
            $data['id_tipo_capitolato'] = 0;
            $data['id_tipologia_centro'] = 0;
            $data['id_tipologia_ospiti'] = 0;
            $data['n_posti_struttura'] = 0;
            $data['n_posti_effettivi'] = 0;
            $data['operativita'] = 1;
        }

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
                $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $contatto->id . '" data-toggle="modal" data-target="#myModalContatto"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
                $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
                $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
                $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
                $button.= '<li><a class="delete" href="#" data-id="' . $contatto->id . '"><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
                $button.= '</ul>';
                $button.= '</div>';
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
        $data = $this->request->data;

        if(empty($data['id'])){
            unset($data['id']);
        }

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

        if(isset($pass['query']['filter'][7])){
			if($pass['query']['filter'][7] == 'No'){
				$pass['query']['filter'][7] = 0;
			}elseif($pass['query']['filter'][7] == 'Sì'){
				$pass['query']['filter'][7] = 1;
			}
		}

        if(isset($pass['query']['filter'][9])){
			if($pass['query']['filter'][9] == 'No'){
				$pass['query']['filter'][9] = 0;
			}elseif($pass['query']['filter'][9] == 'Sì'){
				$pass['query']['filter'][9] = 1;
			}
		}

        $showOld = filter_var($pass['query']['showOld'], FILTER_VALIDATE_BOOLEAN);

        $res = $this->Guest->getGuests($sedeId, $azienda['id_tipo'], $showOld, $pass);

        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $guest) {  

                $buttons = "";
				$buttons .= '<div class="button-group">';
                $buttons .= '<a href="'.Router::url('/aziende/guests/guest?sede='.$sedeId.'&guest='.$guest['id']).'" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Modifica ospite"><i class="fa fa-pencil"></i></a>'; 
                $buttons .= '<a href="#" role="button" class="btn btn-xs btn-danger delete-guest" data-id="'.$guest['id'].'" data-toggle="tooltip" title="Elimina ospite"><i class="fa fa-trash"></i></a>'; 
				$buttons .= '</div>';

                //Icona di avvertenza se superata data dello stato bozza
                $alertDraftIcon = '';
                $today = date('Y-m-d');
                $draftExpiration = empty($guest['draft_expiration']) ? '' : $guest['draft_expiration']->format('Y-m-d');
                if ($guest['draft'] && $today > $draftExpiration) {
                    $alertDraftIcon = '<span class="alert-draft" data-toggle="tooltip" title="Inserire il CUI o l\'ID Vestanet"><i class="fa fa-exclamation-triangle"></i></span>';
                }

                //Stato ospite
                $status = '<span class="guest-status" style="background-color: '.$guest['gs']['color'].'">'.$guest['gs']['name'].'</span>';

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

        $data['name'] = trim($data['name']);
        $data['surname'] = trim($data['surname']);

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
            if ($data['ente_type'] == 1) {
                $data['draft'] = filter_var($data['draft'], FILTER_VALIDATE_BOOLEAN);
                $data['suspended'] = filter_var($data['suspended'], FILTER_VALIDATE_BOOLEAN);
                $data['draft_expiration'] = empty($data['draft_expiration']) || $data['draft_expiration'] == 'null' ? '' : new Time(substr($data['draft_expiration'], 0, 33));
            } elseif ($data['ente_type'] == 2) {
                unset($data['draft']);
                unset($data['suspended']);
                unset($data['draft_expiration']);
            }

            $guests->patchEntity($entity, $data);

            if($guests->save($entity)){
                // Salvataggio ospiti famiglia
                $data['family'] = json_decode($data['family']);

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
                        $guestsFamilies->delete($guestFamily);
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
            $this->_result['msg'] = "Errore nel salvataggio dell'ospite: data di check-in non valida. Esistono delle presenze segnate per l'ospite con data precedente alla data di check-in che si desidera salvare.";
        }
    }

    public function getSediForSearchGuest($guestId)
    {
        $guests = TableRegistry::get('Aziende.Guests');

        $guest = $guests->get($guestId);

        $res =  $guests->find()
            ->where(['cui' => $guest['cui']])
            ->contain(['Sedi.Aziende', 'GuestsStatuses'])  
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

        if($id){

            $guests = TableRegistry::get('Aziende.Guests');

            $guest = $guests->get($id);

            $guest->deleted = '1';

            if($guests->save($guest)){
                $this->_result['response'] = "OK";
                $this->_result['msg'] = "Eliminazione dell'ospite avvenuta con successo";
            }else{
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nell'eliminazione dell'ospite";
            }
        }else{
            $this->_result['response'] = "KO";
            $this->_result['msg'] = "Errore nell'eliminazione dell'ospite: id mancante.";
        }
    }

    public function getGuest($id)
	{
        $user = $this->request->session()->read('Auth.User');
        $guest = TableRegistry::get('Aziende.Guests')->get($id, ['contain' => ['FamilyGuests', 'Countries', 'EducationalQualifications']]);
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
            if ($guest->status_id != 1) {
                //Dati per messaggi di stato
                $lastHistory = TableRegistry::get('Aziende.GuestsHistories')->getLastGuestHistoryByStatus($guest->id, $guest->status_id);
                if ($lastHistory->exit_type_id) {
                    $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($lastHistory->exit_type_id); 
                    $guest['history_exit_type'] = $exitType['name'];
                } else {
                    $guest['history_exit_type'] = '';
                }
                if ($lastHistory->destination_id) {
                    $sede = TableRegistry::get('Aziende.Sedi')->get($lastHistory->destination_id, ['contain' => ['Comuni', 'Aziende']]);
                    $guest['history_destination'] = $sede['azienda']['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['comune']['s_prv'].')';
                } else {
                    $guest['history_destination'] = '';
                }
                if ($lastHistory->provenance_id) {
                    $sede = TableRegistry::get('Aziende.Sedi')->get($lastHistory->provenance_id, ['contain' => ['Comuni', 'Aziende']]);
                    $guest['history_provenance'] = $sede['azienda']['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['comune']['s_prv'].')';
                } else {
                    $guest['history_provenance'] = '';
                }
                if ($guest->check_out_date) {
                    $guest['check_out_date'] = $guest->check_out_date->format('d/m/Y');
                } else {
                    $guest['check_out_date'] = '';
                }
                $guest['history_note'] = $lastHistory->note;
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
                $guest['family'] = $guestsFamilies->getGuestsByFamily($familyId, $guest->sede_id, $guest->id, $guest->original_guest_id);
            }

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
            $guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            $guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $id])->first();

            if($guestHasFamily){
                if($guestsFamilies->delete($guestHasFamily)){
                    $this->_result['response'] = "OK";
                    $this->_result['msg'] = "Rimozione dell'ospite dalla famiglia avvenuta con successo";
                }else{
                    $this->_result['response'] = "KO";
                    $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia.";
                }
            }else{
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nella rimozione dell'ospite dalla famiglia: l'ospite non è associato a nessuna famiglia.";
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
        if($user['role'] != 'admin'){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
        $notificationsCount = $guestsNotifications->countGuestsNotifications($enteType);

        $this->_result['response'] = "OK";
        $this->_result['data'] = $notificationsCount;
        $this->_result['msg'] = 'Notifiche recuperate con sucesso.';
    }

    public function getGuestsNotifications($enteType)
    {
        $user = $this->request->session()->read('Auth.User');

        if($user['role'] != 'admin'){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

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
                $buttons .= '<a href="#" class="btn btn-xs btn-warning edit-agreement" data-id="'.$agreement['id'].'" data-toggle="tooltip" title="Modifica convenzione"><i class="fa fa-pencil"></i></a>'; 
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
            $entity = $agreements->newEntity();
		}else{
			$entity = $agreements->get($data['id']);
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

        $agreements->patchEntity($entity, $data);

		if($agreements->save($entity)){
            // Relazione convenzione - sede
            $agreementsSedi = TableRegistry::get('Aziende.AgreementsToSedi');
            $sedi = TableRegistry::get('Aziende.Sedi')->find()->where(['id_azienda' => $entity->azienda_id])->toArray();

            $agreementsSedi->deleteAll(['agreement_id' => $entity->id, 'active' => 1]);

            if (!empty($data['sedi'])) {
                foreach ($data['sedi'] as $sedeId => $sede) {
                    // Imposto non attive le relazioni della sede con altre convenzioni
                    $agreementsSedi->updateAll(
                        ['active' => false],
                        ['agreement_id !=' => $entity->id, 'sede_id' => $sedeId]
                    );

                    // Salvo i dati della relazione della sede con la convenzione
                    $agreementSede = $agreementsSedi->newEntity();
                    $dataToSave = [
                        'agreement_id' => $entity->id,
                        'sede_id' => $sedeId,
                        'active' => 1,
                        'capacity' => $sede['capacity']
                    ];
                    $agreementsSedi->patchEntity($agreementSede, $dataToSave);
                    if ($agreementsSedi->save($agreementSede)) {
                        foreach ($sedi as $key => $sede) {
                            if ($sede->id == $sedeId) {
                                unset($sedi[$key]);
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

    public function getAgreement($id)
	{
        $agreement = TableRegistry::get('Aziende.Agreements')->get($id, ['contain' => ['AgreementsToSedi']]);

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
            $guests = TableRegistry::get('Aziende.Guests')->getGuestsForPresenze($data['sede'], $data['date']);

            foreach ($guests as $index => $guest) {
                if ($guest['presente'] === null) {
                    $guest['not_saved'] = 1;
                } else {
                    $guest['not_saved'] = 0;
                }
                $guest['presente'] = filter_var($guest['presente'], FILTER_VALIDATE_BOOLEAN);

                //Colore riga in base alle presenze (se ospite in stato "in struttura" e non sospeso)
                $warningPresenze = 0;
                $dangerPresenze = 0;
                $lastPresenzaDate = '';
                $lastPresenza = TableRegistry::get('Aziende.Presenze')->getGuestLastPresenzaByDate($guest['id'], $data['date']);
                if (!empty($lastPresenza)) {
                    $lastPresenzaDate = $lastPresenza['date']->format('Y-m-d');
                } elseif (!empty($guest['check_in_date'])) {
                    $lastPresenzaDate = $guest['check_in_date']->format('Y-m-d');
                }
                if (!empty($lastPresenzaDate)) {
                    $threeDaysBefore = date('Y-m-d', strtotime($data['date'].' -3 days'));
                    if ($lastPresenzaDate < $data['date'] && $lastPresenzaDate >= $threeDaysBefore) {
                        $warningPresenze = 1;
                    } elseif ($lastPresenzaDate < $threeDaysBefore) {
                        $dangerPresenze = 1;
                    }
                }
                $guest['warning_presenze'] = $warningPresenze;
                $guest['danger_presenze'] = $dangerPresenze;

                $guest['check_in_date'] = $guest['check_in_date'] ? $guest['check_in_date']->format('d/m/Y') : '';
                $guest['birthdate'] = $guest['birthdate']->format('d/m/Y');
            }

            $presenzeTable = TableRegistry::get('Aziende.Presenze');
            //totale presenze ospiti per il giorno
            $presenzeForDay = $presenzeTable->getPresenzeSedeForDay($data['sede'], $data['date']);
            $countPresenzeDay = count($presenzeForDay);
            //totale presenze ospiti per il mese
            $month = substr($data['date'], 0, 7);
            $presenzeForMonth = $presenzeTable->getPresenzeSedeForMonth($data['sede'], $month);
            $countPresenzeMonth = count($presenzeForMonth);

            $this->_result['response'] = "OK";
			$this->_result['data'] = ['guests' => $guests, 'count_presenze_day' => $countPresenzeDay, 'count_presenze_month' => $countPresenzeMonth];
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

        if (!empty($guests)) {
            $presenze = TableRegistry::get('Aziende.Presenze');
            $error = false;
            foreach ($guests as $guest) {
                $presenza = $presenze->newEntity();
                $presenzaData = [
                    'guest_id' => $guest->id,
                    'date' => $data['date'],
                    'sede_id' => $data['sede'],
                    'presente' => filter_var($guest->presente, FILTER_VALIDATE_BOOLEAN),
                    'note' => $guest->note
                ];
                $presenze->patchEntity($presenza, $presenzaData);
                if ($presenze->save($presenza)) {
                    $guest->not_saved = 0;
                } else {
                    $error = true;
                }

                //Colore riga in base alle presenze (se ospite in stato "in struttura" e non sospeso)
                $warningPresenze = 0;
                $dangerPresenze = 0;
                $lastPresenzaDate = '';
                $lastPresenza = TableRegistry::get('Aziende.Presenze')->getGuestLastPresenzaByDate($guest->id, $data['date']);
                if (!empty($lastPresenza)) {
                    $lastPresenzaDate = $lastPresenza['date']->format('Y-m-d');
                } elseif (!empty($guest->check_in_date)) {
                    $lastPresenzaDate = substr($guest->check_in_date, 0, 10);
                }
                if (!empty($lastPresenzaDate)) {
                    $threeDaysBefore = date('Y-m-d', strtotime($data['date'].' -3 days'));
                    if ($lastPresenzaDate < $data['date'] && $lastPresenzaDate >= $threeDaysBefore) {
                        $warningPresenze = 1;
                    } elseif ($lastPresenzaDate < $threeDaysBefore) {
                        $dangerPresenze = 1;
                    }
                }
                $guest->warning_presenze = $warningPresenze;
                $guest->danger_presenze = $dangerPresenze;
            }

            if ($error) {
                $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nel salvataggio delle presenze.";
            }else{ 
                $presenzeTable = TableRegistry::get('Aziende.Presenze');
                //totale presenze ospiti per il giorno
                $presenzeForDay = $presenzeTable->getPresenzeSedeForDay($data['sede'], $data['date']);
                $countPresenzeDay = count($presenzeForDay);
                //totale presenze ospiti per il mese
                $month = substr($data['date'], 0, 7);
                $presenzeForMonth = $presenzeTable->getPresenzeSedeForMonth($data['sede'], $month);
                $countPresenzeMonth = count($presenzeForMonth);

                $this->_result['response'] = "OK";
                $this->_result['data'] = ['guests' => $guests, 'count_presenze_day' => $countPresenzeDay, 'count_presenze_month' => $countPresenzeMonth];
                $this->_result['msg'] = "Presenze salvate con successo.";
            }
        } else {
            $this->_result['response'] = "KO";
                $this->_result['msg'] = "Errore nel salvataggio delle presenze: non ci sono ospiti da slavare.";
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

    public function getExitTypes($aziendaTipo)
	{
		$table = TableRegistry::get('Aziende.GuestsExitTypes');

        $role = $this->request->session()->read('Auth.User.role');
        if ($role == 'admin') {
		    $exitTypes = $table->find()->where(['ente_type' => $aziendaTipo])->toArray();
        } elseif ($role == 'ente') {
            $exitTypes = $table->find()->where(['startable_by_ente' => 1, 'ente_type' => $aziendaTipo])->toArray();
        }

        $res = [];
        if (!empty($exitTypes)) {
            foreach ($exitTypes as $type) {
                $res[$type['id']] = $type;
            }
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = $res;
        $this->_result['msg'] = 'Tipologie uscite recuperate con successo.';
    }

    public function getTransferAziendaDefault($sedeId) 
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->getTransferAziendaDefault($sedeId);

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
        $aziende = TableRegistry::get('Aziende.Aziende')->searchTransferAziende($search);

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
        $sedi = TableRegistry::get('Aziende.Sedi')->searchTransferSedi($sedeId, $aziendaId, $search);

		if($sedi){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $sedi;
			$this->_result['msg'] = 'Strutture recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna struttura trovata.';
		}		
    }

    public function exitProcedure()
    {
        $data = $this->request->data;

        $data = $this->request->data;
    
        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($data['exit_type_id']); 

        //richiesta conferma uscita
        if ($exitType['required_confirmation']) {
            $status = 2;
        } else {
            $status = 3;
        }

        $today = new Time();

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

        //uscita ospiti
        $errorMsg = '';
        $responseStatus = 'OK';
        foreach ($guestsToExit as $g) {
            $error = $this->Guest->exitGuest($g, $data, $today, $status);
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
        $res['history_note'] = $data['note'];

        if (!$errorMsg) {
            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = 'Procedura di uscita dell\'ospite completata con successo.';
        } else {
            $this->_result['response'] = $responseStatus;
            $this->_result['data'] = $res;
            $this->_result['msg'] =  $errorMsg;
        }
    }

    public function confirmExit()
    {
        $data = $this->request->data;
    
        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);

        $lastHistory = TableRegistry::get('Aziende.GuestsHistories')->getLastGuestHistoryByStatus($guest->id, 2);
        $exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($lastHistory->exit_type_id); 

        $today = new Time();

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

        //uscita ospiti
        $errorMsg = '';
        $responseStatus = 'OK';
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
    }

    public function transferProcedure()
    {
        $data = $this->request->data;
    
        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);
        $sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

        // se rimane nello stesso ente non serve conferma trasferimento
        if ($sede->id_azienda == $data['azienda']) {
            $status = 6;
            $statusCloned = 1;
        } else {
            $status = 4;
            $statusCloned = 5;
        }

        $today = new Time();

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

        //trasferimento ospiti
        $errorMsg = '';
        $responseStatus = 'OK';
        foreach ($guestsToTransfer as $g) {
            $error = $this->Guest->transferGuest($g, $data, $today);
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
        $res['check_out_date'] = $today->format('d/m/Y');
        $res['history_destination'] = $destination['azienda']['denominazione'].' - '.$destination['indirizzo'].' '.$destination['num_civico'].', '.$destination['comune']['des_luo'].' ('.$destination['comune']['s_prv'].')';
        $res['history_note'] = $data['note'];

        if (!$errorMsg) {
            $this->_result['response'] = "OK";
            $this->_result['data'] = $res;
            $this->_result['msg'] = "Trasferimento dell'ospite avviato con successo.";
        }  else {
            $this->_result['response'] = $responseStatus;
            $this->_result['data'] = $res;
            $this->_result['msg'] = $errorMsg;
        }
    }

    public function acceptTransfer()
    {
        $data = $this->request->data;
    
        $guests = TableRegistry::get('Aziende.Guests');
        $guest = $guests->get($data['guest_id']);
        $sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

        $today = new Time();

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

        //accetta trasferimento ospiti
        $errorMsg = '';
        $responseStatus = 'OK';
        foreach ($guestsToTransfer as $g) {
            $error = $this->Guest->acceptTransferGuest($g, $data, $today);
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
		$guest->check_in_date = $today->format('Y-m-d');

        if (!$errorMsg) {
            $this->_result['response'] = "OK";
            $this->_result['data'] = $guest;
            $this->_result['msg'] = "Ingresso dell'ospite confermato con successo.";
        }  else {
            $this->_result['response'] = $responseStatus;
            $this->_result['data'] = $guest;
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

        $where['CONCAT(cui, " - ", vestanet_id, " - ", name, " ", surname) LIKE'] =  '%'.$search.'%';

        $guestsTable = TableRegistry::get('Aziende.Guests');
        $res = $guestsTable->find()
            ->select(['id', 'text' => 'CONCAT(cui, " - ", vestanet_id, " - ", name, " ", surname)', 'sede' => 'GROUP_CONCAT(sede_id SEPARATOR ",")'])
            ->where($where)
            ->order(['CONCAT(name, " ", surname)' => 'ASC'])
            ->group(['cui'])
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
}
