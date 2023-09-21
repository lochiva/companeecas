<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Ws  (https://www.companee.it)
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
namespace Surveys\Controller;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Utility\Inflector;

/**
 * Surveys Controller
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Surveys.Surveys');
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
		if ($user['role'] == 'admin') {
			return true;
		} else {
			$authorizedActions = [
				'user' => [
					'getSurvey', 'getInterviews', 'getInterview', 'saveInterview', 'getInterviewForNewSurvey',
					'getActiveComponentsByInterview', 'getActiveComponentsByQuotation'
				],
				'area_iv' => ['createInterview', 'getInterview', 'saveInterview']
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

        $res = $this->Surveys->getSurveys($pass, $user);
        
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){

            foreach ($res['res'] as $key => $survey) {

 				switch($survey->status){
					case '1':
						$status = '<span class="status-pubblicato">Pubblicato</span>';
						break;
					/* case '2':
						$status = '<span class="status-bozza">Bozza</span>';
						break; */
					case '3':
						$status = '<span class="status-annullato">Annullato</span>';
						break;
					/* case '4':
						$status = '<span class="status-frozen">Pubblicato (congelato)</span>';
						break; */
					default:
						$status = '';
						break;
				}

                $buttons = "";
				$buttons .= '<div class="button-group">';

				if ($survey->status == 3) {
					$buttons .= '<a class="btn btn-xs btn-warning edit-survey" title="Modifica il modello" disabled><i class="fa fa-pencil"></i></a>'; 
				
					$buttons .= '<a class="btn btn-xs btn-danger" style="margin-left: 5px;" data-id="'.$survey->id.'" title="Annulla il modello" disabled><i class="fa fa-trash"></i></a>';
				} else {
					$buttons .= '<a href="'.Router::url('/surveys/surveys/edit?survey='.$survey->id).'" class="btn btn-xs btn-warning edit-survey" title="Modifica il modello"><i class="fa fa-pencil"></i></a>'; 

					$buttons .= '<a class="btn btn-xs btn-danger delete-survey" data-id="'.$survey->id.'" title="Annulla il modello"><i class="fa fa-trash"></i></a>';
				}

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

	public function getSurvey($id = false, $forInterview = false, $idQuotation = null)
	{
		
		$surveys = TableRegistry::get('Surveys.Surveys');

		if($id){
			$survey = $surveys->get($id);
		}else{
			$survey = $surveys->find()->first();
		}

		$surveysChapters = TableRegistry::get('Surveys.SurveysChapters');
		$survey['chapters'] = $surveysChapters->getChaptersBySurvey($survey['id']);

		//Se dati per interview
		if ($forInterview) {
			//Sotituzione placeholders
			$valuePlaceholders = $this->Surveys->getValuePlaceholders($idQuotation);
			foreach($survey['chapters'] as $chapter){
				$chapter = $this->Surveys->replacePlaceholdersTexts($chapter, $valuePlaceholders);
			}

			//Dati per schede tecniche
			$dataSheetsInfo = [];
			foreach($survey['chapters'] as $chapter){
				$dataSheetsInfo = $this->Surveys->getDataSheetsInfo($chapter, $dataSheetsInfo);
			}
			$survey['data_sheets_info'] = $dataSheetsInfo;
		}

		if($survey){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $survey;
			$this->_result['msg'] = 'Modello recuperato correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero del modello.';
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
			$this->_result['msg'] = 'Stati modello recuperati con sucesso.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero degli stati del modello.';
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
			$this->_result['msg'] = "Modello salvato con successo.";
		}else{
			$message = "Errore nel salvataggio del modello."; 
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
            $this->_result['msg'] = "Modello annullato con successo.";
        }else{
			$message = "Errore nell'annullamento del modello."; 
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

		$interview = $interviews->get($id, ['contain' => 'SurveysInterviewsGuests']);

		$survey = TableRegistry::get('Surveys.Surveys')->get($interview['id_survey']);
		$interview['survey_version'] = $survey['version'];

		$surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
		$interview['answers'] = $surveysAnswers->getAnswersByInterview($id);

		//echo "<pre>"; print_r($interview); die();

		//Sotituzione placeholders
		$valuePlaceholders = $this->Surveys->getValuePlaceholders($interview);
		foreach($interview['answers'] as $answer){
            $answer = $this->Surveys->replacePlaceholdersTexts($answer, $valuePlaceholders);
        }

		if($interview){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $interview;
			$this->_result['msg'] = 'Documento recuperato correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero del documento.';
		}		
	}

	public function getInterviewForNewSurvey($id)
	{
		$interviews = TableRegistry::get('Surveys.SurveysInterviews');
		$interview = $interviews->get($id, ['contain' => 'SurveysInterviewsGuests']);

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

		//Sotituzione placeholders
		$valuePlaceholders = $this->Surveys->getValuePlaceholders($interview);
		foreach($interview['answers'] as $answer){
            $answer = $this->Surveys->replacePlaceholdersTexts($answer, $valuePlaceholders);
        }

		$interview['title'] = $survey['title'];
		$interview['subtitle'] = $survey['subtitle'];
		$interview['description'] = $survey['description'];
		$interview['version'] = $survey['version'];
		$interview['survey_version'] = $survey['version'];

		if($interview){
			$this->_result['response'] = "OK";
			$this->_result['data'] = $interview;
			$this->_result['msg'] = 'Documento recuperato correttamente.';
		}else{
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore nel recupero del Documento.';
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

			$this->_result['response'] = "OK";
			$this->_result['data'] = $entity->id;
			$this->_result['msg'] = "Documento salvato con successo.";
		}else{
			$message = "Errore nel salvataggio del Documento.";  
			foreach($entity->errors() as $field => $errors){ 
				foreach($errors as $rule => $msg){ 
					$message .= "\n" . $field.': '.$msg;
				}
			} 
			$this->_result['response'] = "KO";
			$this->_result['msg'] = $message;
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

	public function saveImagePath($url = false)
	{
		$file = $this->request->data['file'];

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
		
		$basePath = WWW_ROOT.Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');
        $uploadPath = date('Y').DS.date('m');
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
			$type = pathinfo($basePath.$uploadPath.DS.$fileName, PATHINFO_EXTENSION);
			$image = file_get_contents($basePath.$uploadPath.DS.$fileName);
			$this->_result['data'] = 'data:image/' . $type . ';base64,' . base64_encode($image);
			/*$baseUrl = $_SERVER['HTTP_ORIGIN'].Router::url('/');
			$this->_result['data'] = $baseUrl.Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH').$uploadPath.DS.$fileName;*/
		}
        $this->_result['msg'] = "Salvataggio avvenuto con successo.";
	}

	public function viewImage($path)
	{
		$basePath = WWW_ROOT.Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');

		if(file_exists($basePath.$path)){
			$pathArray = explode('/', $path);
			$fileName = end($pathArray);
			$this->response->file($basePath.$path , array(
				'download'=> false,
				'name'=> $fileName
			));
			return $this->response;
        }else{
            $this->_result['msg'] = 'Il file richiesto non esiste.';
        }
	}

	public function deleteImage()
	{
		$path = $this->request->data['path'];
		$basePath = WWW_ROOT.DS.Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');
		
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
			$this->_result['msg'] = "Errore nella clonazione del modello.";
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
			$this->_result['msg'] = "Stato del documento impostato su 'Firmata'.";
		}else{
			$this->_result['msg'] = "Errore. Stato del documento non impostato.";
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
				$this->_result['msg'] = "Errore nella clonazione del documento. Salvataggio sezioni non riuscito.";
			}else{
				$this->_result['response'] = "OK";
				$this->_result['msg'] = "Clonazione del documento avvenuta con successo.";
			}
		}else{
			$this->_result['msg'] = "Errore nella clonazione del documento.";
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

	public function searchDataSheet($search = "") 
    {
        $dataSheets = TableRegistry::get('Building.DataSheets')->searchDataSheets($search);

		if ($dataSheets) {
			$this->_result['response'] = "OK";
			$this->_result['data'] = $dataSheets;
			$this->_result['msg'] = 'Schede tecniche recuperate con sucesso.';
		} else {
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Nessuna scheda tecnica trovata.';
		}
	}

	public function getActiveComponentsByInterview($interviewId = '')
	{
		if (!empty($interviewId)) {
			$interview = TableRegistry::get('Surveys.SurveysInterviews')->get($interviewId);
			$activeComponents = TableRegistry::get('Building.ComponentQuotation')->getComponentIdsByQuotation($interview['id_quotation']);
			
			$this->_result['response'] = "OK";
			$this->_result['data'] = $activeComponents;
			$this->_result['msg'] = 'Componenti attivi recuperati con successo.';
		} else {
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore: ID mancante.';
		}
	}

	public function getActiveComponentsByQuotation($quotationId = '')
	{
		if (!empty($quotationId)) {
			$activeComponents = TableRegistry::get('Building.ComponentQuotation')->getComponentIdsByQuotation($quotationId);

			$this->_result['response'] = "OK";
			$this->_result['data'] = $activeComponents;
			$this->_result['msg'] = 'Componenti attivi recuperati con successo.';
		} else {
			$this->_result['response'] = "KO";
			$this->_result['msg'] = 'Errore: ID mancante.';
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
					'type' => $question->type
				];
				if(isset($question->options)){
					$data['options'] = json_encode($question->options);
				}

				//value
				switch ($question->type) {
					case 'fixed_text':
						$data['value'] = json_encode($question->value);
						break;
					case 'image':
						$data['value'] = json_encode(['path' => $question->path, 'caption' => $question->caption]);
						break;
					case 'multiple_choice':
						$data['value'] = json_encode([
							'answer' => $question->answer,
							'other_answer_check' => $question->other_answer_check,
							'other_answer' => $question->other_answer
						]);
						break;
					case 'page_break':
						$data['value'] = '';
						break;
					default:
						$data['value'] = json_encode($question->answer);
				}

				//final value
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
					case 'fixed_text':
						$data['final_value'] = $question->value;
						break;
					case 'image':
						$data['final_value'] = $question->path;
						break;
					case 'page_break':
						$data['value'] = '';
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

	public function createInterview()
	{
		$this->request->allowMethod(['POST']);

		extract($this->request->data);

		$survey = TableRegistry::get('Surveys.Surveys')->get($survey_id, ['contain' => 'SurveysChapters']);
		//echo "<pre>"; print_r($survey); die();

		$user = $this->request->session()->read('Auth.User');
		
		$interviewsTable = TableRegistry::get('Surveys.SurveysInterviews');

		$int['id_survey']= $survey_id;
		$int['id_user'] = $user['id'];
		$int['title'] = $survey->title;
		$int['subtitle'] = $survey->subtitle;
		$int['description'] = $survey->description;
		$int['version'] = $survey->version;
		$int['status'] = 1;
		$int['guest'] = ['guest_id' => $guest_id];

		for ($i = 0; $i < count($survey->chapters); $i++) {
			$int['answers'][$i] = [
				'chapter' => $survey->chapters[$i]->chapter,
				'chapter_data' => $survey->chapters[$i]->chapter_data,
				'color' => $survey->chapters[$i]->color,
				'group_id' => $survey->chapters[$i]->group_id,
			];
		}

		$entity = $interviewsTable->newEntity($int, ['associated' => ['SurveysInterviewsGuests', 'SurveysAnswers']]);

		//echo "<pre>"; print_r($entity); die();
		if($interviewsTable->save($entity)){
			$entity['interview_id'] = $entity->id;
			$this->_result['response'] = "OK";
			$this->_result['data'] = $entity;
			$this->_result['msg'] = "Documento salvato con successo.";
		}else{
			$message = "Errore nel salvataggio del Documento.";  
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
