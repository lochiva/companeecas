<?php

namespace Leads\Controller;

use Leads\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Leads.Ensemble');
        $this->loadComponent('Leads.Interview');

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
		if($user['role'] == 'admin'){
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

    public function getEnsembles()
    {
        $pass['query'] = $this->request->query;

		if(isset($pass['query']['filter'][2])){
			if($pass['query']['filter'][2] == 'Sì'){
				$pass['query']['filter'][2] = 1;
			}elseif($pass['query']['filter'][2] == 'No'){
				$pass['query']['filter'][2] = 0;
			}
		}

        $res = $this->Ensemble->getEnsembles($pass);
  
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){ 
            
            foreach ($res['res'] as $key => $ensemble) { 

                $buttons = "";
                $buttons .= '<div class="button-group text-center">';
                $buttons .= '<a class="btn btn-xs btn-warning edit-ensemble" href="#" data-id="' . $ensemble->id . '" title="Modifica ensemble" data-toggle="modal" data-target="#modalEnsemble"><i class="fa fa-pencil"></i></a>';
                $buttons .= '<a class="btn btn-xs btn-primary manage-questions" href="#" data-id="' . $ensemble->id . '" title="Gestisci domande" data-toggle="modal" data-target="#modalQuestions"><i class="fa fa-question"></i></a>';
                $buttons .= '<a class="btn btn-xs btn-danger delete-ensemble" href="#" data-id="' . $ensemble->id . '" title="Elimina ensemble" ><i class="fa fa-trash"></i></a>';
                $buttons .= '</div>';
                
                if($ensemble['active']){
                    $active = '<td class="text-center"><i class="glyphicon glyphicon-ok-sign" style="color:green; font-size:24px;" title="Attivo"></i></td>';
                }else{
                    $active = '<td class="text-center"><i class="glyphicon glyphicon-remove-sign" style="color:red; font-size:24px;" title="Non attivo"></i></td>';
                }

                $out['rows'][] = array(
                    htmlspecialchars($ensemble['name']),
                    htmlspecialchars($ensemble['description']),
                    $active,
                    $ensemble['total'],
                    $buttons
                );
            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
    }

    public function getEnsemble($id = "")
    {
        if(!empty($id)){
            $ensemble = TableRegistry::get('Leads.LeadsEnsembles')->get($id);

            $this->_result['response'] = 'OK';
            $this->_result['data'] = $ensemble;
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Impossibile recuperare l'ensemble: ID mancante.";
        }
    }

    public function saveEnsemble()
    {
        $data = $this->request->data;

        if(!empty($data['active']) && $data['active'] == 'on' ){
            $data['active'] = 1;
        }else{
            $data['active'] = 0;
        }

        $ensembles = TableRegistry::get('Leads.LeadsEnsembles');

        if(empty($data['id'])){
            $entity = $ensembles->newEntity();
        }else{
            $entity = $ensembles->get($data['id']);
        }

        $ensembles->patchEntity($entity, $data);

        if($ensembles->save($entity)){
            $this->_result['response'] = 'OK';
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Errore nel salvataggio dell'ensemble";
        }
    }

    public function deleteEnsemble()
    {
        $id = $this->request->data['id_ensemble'];

        $used = $this->Interview->verifyUsedEnsemble($id);

        if(!$used){

            $ensembles = TableRegistry::get('Leads.LeadsEnsembles');

            $entity = $ensembles->get($id);

            $entity->deleted = '1';

            if($ensembles->save($entity)){
                $this->_result['response'] = 'OK';
            }else{
                $this->_result['response'] = 'KO'; 
                $this->_result['msg'] = "Errore nell'eliminazione dell'ensemble";
            }
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Impossibile eliminare l'ensemble. Le domande sono utilizzate in un'intervista.";
        }
    }

    public function getQuestions($idEnsemble = "")
    {
        if(!empty($idEnsemble)){
            $questions = TableRegistry::get('Leads.LeadsQuestions')->find()
                    ->where(['LeadsQuestions.id_ensemble' => $idEnsemble])
                    ->contain(['QuestionTypes'])
                    ->order(['LeadsQuestions.ordering ASC'])
                    ->toArray();

            $this->_result['response'] = 'OK';
            $this->_result['data'] = $questions;
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Impossibile recuperare le domande: ID ensemble mancante.";
        }
    }

    public function getQuestion($id = "")
    {
        if(!empty($id)){
            $question = TableRegistry::get('Leads.LeadsQuestions')->get($id, ['contain' => ['QuestionTypes']]);

            $this->_result['response'] = 'OK';
            $this->_result['data'] = $question;
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Impossibile recuperare la domanda: ID mancante.";
        }
    }

    public function saveQuestion()
    {
        $data = $this->request->data;

        if(!empty($data['active']) && $data['active'] == 'on' ){
            $data['active'] = 1;
        }else{
            $data['active'] = 0;
        }

        $questions = TableRegistry::get('Leads.LeadsQuestions');

        if(empty($data['id'])){
            $entity = $questions->newEntity();

            $ordering = $questions->getLastOrdering($data['id_ensemble']);
            $entity->ordering = $ordering + 1;
        }else{
            $entity = $questions->get($data['id']);
        }

        $questions->patchEntity($entity, $data);

        if($questions->save($entity)){
            $this->_result['response'] = 'OK';
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Errore nel salvataggio della domanda";
        }
    }

    public function deleteQuestion()
    {
        $id = $this->request->data['id_question'];

        $questions = TableRegistry::get('Leads.LeadsQuestions');

        $entity = $questions->get($id);

        $used = $this->Interview->verifyUsedEnsemble($entity->id_ensemble);

        if(!$used){

            $entity->ordering = '0';
            $entity->deleted = '1';

            if($questions->save($entity)){
                $this->_result['response'] = 'OK';
            }else{
                $this->_result['response'] = 'KO'; 
                $this->_result['msg'] = "Errore nell'eliminazione della domanda";
            }
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Impossibile eliminare la domanda perchè utilizzata in un'intervista.";
        }
    }

    public function autocompleteQuestionType()
    {
        if(empty($this->request->query['q'])){
            $text = '';
        }else{
            $text = $this->request->query['q'];
        }     

        $res = $this->Ensemble->getQuestionTypeAutocomplete($text);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
    }

    public function setQuestionsOrdering()
    {
        $data = $this->request->data;
   
        $questions = TableRegistry::get('Leads.LeadsQuestions');

        $error = false;

        foreach($data['question'] as $order => $id){
            $entity = $questions->get($id);

            $entity->ordering = $order + 1;
    
            if(!$questions->save($entity)){
                $error = true;
            }
        }

        if(!$error){
            $this->_result['response'] = 'OK';
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Errore nel salvataggio dell'ordinamento delle domande";
        }
    }

    public function getInterviews()
    {
        $pass['query'] = $this->request->query;

        $res = $this->Interview->getInterviews($pass);
 
        $out['total_rows'] = $res['tot'];

        if(!empty($res['res'])){ 
            
            foreach ($res['res'] as $key => $interview) { 

                $buttons = '<div class="button-group text-center">';
                $buttons .= '<a class="btn btn-xs btn-warning edit-headers-interview" data-id="'.$interview->id.'" title="Modifica intestazione intervista" ><i class="fa fa-pencil"></i></a>';
                $buttons .= '<a class="btn btn-xs btn-info edit-interview" href="'.Router::url('/admin/leads/interview/answers/'.$interview->id).'" title="Compila intervista" ><i class="fa fa-pencil-square-o"></i></a>';
                $buttons .= '<a class="btn btn-xs btn-danger delete-interview" href="#" data-id="' . $interview->id . '" title="Elimina intervista" ><i class="fa fa-trash"></i></a>';
                $buttons .= '</div>';

                $out['rows'][] = array(
                    htmlspecialchars($interview['a']['denominazione']),
                    htmlspecialchars($interview['contatto']),
                    htmlspecialchars($interview['le']['name']),
                    htmlspecialchars($interview['name']),
                    $interview['created']->format('d/m/Y'),
                    $buttons
                );
            }

            $this->_result = $out;

        }else{

            $this->_result = array();
        }
    }

    public function getInterview($id = "")
    {
        if(!empty($id)){
            $interview = TableRegistry::get('Leads.LeadsInterviews')->get($id, ['contain' => ['Azienda', 'Contatti', 'Ensemble']]);

            $this->_result['response'] = 'OK';
            $this->_result['data'] = $interview;
            $this->_result['msg'] = "Intervista recuperata correttamente.";
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Impossibile recuperare l'intervista: ID mancante.";
        }
    }

    public function autocompleteEnsemble()
    {
        if(empty($this->request->query['q'])){
            $text = '';
        }else{
            $text = $this->request->query['q'];
        }     

        $res = $this->Ensemble->getEnsembleAutocomplete($text);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
    }

    public function autocompleteContatti($aziendaId)
    {
        if(empty($this->request->query['q'])){
            $text = '';
        }else{
            $text = $this->request->query['q'];
        }     

        $contacts = TableRegistry::get('Aziende.Contatti');
        $res = $contacts->find('all')
                    ->select(['id' => 'id', 'text' => 'CONCAT(cognome, " ", nome)'])
                    ->where(['CONCAT(cognome, " ", nome) LIKE' =>'%'.$text.'%', 'id_azienda' => $aziendaId, 'deleted' => 0])
                    ->order(['CONCAT(cognome, " ", nome)'=>'ASC'])
                    ->toArray();

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Elenco risultati.");
    }

    public function saveInterview()
    {
        $data = $this->request->data;

        $interviews = TableRegistry::get('Leads.LeadsInterviews');

        if(empty($data['id'])){
            $entity = $interviews->newEntity();
        }else{
            $entity = $interviews->get($data['id']);
        }

        $interviews->patchEntity($entity, $data);

        if($interviews->save($entity)){
            $this->_result['response'] = 'OK'; 
            $this->_result['data'] = $entity->id; 
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Errore nel salvataggio dell'intervista";
        }
    }

    public function deleteInterview()
    {
        $id = $this->request->data['id_interview'];

        $interviews = TableRegistry::get('Leads.LeadsInterviews');

        $entity = $interviews->get($id);

        $entity->deleted = '1';

        if($interviews->save($entity)){
            $this->_result['response'] = 'OK';
        }else{
            $this->_result['response'] = 'KO'; 
            $this->_result['msg'] = "Errore nell'eliminazione dell'intervista";
        }
    }

    public function deleteAnswerFile()
    {
        $idAnswer = $this->request->data['id'];

        $answers = TableRegistry::get('Leads.LeadsAnswers');
        $answer = $answers->get($idAnswer); 

        $answer->question_answer = '';

        if($answers->save($answer)){
            $this->_result['response'] = "OK";
            $this->_result['msg'] = 'File eliminato con successo.';
        }else{
            $this->_result['response'] = "KO";
            $this->_result['msg'] = 'Errore nell\'eliminazione del file.';
        }
    }

    public function downloadAnswerFile($idAnswer)
    {
        $answers = TableRegistry::get('Leads.LeadsAnswers');
        $answer = $answers->get($idAnswer); 
		
		$basePath = ROOT.DS.Configure::read('dbconfig.leads.INTERVIEWS_UPLOAD_PATH');
        $uploadPath = $basePath.$answer['question_answer'];

        $fileArray = array_reverse(explode('/', $uploadPath));
        $fileName = $fileArray[0];

        if(file_exists($uploadPath)){
            //download file
            $this->response->file($uploadPath , array(
                'download'=> true,
                'name'=> $fileName
            ));
            setcookie('downloadStarted', '1', false, '/');
            return $this->response;
        }else{
            setcookie('downloadStarted', '1', false, '/');
            $this->Flash->set('Il file richiesto non esiste.', ['element' => 'error']);
            $this->redirect('/admin/leads/interview/answers/'.$answer['id_interview']);
        }
    }
}
