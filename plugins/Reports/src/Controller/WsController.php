<?php
namespace Reports\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\I18n\Time;

/**
 * Reports ws Controller
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Reports.Reports');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

		$this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");
	}
	
	public function isAuthorized($user = null)
    {
		if($user['role'] == 'admin'){
			return true;
		}

		if($user['role'] == 'centro'){
			$centroActions = ['getReports', 'loadReport', 'searchCountry', 'searchRegion', 'searchProvince', 'searchCity',
								'searchNode', 'saveVictim', 'saveWitness', 'getUserRole', 'checkAnagrafica', 'searchCitizenship',
								'saveDocument', 'getDocuments', 'downloadDocument', 'deleteDocument', 'closeReport', 'reopenReport',
								'transferReport', 'confirmTransferReport'];
			if (in_array($this->request->getParam('action'), $centroActions)) {
				return true;
			}
		}

		if($user['role'] == 'nodo'){
			$nodoActions = ['getReports', 'loadReport', 'searchCountry', 'searchRegion', 'searchProvince', 'searchCity',
							'saveVictim', 'saveWitness', 'getUserRole', 'checkAnagrafica', 'searchCitizenship',
							'saveDocument', 'getDocuments', 'downloadDocument', 'deleteDocument', 'closeReport', 'reopenReport',
							'transferReport'];
			if (in_array($this->request->getParam('action'), $nodoActions)) {
				return true;
			}
		}

        // Default deny
        return false;
    }

	public function beforeRender(Event $event) 
	{
        parent::beforeFilter($event);
        $this->set('result', json_encode($this->_result));
	}
	
	public function getReports()
	{
		$pass['query'] = $this->request->query;

		$user = $this->request->session()->read('Auth.User');

		if($user['role'] == 'admin' || $user['role'] == 'centro') {
			$statusIndex = 3;
		}else {
			$statusIndex = 2;
		}

		if(isset($pass['query']['filter'][$statusIndex])){
			if($pass['query']['filter'][$statusIndex] == 'Aperto'){
				$pass['query']['filter'][$statusIndex] = 'open';
			}elseif($pass['query']['filter'][$statusIndex] == 'Chiuso'){
				$pass['query']['filter'][$statusIndex] = 'closed';
			}elseif($pass['query']['filter'][$statusIndex] == 'Trasferimento'){
				$pass['query']['filter'][$statusIndex] = 'transfer';
			}elseif($pass['query']['filter'][$statusIndex] == 'Trasferito'){
				$pass['query']['filter'][$statusIndex] = 'transfer_accepted';
			}
		}

        $res = $this->Reports->getReports($pass, $user);
        
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $report) {

				switch($report->status){
					case 'open':
						$status = '<span class="status-open">Aperto</span>';
						break;
					case 'closed':
						$status = '<span class="status-closed">Chiuso</span>';
						break;
					case 'transfer':
						$status = '<span class="status-transfer">Trasferimento</span>';
						break;
					case 'transfer_accepted':
						$status = '<span class="status-transfer-accepted">Trasferito</span>';
						break;
					default:
						$status = '';
				}

                $buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/reports/reports/edit?report='.$report->id).'" class="btn btn-xs btn-warning edit-report" title="Modifica segnalazione"><i class="fa fa-pencil"></i></a>'; 
				$buttons .= '<a class="btn btn-xs btn-default download-report-pdf" data-id="' . $report->id . '" ><i class="fa fa-file-pdf-o" data-toggle="tooltip" title="Scarica pdf"></i></a>';
				if ($user['role'] == 'admin' || $user['role'] == 'centro') {
					$buttons .= '<a class="btn btn-xs btn-danger delete-report" data-id="' . $report->id . '" ><i class="fa fa-trash" data-toggle="tooltip" title="Elimina segnalazione"></i></a>';
				}
				$buttons .= '</div>';

				$out['rows'][$key][] = $report['code'];
				if ($user['role'] == 'admin' || $user['role'] == 'centro') {
					$out['rows'][$key][] = htmlspecialchars($report['node']);
				}
				$out['rows'][$key][] = htmlspecialchars($report['witness']);
				$out['rows'][$key][] = $status;
				$out['rows'][$key][] = $report['days_open'];

				//Domande scheda
				$questions = TableRegistry::get('Surveys.SurveysQuestionMetadata')->getTableQuestions();
				foreach ($questions as $question) {
					$out['rows'][$key][] = htmlspecialchars($report['question'.$question['question_id']]['final_value']);
				}

				$out['rows'][$key][] = $buttons;
				
            }

            $this->_result = $out;

        }else{

            $this->_result = [];
        }
	}

	public function getUserRole()
	{
		$user = $this->request->session()->read('Auth.User');

		if($user){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $user['role'];
			$this->_result['msg'] = 'Ruolo utente recuperato.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero del ruolo utente.';
		}
	}

	public function loadReport($reportId)
	{
		if(!empty($reportId)){
			$report = TableRegistry::get('Reports.Reports')->get($reportId);

			$report['code'] = str_pad($report['code'], 5, "0", STR_PAD_LEFT);

			if(!empty($report['node_id'])){
				$report['node'] = TableRegistry::get('Aziende.Aziende')->get($report['node_id']);
			}

			$victim = null;
			if(!empty($report['victim_id'])){
				$victim = TableRegistry::get('Reports.Victims')->get($report['victim_id'], [
					'contain' => ['Users', 'Countries', 'Citizenships', 'Regions', 'Provinces', 'Cities']
				]);
				$victim['birth_year'] = empty($victim['birth_year']) ? '' : $victim['birth_year'];
				$victim['in_italy_from_year'] = empty($victim['in_italy_from_year']) ? '' : $victim['in_italy_from_year'];
				$victim['date_update'] = $victim['modified']->format('d/m/Y');
				$victim['lives_with'] = explode(',', $victim['lives_with']);
			}

			$witness = null;
			if(!empty($report['witness_id'])){
				$witness = TableRegistry::get('Reports.Witnesses')->get($report['witness_id'], [
					'contain' => ['Users', 'Countries', 'Citizenships', 'Regions', 'Provinces', 'Cities', 
						'RegionLegal', 'ProvinceLegal', 'CityLegal', 'RegionOperational', 'ProvinceOperational', 'CityOperational'
					]
				]);
				$witness['birth_year'] = empty($witness['birth_year']) ? '' : $witness['birth_year'];
				$witness['in_italy_from_year'] = empty($witness['in_italy_from_year']) ? '' : $witness['in_italy_from_year'];
				$witness['date_update'] = $witness['modified']->format('d/m/Y');
				$witness['lives_with'] = explode(',', $witness['lives_with']);
			}

			$history = TableRegistry::get('Reports.Histories')->find()
				->where(['report_id' => $reportId])
				->order(['date' => 'ASC'])
				->toArray();

			$this->_result['response'] = "OK";
			$this->_result['data'] = ['report' => $report, 'victim' => $victim, 'witness' => $witness, 'history' => $history];
			$this->_result['msg'] = 'Dati caso recuperati.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore: ID mancante.';
		}
	}

	public function searchNode($search) 
    {
        $nodes = TableRegistry::get('Aziende.Aziende')->find()
			->select(['id' => 'Aziende.id', 'label' => 'Aziende.denominazione'])
			->where([
				'Aziende.denominazione LIKE' => '%'.$search.'%',
			])
			->order('Aziende.denominazione ASC')
			->toArray();

		if($nodes){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $nodes;
			$this->_result['msg'] = 'Nodi recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun nodo trovato.';
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

	public function searchCitizenship($search) 
    {
        $citizenships = TableRegistry::get('Luoghi')->find()
			->select(['id' => 'Luoghi.c_luo', 'label' => 'Luoghi.des_luo', 'user_text'])
			->where([
				'OR' => [
					['Luoghi.in_luo' => 1],
					['Luoghi.in_luo' => 5]
				],
				'Luoghi.des_luo LIKE' => '%'.$search.'%',
			])
			->order('Luoghi.des_luo ASC')
			->toArray();

		if($citizenships){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $citizenships;
			$this->_result['msg'] = 'Cittadinanze recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna cittadinanza trovata.';
		}		
	}
	
	public function searchRegion($search) 
    {
        $regions = TableRegistry::get('Luoghi')->find()
			->select(['id' => 'Luoghi.c_luo', 'label' => 'Luoghi.des_luo'])
			->where([
				'Luoghi.in_luo' => 2,
				'Luoghi.des_luo LIKE' => '%'.$search.'%',
			])
			->order('Luoghi.des_luo ASC')
			->toArray();

		if($regions){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $regions;
			$this->_result['msg'] = 'Regioni recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna regione trovata.';
		}		
	}
	
	public function searchProvince($search, $regionId) 
    {
		$region = TableRegistry::get('Luoghi')->get($regionId);
        $provinces = TableRegistry::get('Luoghi')->find()
			->select(['id' => 'Luoghi.c_luo', 'label' => 'Luoghi.des_luo'])
			->where([
				'Luoghi.in_luo' => 3,
				'Luoghi.c_rgn' => $region['c_rgn'],
				'Luoghi.des_luo LIKE' => '%'.$search.'%',
			])
			->order('Luoghi.des_luo ASC')
			->toArray();

		if($provinces){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $provinces;
			$this->_result['msg'] = 'Province recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna provincia trovata.';
		}		
	}
	
	public function searchCity($search, $provinceId) 
    {
		$province = TableRegistry::get('Luoghi')->get($provinceId);
        $cities = TableRegistry::get('Luoghi')->find()
			->select(['id' => 'Luoghi.c_luo', 'label' => 'Luoghi.des_luo'])
			->where([
				'Luoghi.in_luo' => 4,
				'Luoghi.c_prv' => $province['c_prv'],
				'Luoghi.des_luo LIKE' => '%'.$search.'%',
			])
			->order('Luoghi.des_luo ASC')
			->toArray();

		if($cities){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $cities;
			$this->_result['msg'] = 'Comuni recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessun comune trovato.';
		}		
	}
	
	public function saveVictim()
	{
		$data = $this->request->data;

		$user = $this->request->session()->read('Auth.User');

		$victimsTable = TableRegistry::get('Reports.Victims');

		if(empty($data['victim_id'])){
			$victim = $victimsTable->newEntity();
		}else{
			$victim = $victimsTable->get($data['victim_id']);
		}

		$data['user_update_id'] = $user['id'];
		$livesWith = [];
		foreach(json_decode($data['lives_with']) as $index => $value){
			if($value){
				$livesWith[] = $index;
			}
		}
		$data['lives_with'] = implode(',',$livesWith);
		$data['country_id'] = $data['country'];
		$data['citizenship_id'] = $data['citizenship'];
		$data['region_id'] = $data['region'];
		$data['province_id'] = $data['province'];
		$data['city_id'] = $data['city'];

		$victim = $victimsTable->patchEntity($victim, $data);

		//assegnazione nodo e codice provincia
		if($user['role'] == 'nodo'){
			$contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
			$nodeId = $contatto['id_azienda'];
		}elseif($user['role'] == 'centro'){
			$nodeId = NULL;
		}else{
			$nodeId = empty($data['node_id']) ? NULL : $data['node_id'];
		}

		if(empty($data['report_id'])) {
			if(empty($nodeId)){
				$provinceCode = 'PIE';
			}else{
				$azienda = TableRegistry::get('Aziende.Aziende')->get($nodeId);
				$provinceCode = $azienda['codice_provincia'];
			}
		}

		if(empty($data['report_id']) && empty($provinceCode)) {
			$this->_result['response'] = "KO";
			$this->_result['msg'] = "Attenzione! Il codice provincia del nodo non è impostato correttamente e non si possono salvare i casi. Contattare l'amministratore di sistema per risolvere.";
		}else{
			if($victimsTable->save($victim)){ 
				$reportsTable = TableRegistry::get('Reports.Reports');
				if(empty($data['report_id'])){
					$newReport = true;
					$report = $reportsTable->newEntity();
					$report->user_create_id = $user['id'];
					$report->status = 'open';
					$report->province_code = $provinceCode;
					$report->opening_date = date('Y-m-d');
				}else{
					$newReport = false;
					$report = $reportsTable->get($data['report_id']);
				}
				$report->user_update_id = $user['id'];
				$report->victim_id = $victim->id;
				$report->node_id = $nodeId;

				$report = $reportsTable->save($report);

				if ($newReport && $report) {
					// Salvo storico apertura
					$date = new Time();
					$this->Reports->saveHistory($report['id'], $report['node_id'], 'open', $date);
				}

				$history = TableRegistry::get('Reports.Histories')->find()
					->where(['report_id' => $report['id']])
					->order(['date' => 'ASC'])
					->toArray();

				$this->_result['response'] = "OK";
				$this->_result['data'] = ['report' => $report, 'victim' => $victim->id, 'history' => $history];
				$this->_result['msg'] = 'Anagrafica vittima salvata con successo.';
			}else{
				$this->_result['response'] = "KO";
				$this->_result['msg'] = 'Errore nel salvataggio dell\'anagrafica della vittima.';
			}
		}
	}

	public function saveWitness()
	{
		$data = $this->request->data;

		$user = $this->request->session()->read('Auth.User');

		$witnessesTable = TableRegistry::get('Reports.Witnesses');

		if(empty($data['witness_id'])){
			$witness = $witnessesTable->newEntity();
		}else{
			$witness = $witnessesTable->get($data['witness_id']);
		}

		$data['user_update_id'] = $user['id'];

		if($data['type'] == 'person'){
			$livesWith = [];
			foreach(json_decode($data['lives_with']) as $index => $value){
				if($value){
					$livesWith[] = $index;
				}
			}
			$data['lives_with'] = implode(',',$livesWith);
			$data['country_id'] = $data['country'];
			$data['citizenship_id'] = $data['citizenship'];
			$data['region_id'] = $data['region'];
			$data['province_id'] = $data['province'];
			$data['city_id'] = $data['city'];
		}elseif($data['type'] == 'business'){
			$data['lives_with'] = '';
			$data['region_id_legal'] = $data['region_legal'];
			$data['province_id_legal'] = $data['province_legal'];
			$data['city_id_legal'] = $data['city_legal'];
			$data['region_id_operational'] = $data['region_operational'];
			$data['province_id_operational'] = $data['province_operational'];
			$data['city_id_operational'] = $data['city_operational'];
		}
		
		$witness = $witnessesTable->patchEntity($witness, $data);

		//assegnazione nodo e codice provincia
		if($user['role'] == 'nodo'){
			$contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
			$nodeId = $contatto['id_azienda'];
		}elseif($user['role'] == 'centro'){
			$nodeId = NULL;
		}else{
			$nodeId = empty($data['node_id']) ? NULL : $data['node_id'];
		}

		if(empty($data['report_id'])) {
			if(empty($nodeId)){
				$provinceCode = 'PIE';
			}else{
				$azienda = TableRegistry::get('Aziende.Aziende')->get($nodeId);
				$provinceCode = $azienda['codice_provincia'];
			}
		}

		if(empty($data['report_id']) && empty($provinceCode)) {
			$this->_result['response'] = "KO";
			$this->_result['msg'] = "Attenzione! Il codice provincia del nodo non è impostato correttamente e non si possono salvare i casi. Contattare l'amministratore di sistema per risolvere.";
		}else{
			if($witnessesTable->save($witness)){
				$reportsTable = TableRegistry::get('Reports.Reports');
				if(empty($data['report_id'])){
					$newReport = true;
					$report = $reportsTable->newEntity();
					$report->user_create_id = $user['id'];
					$report->status = 'open';
					$report->province_code = $provinceCode;
					$report->opening_date = date('Y-m-d');
				}else{
					$newReport = false;
					$report = $reportsTable->get($data['report_id']);
				}
				$report->type_reporter = $data['type_reporter'];
				$report->user_update_id = $user['id'];
				$report->witness_id = $witness->id;
				$report->node_id = $nodeId;

				$report = $reportsTable->save($report);

				if ($newReport && $report) {
					// Salvo storico apertura
					$date = new Time();
					$this->Reports->saveHistory($report['id'], $report['node_id'], 'open', $date);
				}

				$history = TableRegistry::get('Reports.Histories')->find()
					->where(['report_id' => $report['id']])
					->order(['date' => 'ASC'])
					->toArray();

				$this->_result['response'] = "OK";
				$this->_result['data'] = ['report' => $report, 'witness' => $witness->id, 'history' => $history];
				$this->_result['msg'] = 'Anagrafica segnalante salvata con successo.';
			}else{
				$this->_result['response'] = "KO";
				$this->_result['msg'] = 'Errore nel salvataggio dell\'anagrafica segnalante.';
			}
		}
	}

	public function checkAnagrafica($type, $field, $value) 
	{
		$anagrafica = [];
		if($type == 'victim'){
			$anagrafica = TableRegistry::get('Reports.Victims')->find()
				->where(['Victims.'.$field => $value])
				->order(['Victims.created' => 'DESC'])
				->contain(['Users', 'Countries', 'Citizenships', 'Regions', 'Provinces', 'Cities'])
				->first();
		}elseif($type == 'witness'){
			$anagrafica = TableRegistry::get('Reports.Witnesses')->find()
				->where(['Witnesses.'.$field => $value])
				->order(['Witnesses.created' => 'DESC'])
				->contain(['Users', 'Countries', 'Citizenships', 'Regions', 'Provinces', 'Cities',
					'RegionLegal', 'ProvinceLegal', 'CityLegal', 'RegionOperational', 'ProvinceOperational', 'CityOperational'
				])
				->first();
		}

		if($anagrafica){
			$anagrafica['birth_year'] = empty($anagrafica['birth_year']) ? '' : $anagrafica['birth_year'];
			$anagrafica['in_italy_from_year'] = empty($anagrafica['in_italy_from_year']) ? '' : $anagrafica['in_italy_from_year'];
			$anagrafica['date_update'] = $anagrafica['modified']->format('d/m/Y');
			$anagrafica['lives_with'] = explode(',', $anagrafica['lives_with']);

			$this->_result['response'] = "OK";
			$this->_result['data'] = $anagrafica;
			$this->_result['msg'] = 'Anagrafica recuperata.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna anagrafica trovata.';
		}
	}

	public function deleteReport()
    {
		if (!empty($this->request->data['id'])) {
			$id = $this->request->data['id'];

			$reportsTable = TableRegistry::get('Reports.Reports');

			$report = $reportsTable->get($id);

			$report->deleted = '1';

			if($reportsTable->save($report)){
				$this->_result['response'] = 'OK';
				$this->_result['msg'] = 'Segnalazione eliminata con successo.';
			}else{
				$this->_result['msg'] = 'Errore nell\'eliminazione della segnalazione.';
			}
		} else {
			$this->_result['msg'] = 'Errore nell\'eliminazione della segnalazione: ID mancante.';
		}
	}

	public function saveDocument()
    {
        $data = $this->request->data;

        if(count($data['documents']) == 1 && empty($data['documents'][0]['tmp_name'])){
            $this->_result['msg'] = 'Nessun file caricato.';        
        }else{
            $documentsTable = TableRegistry::get('Reports.Documents');

            $basePath = ROOT.DS.Configure::read('dbconfig.reports.DOCUMENTS_UPLOAD_PATH');

            $error = false;

            foreach($data['documents'] as $index => $document){ 
                $fileName = uniqid().'_'.$document['name'];
                $path = date('Y').DS.date('m').DS;

                if (!is_dir($basePath.$path) && !mkdir($basePath.$path, 0755, true)){
                    $error = true;
                }

                if(!move_uploaded_file($document['tmp_name'], $basePath.$path.$fileName) ){
                    $error = true;
                }

                if(!$error){
                    $entity = $documentsTable->newEntity();

                    $entity->report_id = $data['report_id'];
                    $entity->file = $document['name'];
                    $entity->file_path = $path.$fileName;
                    $entity->title = $data['documents_title'][$index];
                    $entity->description = $data['documents_description'][$index];

                    if(!$documentsTable->save($entity)){
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

    public function getDocuments($reportId = ''){ 
		if (!empty($reportId)) {
			$pass['query'] = $this->request->query;

			$report = TableRegistry::get('Reports.Reports')->get($reportId);
			$user = $this->request->session()->read('Auth.User');

			$res = $this->Reports->getDocuments($reportId, $pass);
	
			$out['total_rows'] = $res['tot'];

			if(!empty($res['res'])){ 
				
				foreach ($res['res'] as $key => $document) { 

					$buttons = "";
					$buttons .= '<div class="button-group text-center">';
					$buttons .= '<a class="btn btn-xs btn-primary download-document" href="#" data-id="' . $document->id . '" data-toggle="tooltip" data-placement="top" title="Scarica documento"><i class="fa fa-download"></i></a>';
					if ($report->status == 'transfer' || $report->status == 'transfer_accepted' || ($user['role'] != 'admin' && $report->status == 'closed') || ($user['role'] == 'centro' && $report->node_id)) {
						$buttons .= '<a class="disabled btn btn-xs btn-danger delete-document" href="#" data-id="' . $document->id . '" data-toggle="tooltip" data-placement="top" title="Elimina documento"><i class="fa fa-trash"></i></a>';
					} else {
						$buttons .= '<a class="btn btn-xs btn-danger delete-document" href="#" data-id="' . $document->id . '" data-toggle="tooltip" data-placement="top" title="Elimina documento"><i class="fa fa-trash"></i></a>';
					}
					$buttons .= '</div>';

					$out['rows'][] = array(
						htmlspecialchars($document['file']),
						htmlspecialchars($document['title']),
						htmlspecialchars($document['description']),
						$buttons
					);
				}

				$this->_result = $out;
			} else {
				$this->_result = [];
			}
        }else{
            $this->_result = [];
        }
    }

    public function downloadDocument($id)
    {
        $documentsTable = TableRegistry::get('Reports.Documents');

        $document = $documentsTable->get($id);
        
        $basePath = ROOT.DS.Configure::read('dbconfig.reports.DOCUMENTS_UPLOAD_PATH');
        $uploadPath = $basePath.$document['file_path'];
        $name = $document['file'];

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

    public function deleteDocument()
    {
        $id = $this->request->data['id'];

        $documentsTable = TableRegistry::get('Reports.Documents');

        $document = $documentsTable->get($id);

        $document->deleted = '1';

        if($documentsTable->save($document)){
            $this->_result['response'] = 'OK';
            $this->_result['msg'] = 'Documento eliminato con successo.';
        }else{
			$this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nell\'eliminazione del documento.';
        }
	}


	public function closeReport()
    {
        $data = $this->request->data;

        $reportsTable = TableRegistry::get('Reports.Reports');

        $report = $reportsTable->get($data['report_id']);

		$datetime =  preg_replace('/( \(.*)$/','', $data['date']);
		$date = new Time($datetime);

		$report->status = 'closed';
        $report->closing_date = $date->format('Y-m-d');
		$report->closing_outcome_id = $data['outcome'];

        if ($reportsTable->save($report)) {
			// Salvo storico chiusura
			$this->Reports->saveHistory($report['id'], $report['node_id'], 'close', $date, $data['motivation'], $data['outcome']);

            $this->_result['response'] = 'OK';
            $this->_result['msg'] = 'Caso chiuso con successo.';
        } else {
			$this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nella chiusura del caso.';
        }
	}

	public function reopenReport()
    {
        $data = $this->request->data;

        $reportsTable = TableRegistry::get('Reports.Reports');

        $report = $reportsTable->get($data['report_id']);

		$date = new Time();

		$report->status = 'open';
		$report->opening_date = $date->format('Y-m-d');
        $report->closing_date = NULL;
		$report->closing_outcome_id = NULL;

        if ($reportsTable->save($report)) {
			// Salvo storico riapertura
			$this->Reports->saveHistory($report['id'], $report['node_id'], 'reopen', $date, $data['motivation']);

            $this->_result['response'] = 'OK';
            $this->_result['msg'] = 'Caso riaperto con successo.';
        } else {
			$this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nella riapertura del caso.';
        }
	}

	public function transferReport()
    {
        $data = $this->request->data;

        $reportsTable = TableRegistry::get('Reports.Reports');

        $report = $reportsTable->get($data['report_id']);

		$date = new Time();

		$report->status = 'transfer';
		$report->transfer_date = $date;

        if ($reportsTable->save($report)) {
			// Salvo storico trasferimento
			$this->Reports->saveHistory($report['id'], $report['node_id'], 'transfer', $date, $data['motivation']);

            $this->_result['response'] = 'OK';
            $this->_result['msg'] = 'Caso trasferito con successo.';
        } else {
			$this->_result['response'] = 'KO';
            $this->_result['msg'] = 'Errore nel trasferimento del caso.';
        }
	}

	public function confirmTransferReport()
	{
		$data = $this->request->data;

		if (!empty($data['report_id']) && isset($data['node_id'])) {
			$reportsTable = TableRegistry::get('Reports.Reports');

			$report = $reportsTable->get($data['report_id']);

			$data['node_id'] = empty($data['node_id']) ? NULL : $data['node_id'];

			if ($data['node_id'] != $report['node_id']) {
				$report->status = 'transfer_accepted';

				if ($reportsTable->save($report)) {
					$clonedReportData = $report->toArray();
					$clonedReportData['interview_id'] = NULL;

					// Clonazione scheda caso
					if (!empty($report['interview_id'])) {
						$interviewsTable = TableRegistry::get('Surveys.SurveysInterviews');
						$interview = $interviewsTable->get($report['interview_id']);
						$clonedInterviewData = $interview->toArray();
						unset($clonedInterviewData['id']);
						unset($clonedInterviewData['created']);
						unset($clonedInterviewData['modified']);
						$clonedInterview = $interviewsTable->newEntity($clonedInterviewData);
						if ($interviewsTable->save($clonedInterview)) {
							$clonedReportData['interview_id'] = $clonedInterview['id'];

							$answersTable = TableRegistry::get('Surveys.SurveysAnswers');
							$answers = $answersTable->find()->where(['id_interview' => $report['interview_id']])->toArray();
							foreach ($answers as $answer) {
								$clonedAnswerData = $answer->toArray();
								$clonedAnswerData['id_interview'] = $clonedInterview['id'];
								unset($clonedAnswerData['id']);
								unset($clonedAnswerData['created']);
								unset($clonedAnswerData['modified']);
								$clonedAnswer = $answersTable->newEntity($clonedAnswerData);
								$answersTable->save($clonedAnswer);
							}

							$answerDataTable = TableRegistry::get('Surveys.SurveysAnswerData');
							$answersData = $answerDataTable->find()->where(['interview_id' => $report['interview_id']])->toArray();
							foreach ($answersData as $answerData) {
								$clonedAnswerDataData = $answerData->toArray();
								$clonedAnswerDataData['interview_id'] = $clonedInterview['id'];
								unset($clonedAnswerDataData['id']);
								unset($clonedAnswerDataData['created']);
								unset($clonedAnswerDataData['modified']);
								$clonedAnswerData = $answerDataTable->newEntity($clonedAnswerDataData);
								$answerDataTable->save($clonedAnswerData);
							}
						}
					}

					// Clonazione report per nuovo intestatario
					$clonedReportData['node_id'] = $data['node_id'];
					$clonedReportData['status'] = 'open';
					$clonedReportData['transfer_date'] = NULL;
					unset($clonedReportData['id']);
					unset($clonedReportData['created']);
					unset($clonedReportData['modified']);

					$clonedReport = $reportsTable->newEntity($clonedReportData);
					if ($reportsTable->save($clonedReport)) {
						// Salvo storico trasferimento accettato
						$date = new Time();
						$this->Reports->saveHistory($report['id'], $clonedReport['node_id'], 'transfer_accepted', $date);

						// Clonazione documenti
						$documentsTable = TableRegistry::get('Reports.Documents');
						$documents = $documentsTable->find()->where(['report_id' => $report['id']])->toArray();
						foreach ($documents as $document) {
							$clonedDocumentData = $document->toArray();
							$clonedDocumentData['report_id'] = $clonedReport['id'];
							unset($clonedDocumentData['id']);
							unset($clonedDocumentData['created']);
							unset($clonedDocumentData['modified']);
							$clonedDocument = $documentsTable->newEntity($clonedDocumentData);
							$documentsTable->save($clonedDocument);
						}

						// Clonazione storico
						$historiesTable = TableRegistry::get('Reports.Histories');
						$histories = $historiesTable->find()->where(['report_id' => $report['id']])->toArray();
						foreach ($histories as $history) {
							$clonedHistoryData = $history->toArray();
							$clonedHistoryData['report_id'] = $clonedReport['id'];
							unset($clonedHistoryData['id']);
							unset($clonedHistoryData['created']);
							$clonedHistory = $historiesTable->newEntity($clonedHistoryData);
							$historiesTable->save($clonedHistory);
						}

						$this->_result['response'] = 'OK';
						$this->_result['msg'] = 'Conferma trasferimento caso avvenuta con successo.';
					} else {
						$this->_result['response'] = 'KO';
						$this->_result['msg'] = 'Errore nella conferma del trasferimento del caso.';
					}
				} else {
					$this->_result['response'] = 'KO';
					$this->_result['msg'] = 'Errore nella conferma del trasferimento del caso.';
				}
			} else {
				$this->_result['response'] = 'KO';
				$this->_result['msg'] = 'Errore nella conferma del trasferimento del caso: l\'intestatario non è cambiato.';
			}
		} else {
			$this->_result['response'] = 'KO';
			$this->_result['msg'] = 'Errore nella conferma del trasferimento del caso: dati mancanti.';
		}
	}
}
