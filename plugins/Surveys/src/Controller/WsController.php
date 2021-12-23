<?php
namespace Surveys\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Cake\Core\Configure;
use Cake\I18n\Time;

/**
 * Surveys Controller
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Surveys.Surveys');
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
			$centroActions = ['getSurvey', 'getInterviews', 'getInterview', 'saveInterview', 'getInterviewForNewSurvey'];
			if (in_array($this->request->getParam('action'), $centroActions)) {
				return true;
			}
		}

		if($user['role'] == 'nodo'){
			$nodoActions = ['getSurvey', 'getInterviews', 'getInterview', 'saveInterview', 'getInterviewForNewSurvey'];
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
        $this->set('result', json_encode($this -> _result));
	}
	
	public function getSurveys()
	{
		$user = $this->request->session()->read('Auth.User');

		$pass['query'] = $this->request->query;

		if($user['role'] == 'admin'){
			if(isset($pass['query']['filter'][3])){
				if($pass['query']['filter'][3] == 'Pubblicato'){
					$pass['query']['filter'][3] = 1;
				}elseif($pass['query']['filter'][3] == 'Bozza'){
					$pass['query']['filter'][3] = 2;
				}elseif($pass['query']['filter'][3] == 'Annullato'){
					$pass['query']['filter'][3] = 3;
				}elseif($pass['query']['filter'][3] == 'Pubblicato (congelato)'){
					$pass['query']['filter'][3] = 4;
				}
			}
		}

        $res = $this->Surveys->getSurveys($pass, $user);
        
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $survey) {

				switch($survey->status){
					case '1':
						$status = '<span class="status-pubblicato">Pubblicato</span>';
						break;
					case '2':
						$status = '<span class="status-bozza">Bozza</span>';
						break;
					case '3':
						$status = '<span class="status-annullato">Annullato</span>';
						break;
					case '4':
						$status = '<span class="status-frozen">Pubblicato (congelato)</span>';
						break;
				}

                $buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/surveys/surveys/edit?survey='.$survey->id).'" class="btn btn-xs btn-warning edit-survey" title="Modifica questionario"><i class="fa fa-pencil"></i></a>'; 
				$buttons .= '<a href="'.Router::url('/surveys/surveys/interviews/'.$survey->id).'" class="btn btn-xs btn-primary survey-interviews" title="Visualizza interviste"><i class="fa fa-list"></i></a>'; 
				$buttons .= '<a class="btn btn-xs btn-info survey-clone" data-id="'.$survey->id.'" title="Clona questionario"><i class="fa fa-clone"></i></a>';
				$buttons .= '<a class="btn btn-xs btn-danger delete-survey" data-id="'.$survey->id.'" title="Annulla questionario"><i class="fa fa-trash"></i></a>';
				$buttons .= '</div>';

				$out['rows'][] = [
					htmlspecialchars($survey['title']),
					htmlspecialchars($survey['subtitle']),
					htmlspecialchars($survey['description']),
					$status,
					$buttons
				];

            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
	}

	public function getSurvey($id = false)
	{
		
		$surveys = TableRegistry::get('Surveys.Surveys');

		if($id){
			$survey = $surveys->get($id);
		}else{
			$survey = $surveys->find()->first();
		}

		$survey['partners'] = $this->Surveys->getSurveyPartners($survey['id']);

		$surveysChapters = TableRegistry::get('Surveys.SurveysChapters');
		$survey['chapters'] = $surveysChapters->getChaptersBySurvey($survey['id']);

		if($survey){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $survey;
			$this->_result['msg'] = 'Scheda recuperata correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero della scheda.';
		}		
	}

	public function getSurveyStatuses()
	{
		$statuses = TableRegistry::get('Surveys.SurveysStatuses')
			->find()
			->order('ordering ASC')
			->toArray();

		if($statuses){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $statuses;
			$this->_result['msg'] = 'Stati scheda recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero degli stati della scheda.';
		}		
	}

	public function getPartners()
	{
		$partners = TableRegistry::get('Aziende.Aziende')
			->find()
			->select(['code' => 'id', 'label' => 'denominazione'])
			->where(['deleted' => '0'])
			->order('denominazione ASC')
			->toArray();

		if($partners){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $partners;
			$this->_result['msg'] = 'Aziende recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero delle aziende.';
		}		
	}

	public function saveSurvey()
	{
		$data = $this->request->data;

		$surveys = TableRegistry::get('Surveys.Surveys');

		if(empty($data['idSurvey'])){
			$entity = $surveys->newEntity();
			$entity->version = date('Y.m').'.1';
		}else{
			$entity = $surveys->get($data['idSurvey']);
			if($data['changed'] == 1){
				$version = explode('.', $entity->version);
				if(date('Y.m') == $version[0].'.'.$version[1]){
					$entity->version = $version[0].'.'.$version[1].'.'.($version[2] + 1);
				}else{
					$entity->version = date('Y.m').'.1';
				}
			}
		}

		$data['status'] = 1;

		$surveys->patchEntity($entity, $data);

		if($surveys->save($entity)){

			/*$aziendeStructures = TableRegistry::get('Surveys.SurveysToStructures');

			foreach(json_decode($data['partners']) as $partner){
				if(!empty($partner->structures)){
					foreach($partner->structures as $sede){ 
						if($sede->selected){
							$structure = $aziendeStructures->newEntity();
							$structure->id_survey = $entity->id;
							$structure->id_azienda = $partner->code;
							$structure->id_sede = $sede->id;

							$aziendeStructures->save($structure);
						}
					}
				}else{
					$structure = $aziendeStructures->newEntity();
					$structure->id_survey = $entity->id;
					$structure->id_azienda = $partner->code;

					$aziendeStructures->save($structure);
				}
			}*/

			//Delete chapters for survey
			$surveysChapters = TableRegistry::get('Surveys.SurveysChapters');
			$groupCode = uniqid('', true);
			$surveysChapters->updateAll(['group_id' => $groupCode, 'deleted' => 1], ['id_survey' => $entity->id, 'deleted' => 0]);

			//Delete questions metadata for survey
			$surveysQuestionMetadata = TableRegistry::get('Surveys.SurveysQuestionMetadata');
			$surveysQuestionMetadata->deleteAll(['survey_id' => $entity->id]);

			$chapters = json_decode($data['chapters']);

			//Save chapters
			foreach($chapters as $index => $chapter){
				$surveyChapter = $surveysChapters->newEntity();

				$surveyChapter->id_survey = $entity->id;
				$surveyChapter->chapter = $index+1;
				$surveyChapter->chapter_data = json_encode($chapter);
				$surveyChapter->color = $chapter->color;

				$surveysChapters->save($surveyChapter);
			}

			//Save questions metadata
			if(!empty($chapters)){
				$this->saveItemsQuestionsMetadata($chapters, $entity->id);
			}

			$this->_result['response'] = "OK";
			$this->_result['data'] = $entity;
			$this->_result['msg'] = "Scheda salvata con successo.";
		}else{
			$message = "Errore nel salvataggio della scheda."; 
			foreach($entity->errors() as $field => $errors){ 
				foreach($errors as $rule => $msg){ 
					$message .= "\n" . $field.': '.$msg;
				}
			}  
			$this->_result['response'] = "KO";
			$this->_result['msg'] = $message;
		}
	}

	public function deleteSurvey()
	{
		$id = $this->request->data['id'];

		$surveys = TableRegistry::get('Surveys.Surveys');

		$survey = $surveys->get($id);

		$survey->status = 3;

		if($surveys->save($survey)){
			$this->_result['response'] = "OK";
            $this->_result['msg'] = "Questionario annullato con successo.";
        }else{
			$message = "Errore nell'annullamento del questionario."; 
			foreach($survey->errors() as $field){ 
				foreach($field as $rule => $msg){ 
					$message .= "\n" . $msg;
				}
			} 
            $this->_result['response'] = "KO";
            $this->_result['msg'] = $message;
        }
	}

	public function getInterviews($surveyId)
	{
		$pass['query'] = $this->request->query;

		if(isset($pass['query']['filter'][7])){
			if($pass['query']['filter'][7] == 'Compilazione'){
				$pass['query']['filter'][7] = 1;
			}elseif($pass['query']['filter'][7] == 'Firmata'){
				$pass['query']['filter'][7] = 2;
			}
		}

		if(isset($pass['query']['filter'][8])){
			if($pass['query']['filter'][8] == 'Sì'){
				$pass['query']['filter'][8] = 0;
			}elseif($pass['query']['filter'][8] == 'No'){
				$pass['query']['filter'][8] = 1;
			}
		}

        $res = $this->Surveys->getInterviews($pass, $surveyId);
        
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $interview) {

				switch($interview->status){
					case '1':
						$status = '<span class="status-pubblicato">Compilazione</span>';
						break;
					case '2':
						$status = '<span class="status-annullato">Firmata</span>';
						break;
				}

				switch($interview->not_valid){
					case '0':
						$valida = '<span class="interview-valid">Sì</span>';
						break;
					case '1':
						$valida = '<span class="interview-not-valid">No</span>';
						break;
				}

				$buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/surveys/surveys/answers?survey='.$surveyId.'&interview='.$interview->id).'" class="btn btn-xs btn-warning survey-answers" title="Modifica intervista"><i class="fa fa-pencil"></i></a>'; 
				if($interview->status == 2){
					$buttons .= '<a class="btn btn-xs btn-info interview-clone" data-id="'.$interview->id.'" title="Clona intervista"><i class="fa fa-clone"></i></a>';
				}
				$buttons .= '<a class="btn btn-xs btn-default interview-pdf" data-id="'.$interview->id.'" ><i class="fa fa-file-pdf-o" title="Scarica intervista in PDF"></i></a>';
				$buttons .= '</div>';

				
				$out['rows'][] = [
					htmlspecialchars($interview['azienda']),
					htmlspecialchars($interview['struttura']),
					htmlspecialchars($interview['title']),
					htmlspecialchars($interview['subtitle']),
					htmlspecialchars($interview['description']),
					$interview['created']->format('d/m/Y'),
					htmlspecialchars($interview['contatto']),
					$status,
					$valida,
					empty($interview['signature_date']) ? '' : $interview['signature_date']->format('d/m/Y'),
					$buttons
				];
            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
	}

	public function getInterview($id)
	{
		$interviews = TableRegistry::get('Surveys.SurveysInterviews');

		$interview = $interviews->get($id);

		$survey = TableRegistry::get('Surveys.Surveys')->get($interview['id_survey']);
		$interview['survey_version'] = $survey['version'];

		$surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
		$interview['answers'] = $surveysAnswers->getAnswersByInterview($id);
		$interview['date_update'] = $interview['modified']->format('d/m/Y');

		$user = TableRegistry::get('Users')->get($interview['id_user']);
		$interview['user_update'] = $user['nome'].' '.$user['cognome'];

		if($interview){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $interview;
			$this->_result['msg'] = 'Scheda recuperata correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero della scheda.';
		}		
	}

	public function getInterviewForNewSurvey($id)
	{
		$interviews = TableRegistry::get('Surveys.SurveysInterviews');
		$interview = $interviews->get($id);

		$survey = TableRegistry::get('Surveys.Surveys')->get($interview['id_survey']);

		$surveysChapters = TableRegistry::get('Surveys.SurveysChapters');
		$chapters = $surveysChapters->getChaptersBySurvey($survey['id']);
		
		$surveysAnswerData = TableRegistry::get('Surveys.SurveysAnswerData');
		$resAsnwerData = $surveysAnswerData->find()->where(['interview_id' => $id])->toArray();
		$answers = [];
		foreach($resAsnwerData as $data){
			$answers[$data['question_id']] = $data;
		}

		$interview['answers'] = $this->Surveys->setQuestionsValue($chapters, $answers);

		$interview['title'] = $survey['title'];
		$interview['subtitle'] = $survey['subtitle'];
		$interview['description'] = $survey['description'];
		$interview['version'] = $survey['version'];
		$interview['survey_version'] = $survey['version'];
		$interview['date_update'] = $interview['modified']->format('d/m/Y');

		$user = TableRegistry::get('Users')->get($interview['id_user']);
		$interview['user_update'] = $user['nome'].' '.$user['cognome'];

		if($interview){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $interview;
			$this->_result['msg'] = 'Scheda recuperata correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero della scheda.';
		}		
	}

	public function getEnabledPartners($surveyId)
	{
		$partners = TableRegistry::get('Aziende.Aziende')
			->find()
			->select(['code' => 'Aziende.id', 'label' => 'denominazione'])
			->where(['Aziende.id = sts.id_azienda', 'deleted' => '0'])
			->order('label ASC')
			->join([
				[
					'table' => 'surveys_to_structures',
					'alias' => 'sts',
					'type' => 'LEFT',
					'conditions' => ['sts.id_survey' => $surveyId]
 				]
			])
			->group(['Aziende.id'])
			->toArray();

		if($partners){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $partners;
			$this->_result['msg'] = 'Enti recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero degli enti.';
		}		
	}

	public function getStructuresInterview($partnerId)
	{
		$structures = TableRegistry::get('Aziende.Sedi')
			->find()
			->select(['code' => 'Sedi.id', 'label' => 'CONCAT(UPPER(Sedi.comune), " - ", Sedi.indirizzo)'])
			->where(['Sedi.id_azienda' => $partnerId, 'deleted' => '0'])
			->order('label ASC')
			->toArray();

		if($structures){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $structures;
			$this->_result['msg'] = 'Sedi recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero delle sedi.';
		}		
	}

	public function getEnabledStructures($surveyId, $partnerId)
	{
		$structures = TableRegistry::get('Aziende.Sedi')
			->find()
			->select(['code' => 'Sedi.id', 'label' => 'CONCAT(UPPER(Sedi.comune), " - ", Sedi.indirizzo)'])
			->where(['Sedi.id = sts.id_sede', 'deleted' => '0'])
			->order('label ASC')
			->join([
				[
					'table' => 'surveys_to_structures',
					'alias' => 'sts',
					'type' => 'LEFT',
					'conditions' => ['sts.id_survey' => $surveyId, 'sts.id_azienda' => $partnerId]
 				]
			])
			->group(['Sedi.id'])
			->toArray();

		if($structures){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $structures;
			$this->_result['msg'] = 'Sedi recuperate con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero delle sedi.';
		}		
	}

	public function saveInterview()
	{
		$data = $this->request->data;

		$user = $this->request->session()->read('Auth.User');
		
		$data['not_valid'] = filter_var($data['not_valid'], FILTER_VALIDATE_BOOLEAN);

		$interviews = TableRegistry::get('Surveys.SurveysInterviews');

		if(empty($data['idInterview'])){
			$entity = $interviews->newEntity();
			$entity->status = 1;
			$frozen = true;
		}else{
			$entity = $interviews->get($data['idInterview']);
			$frozen = false;
		}

		$data['id_user'] = $user['id'];

		$interviews->patchEntity($entity, $data);

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
			if($interviews->save($entity)){

				if($frozen){
					$surveys = TableRegistry::get('Surveys.Surveys');
					$survey = $surveys->get($data['id_survey']);
					$survey->status = 4;
					$surveys->save($survey);
				}

				//Delete answers for interview
				$surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
				$groupCode = uniqid('', true);
				$surveysAnswers->updateAll(['group_id' => $groupCode, 'deleted' => 1], ['id_interview' => $entity->id, 'deleted' => 0]);

				//Delete answer data for interview
				$surveysAnswerData = TableRegistry::get('Surveys.SurveysAnswerData');
				$surveysAnswerData->deleteAll(['interview_id' => $entity->id]);

				$answersData = json_decode($data['answers']);

				//Save answers
				foreach($answersData as $index => $answers){
					$surveyAnswer = $surveysAnswers->newEntity();

					$surveyAnswer->id_interview = $entity->id;
					$surveyAnswer->chapter = $index+1;
					$surveyAnswer->chapter_data = json_encode($answers);
					$surveyAnswer->color = $answers->color;

					$surveysAnswers->save($surveyAnswer);
				}

				//Save answers data
				if(!empty($answersData)){
					$this->saveItemsAnswersData($answersData, $entity->id);
				}

				//salvataggio report
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
				$report->interview_id = $entity->id;
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
				$this->_result['data'] = ['report' => $report, 'interview' => $entity->id, 'history' => $history];
				$this->_result['msg'] = "Scheda salvata con successo.";
			}else{
				$message = "Errore nel salvataggio della scheda.";  
				foreach($entity->errors() as $field => $errors){ 
					foreach($errors as $rule => $msg){ 
						$message .= "\n" . $field.': '.$msg;
					}
				} 
				$this->_result['response'] = "KO";
				$this->_result['msg'] = $message;
			}
		}
	}

	public function getPartnerStructures($idPartner)
	{
		$sedi = TableRegistry::get('Aziende.Sedi');

		$res = $sedi->find()
			->select(['id', 'comune', 'indirizzo'])
			->where(['id_azienda' => $idPartner, 'deleted' => 0])
			->order(['comune ASC', 'indirizzo ASC'])
			->toArray();

		$data = [];
		foreach($res as $sede){
			$data[] = [
				'id' => $sede['id'],
				'label' => strtoupper($sede['comune']).' - '.$sede['indirizzo'],
				'selected' => false
			];
		}

		$this->_result['response'] = "OK";
		$this->_result['data'] = $data;
		$this->_result['msg'] = "Sedi dell'ente recuperate con successo.";		
	}

	public function getManagingEntities()
	{
		$pass['query'] = $this->request->query;

		$userId = $this->request->session()->read('Auth.User.id');

        $res = $this->Surveys->getManagingEntities($pass, $userId);
     
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $entity) {

                $buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/surveys/surveys/structures/'.$entity->id).'" class="btn btn-xs btn-primary view-structures" title="Visualizza sedi"><i class="fa fa-industry"></i> Sedi</a>';
				$buttons .= '</div>';

				$out['rows'][] = [
					htmlspecialchars($entity['denominazione']),
					$buttons
				];

            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
	}

	public function getStructures($idManagingEntity)
	{
		$pass['query'] = $this->request->query;

		$userId = $this->request->session()->read('Auth.User.id');

        $res = $this->Surveys->getStructures($pass, $userId, $idManagingEntity);

        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $structure) {

                $buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/surveys/surveys/interviews/0/'.$idManagingEntity.'/'.$structure['id']).'" class="btn btn-xs btn-primary view-interviews" title="Visualizza interviste"><i class="fa fa-list"></i> interviste</a>';
				$buttons .= '</div>';

				$out['rows'][] = [
					htmlspecialchars(strtoupper($structure['comune'])),
					htmlspecialchars($structure['indirizzo']),
					$buttons
				];

            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
	}

	public function getInterviewsUser($managingEntityId, $structureId)
	{
		$userId = $this->request->session()->read('Auth.User.id');

		$pass['query'] = $this->request->query;

		if(isset($pass['query']['filter'][5])){
			if($pass['query']['filter'][5] == 'Compilazione'){
				$pass['query']['filter'][5] = 1;
			}elseif($pass['query']['filter'][5] == 'Firmata'){
				$pass['query']['filter'][5] = 2;
			}
		}

		if(isset($pass['query']['filter'][6])){
			if($pass['query']['filter'][6] == 'Sì'){
				$pass['query']['filter'][6] = 0;
			}elseif($pass['query']['filter'][6] == 'No'){
				$pass['query']['filter'][6] = 1;
			}
		}

        $res = $this->Surveys->getInterviewsUser($pass, $userId, $managingEntityId, $structureId);
        
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $interview) {

				switch($interview->status){
					case '1':
						$status = '<span class="status-pubblicato">Compilazione</span>';
						break;
					case '2':
						$status = '<span class="status-annullato">Firmata</span>';
						break;
				}

				switch($interview->not_valid){
					case '0':
						$valida = '<span class="interview-valid">Sì</span>';
						break;
					case '1':
						$valida = '<span class="interview-not-valid">No</span>';
						break;
				}

				$buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/surveys/surveys/answers?survey='.$interview->id_survey.'&interview='.$interview->id).'" class="btn btn-xs btn-warning survey-answers" title="Modifica intervista"><i class="fa fa-pencil"></i></a>'; 
				if($interview->status == 2){
					$buttons .= '<a class="btn btn-xs btn-info interview-clone" data-id="'.$interview->id.'" title="Clona intervista"><i class="fa fa-clone"></i></a>';
				}
				$buttons .= '<a class="btn btn-xs btn-default interview-pdf" data-id="'.$interview->id.'" ><i class="fa fa-file-pdf-o" title="Scarica intervista in PDF"></i></a>';
				$buttons .= '</div>';

				
				$out['rows'][] = [
					htmlspecialchars($interview['title']),
					htmlspecialchars($interview['subtitle']),
					htmlspecialchars($interview['description']),
					$interview['created']->format('d/m/Y'),
					htmlspecialchars($interview['contatto']),
					$status,
					$valida,
					empty($interview['signature_date']) ? '' : $interview['signature_date']->format('d/m/Y'),
					$buttons
				];
            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
	}

	public function verifySurveysStructure($managingEntityId, $structureId)
	{
		$userId = $this->request->session()->read('Auth.User.id');
		$partner = TableRegistry::get('Aziende.Aziende')->getPartnerByUser($userId);

		$surveys = TableRegistry::get('Surveys.Surveys')->verifySurveysStructure($partner['id'], $managingEntityId, $structureId);

		if($surveys){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $surveys;
            $this->_result['msg'] = 'Questionari trovati.';		
        }else{
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Nessun questionario trovato per questa sede.';
        }
	}


	public function saveImagePath($url = false)
	{
		$file = $this->request->data['file'];
		$surveyId = $this->request->data['survey'];

        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file['tmp_name']);
        $type = substr($type, 0, strpos($type, '/'));

        if($type != 'image'){
			$this->_result['msg'] = "Il file caricato non è un'immagine.";
			return;
		}

		if($file['size'] > 10000000){
			$this->_result['msg'] = "L'immagine caricata non deve superare i 10 MB.";
            return;
		}
		
		$basePath = ROOT.DS.Configure::read('dbconfig.surveys.ELEMENT_IMAGE_FILE_BASE_PATH');
        $uploadPath = date('Y').DS.date('m').DS.$surveyId;
        $fileName = uniqid().'_'.$file['name'];

        if (!is_dir($basePath.$uploadPath) && !mkdir($basePath.$uploadPath, 0755, true)){
			$this->_result['msg'] = "Errore durante la creazione della cartella.";
			return;
        }

        if(!move_uploaded_file($file['tmp_name'],$basePath.$uploadPath.DS.$fileName) ){
			$this->_result['msg'] = "Errore durante il salvataggio del file.";
			return;
        }

        $this->_result['response'] = "OK";
		$this->_result['data'] = $uploadPath.DS.$fileName;
		if($url){
			/*$type = pathinfo($basePath.$uploadPath.DS.$fileName, PATHINFO_EXTENSION);
			$image = file_get_contents($basePath.$uploadPath.DS.$fileName);
			$this->_result['data'] = 'data:image/' . $type . ';base64,' . base64_encode($image);*/
			$baseUrl = $_SERVER['HTTP_ORIGIN'].Router::url('/');
			$this->_result['data'] = $baseUrl.Configure::read('dbconfig.surveys.ELEMENT_IMAGE_FILE_BASE_PATH').$uploadPath.DS.$fileName;
		}
        $this->_result['msg'] = "Salvataggio avvenuto con successo.";
	}

	public function viewImage($path)
	{
		$basePath = ROOT.DS.Configure::read('dbconfig.surveys.ELEMENT_IMAGE_FILE_BASE_PATH');

		if(file_exists($basePath.$path)){
            $this->response->file($basePath.$path , array(
                'download'=> false,
                'name'=> end(explode('/', $path))
            ));
            return $this->response;
        }else{
            $this->_result['msg'] = 'Il file richiesto non esiste.';
        }
	}

	public function deleteImage()
	{
		$path = $this->request->data['path'];
		$basePath = ROOT.DS.Configure::read('dbconfig.surveys.ELEMENT_IMAGE_FILE_BASE_PATH');
		
		if(unlink($basePath.$path)){
			$this->_result['response'] = "OK";
			$this->_result['msg'] = "Eliminazione avvenuto con successo.";
		}
	}

	public function cloneSurvey()
	{
		$surveyId = $this->request->data['survey_id'];

		$surveys = TableRegistry::get('Surveys.Surveys');

		$original = $surveys->get($surveyId);

		//Salvataggio intestazione questionario
		$cloned = $surveys->newEntity();

		unset($original->id);
		unset($original->created);
		unset($original->modified);

		$surveys->patchEntity($cloned, $original->toArray());

		$cloned->status = 2;
		$cloned->valid_from = date('Y-m-d');
		$cloned->cloned_by = $surveyId;

		if($surveys->save($cloned)){
				
			//Associazione strutture
			$surveysStructures = TableRegistry::get('Surveys.SurveysToStructures');

			$aziende = $surveysStructures->getStructuresForSurvey($surveyId);

			$errorStructures = false;

			foreach($aziende as $a){ 
				if($errorStructures){
					break;
				}
				foreach(explode(',', $a->sedi) as $s){ 
					$entityStructure = $surveysStructures->newEntity();

					$entityStructure->id_survey = $cloned->id;
					$entityStructure->id_gestore = $a->id_azienda;
					$entityStructure->id_sede = $s;

					if(!$surveysStructures->save($entityStructure)){
						$errorStructures = true;
					}		
				}		
			} 

			//Salvataggio sezioni
			if(!$errorStructures){

				$surveysChapters = TableRegistry::get('Surveys.SurveysChapters');

				$chapters = $surveysChapters->getChaptersBySurvey($surveyId, false);

				$errorChapters = false;

				foreach($chapters as $c){ 
					if($errorChapters){
						break;
					}
			
					$entityChapter = $surveysChapters->newEntity();

					unset($c->id);
					unset($c->created);
					unset($c->modified);

					$surveysChapters->patchEntity($entityChapter, $c->toArray());

					$entityChapter->id_survey = $cloned->id;

					if(!$surveysChapters->save($entityChapter)){
						$errorChapters = true;
					}			
				} 
			}

			if($errorStructures){
				$surveys->delete($cloned->id);
				$this->_result['msg'] = "Errore nella clonazione del questionario. Salvataggio sedi non riuscito.";
			}elseif($errorChapters){
				$surveys->delete($cloned->id);
				$this->_result['msg'] = "Errore nella clonazione del questionario. Salvataggio sezioni non riuscito.";
			}else{
				$this->_result['response'] = "OK";
				$this->_result['msg'] = "Clonazione del questionario avvenuta con successo.";
			}
		}else{
			$this->_result['msg'] = "Errore nella clonazione del questionario.";
		}

	}

	public function setInterviewSigned()
	{
		$interviewId = $this->request->data['idInterview'];

		$interviews = TableRegistry::get('Surveys.SurveysInterviews');

		$interview = $interviews->get($interviewId);

		$interview->status = 2;
		$interview->signature_date = date('Y-m-d');

		if($interviews->save($interview)){
			$this->_result['response'] = "OK";
			$this->_result['msg'] = "Stato dell'intervista impostato su 'Firmata'.";
		}else{
			$this->_result['msg'] = "Errore. Stato dell'intervista non impostato.";
		}
	}

	public function cloneInterview()
	{
		$interviewId = $this->request->data['interview_id'];

		$interviews = TableRegistry::get('Surveys.SurveysInterviews');

		$original = $interviews->get($interviewId);

		//Salvataggio intestazione intervista
		$cloned = $interviews->newEntity();

		unset($original->id);
		unset($original->created);
		unset($original->modified);

		$interviews->patchEntity($cloned, $original->toArray());

		$cloned->status = 1;
		$cloned->cloned_by = $interviewId;

		if($interviews->save($cloned)){

			//Salvataggio sezioni
			$surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');

			$answers = $surveysAnswers->getAnswersByInterview($interviewId, false);

			$errorAnswers = false;

			foreach($answers as $a){ 
				if($errorAnswers){
					break;
				}
		
				$entityAnswer = $surveysAnswers->newEntity();

				unset($a->id);
				unset($a->created);
				unset($a->modified);

				$surveysAnswers->patchEntity($entityAnswer, $a->toArray());

				$entityAnswer->id_interview = $cloned->id;

				if(!$surveysAnswers->save($entityAnswer)){
					$errorAnswers = true;
				}			
			} 

			if($errorAnswers){
				$surveys->delete($cloned->id);
				$this->_result['msg'] = "Errore nella clonazione dell'intervista. Salvataggio sezioni non riuscito.";
			}else{
				$this->_result['response'] = "OK";
				$this->_result['msg'] = "Clonazione dell'intervista avvenuta con successo.";
			}
		}else{
			$this->_result['msg'] = "Errore nella clonazione del questionario.";
		}

	}

	public function getStandardTexts()
	{
		$standardTexts = [];

		$standardTexts = TableRegistry::get('Surveys.SurveysChaptersContents')->find()->order(['ordering ASC', 'name ASC'])->toArray();

		$this->_result['response'] = "OK";
		$this->_result['data'] = $standardTexts;
		$this->_result['msg'] = 'Testi standard recuperati con sucesso.';	
	}

	public function getChapters()
	{
		$pass['query'] = $this->request->query;


        $res = $this->Surveys->getChapters($pass);
        
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $chapter) {

                $buttons = "";
				$buttons .= '<div class="button-group">';
				$buttons .= '<a href="'.Router::url('/surveys/surveys/chapterPreview/'.$chapter->id).'" target="_blank" class="btn btn-sm btn-info preview-chapter" data-toggle="tooltip" title="Visualizza anteprima capitolo" ><i class="fa fa-eye"></i></a>'; 
				$buttons .= '<span data-toggle="tooltip" title="Modifica capitolo"><a class="btn btn-sm btn-warning edit-chapter" data-id="'.$chapter->id.'" ><i class="fa fa-pencil"></i></a></span>'; 
				$buttons .= '<a class="btn btn-sm btn-danger delete-chapter" data-id="'.$chapter->id.'" data-toggle="tooltip" title="Annulla capitolo"><i class="fa fa-trash"></i></a>';
				$buttons .= '</div>';

				$out['rows'][] = [
					htmlspecialchars($chapter['name']),
					htmlspecialchars($chapter['ordering']),
					$buttons
				];

            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
	}

	public function getChapter($id = 0)
	{
		if(!empty($id)){
			$chapters = TableRegistry::get('Surveys.SurveysChaptersContents');

			$chapter = $chapters->get($id);		

			if($chapter){
				$this->_result['response'] = "OK";
				$this->_result['data'] = $chapter;
				$this->_result['msg'] = 'Capitolo recuperato correttamente.';
			}else{
				$this->_result['response'] = "KO";
				$this->_result['msg'] = 'Errore nel recupero del capitolo.';
			}		
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero del capitolo: ID mancante.';
		}
	}

	public function saveChapter()
	{
		$data = $this->request->data;

		$chapters = TableRegistry::get('Surveys.SurveysChaptersContents');

		if(empty($data['id'])){
			$entity = $chapters->newEntity();
		}else{
			$entity = $chapters->get($data['id']);
		}

		$chapters->patchEntity($entity, $data);

		if($chapters->save($entity)){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $entity->id;
			$this->_result['msg'] = "Capitolo salvato con successo.";
		}else{
			$message = "Errore nel salvataggio del capitolo."; 
			foreach($entity->errors() as $field => $errors){ 
				foreach($errors as $rule => $msg){ 
					$message .= "\n" . $field.': '.$msg;
				}
			}  
			$this->_result['response'] = "KO";
			$this->_result['msg'] = $message;
		}
	}

	public function deleteChapter()
	{
		$id = $this->request->data['id'];

		$chapters = TableRegistry::get('Surveys.SurveysChaptersContents');

		$chapter = $chapters->get($id);

		$chapter->deleted = 1;

		if($chapters->save($chapter)){
			$this->_result['response'] = "OK";
            $this->_result['msg'] = "Capitolo eliminato con successo.";
        }else{
			$message = "Errore nell'eliminazione del capitolo."; 
			foreach($chapter->errors() as $field){ 
				foreach($field as $rule => $msg){ 
					$message .= "\n" . $msg;
				}
			} 
            $this->_result['response'] = "KO";
            $this->_result['msg'] = $message;
        }
	}

	private function saveItemsQuestionsMetadata($items, $surveyId){
		$surveysQuestionMetadata = TableRegistry::get('Surveys.SurveysQuestionMetadata');

		foreach($items as $item){
			foreach($item->questions as $question){
				$newEntity = $surveysQuestionMetadata->newEntity();
				$data = [
					'survey_id' => $surveyId,
					'question_id' => $question->id,
					'show_in_table' => $question->show_in_table,
					'label_table' => $question->label_table,
					'show_in_export' => $question->show_in_export,
					'label_export' => $question->label_export,
				];
				$surveysQuestionMetadata->patchEntity($newEntity, $data);
				$surveysQuestionMetadata->save($newEntity);
			}

			if(!empty($item->items)){
				$this->saveItemsQuestionsMetadata($item->items, $surveyId);
			}
		}
	}

	private function saveItemsAnswersData($items, $interviewId){
		$surveysAnswerData = TableRegistry::get('Surveys.SurveysAnswerData');

		foreach($items as $item){
			foreach($item->questions as $question){
				$newEntity = $surveysAnswerData->newEntity();
				$data = [
					'interview_id' => $interviewId,
					'question_id' => $question->id,
					'value' => json_encode($question->answer),
					'type' => $question->type
				];
				if(isset($question->options)){
					$data['options'] = json_encode($question->options);
				}
				if($question->type == 'multiple_choice'){
					$data['value'] = json_encode([
						'answer' => $question->answer,
						'other_answer_check' => $question->other_answer_check,
						'other_answer' => $question->other_answer
					]);
				}

				switch ($question->type) {
					case 'yes_no':
						$a = '';
						if ($question->answer == 'yes') {
							$a = 'Sì';
						} elseif ($question->answer == 'no') {
							$a = 'No';
						}
						$data['final_value'] = $a;
						break;
					case 'date':
						$a = '';
						if ($question->answer) {
							$a = date('d/m/Y', strtotime($question->answer));
						}
						$data['final_value'] = $a;
						break;
					case 'single_choice':
						$a = '';
						$value = $question->answer;
						$options = $question->options;
						if ($value->check) {
							$a = $options[$value->check]->text;
							if ($options[$value->check]->extended && $value->extensions[$value->check]) {
								$a .= ' ('.$value->extensions[$value->check].')';
							}
						}
						$data['final_value'] = $a;
						break;
					case 'multiple_choice':
						$a = [];
						$options = $question->options;
						foreach ($question->answer as $index => $check) {
							$c = '';
							if ($check->check) {
								$c = $options[$index]->text;
								if ($options[$index]->extended && $check->extended) {
									$c .= ' ('.$check->extended.')';
								}
							}
							if (!empty($c)) {
								$a[] = $c;
							}
						}
						if ($question->other_answer_check) {
							$a[] = 'altro: '.$question->other_answer;
						}
						$data['final_value'] = implode(', ', $a);
						break;
					default:
						$data['final_value'] = $question->answer;
				}

				$surveysAnswerData->patchEntity($newEntity, $data);
				$surveysAnswerData->save($newEntity);
			}

			if(!empty($item->items)){
				$this->saveItemsAnswersData($item->items, $interviewId);
			}
		}
	}
}
