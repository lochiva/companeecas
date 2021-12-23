<?php
namespace Surveys\Controller;

use Surveys\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Surveys Controller
 *
 * @property \Surveys\Model\Table\SurveysTable $Surveys
 *
 * @method \Surveys\Model\Entity\Survey[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SurveysController extends AppController
{

    public function isAuthorized($user = null)
    {
        return true;
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        if($this->request->session()->read('Auth.User.role') != 'admin'){
			$this->Flash->error('Accesso negato. Non sei autorizzato.');
            return $this->redirect('/');
        }

        $survey = TableRegistry::get('Surveys.Surveys')->find()->first();

        if($survey){
            $this->redirect('/surveys/surveys/edit?survey='.$survey['id']);
        }else{
            $this->redirect('/surveys/surveys/add');
        }

    }

    public function add()
    {
        if($this->request->session()->read('Auth.User.role') != 'admin'){
			$this->Flash->error('Accesso negato. Non sei autorizzato.');
            return $this->redirect('/');
        }
    }

    public function edit()
    {
        if($this->request->session()->read('Auth.User.role') != 'admin'){
			$this->Flash->error('Accesso negato. Non sei autorizzato.');
            return $this->redirect('/');
        }

        $this->render('add');
    }

    public function interviews($surveyId, $managingEntityId = '', $structureId = '')
    {
        if($this->request->session()->read('Auth.User.role') != 'admin'){
            $this->set('managingEntityId', $managingEntityId);

            $structure = TableRegistry::get('Aziende.Sedi')->get($structureId);
            $this->set('structure', $structure);

            $this->render('interviews_user');
        }else{
            $this->set('surveyId', $surveyId);
        }
    }

    public function answers()
    {
    
    }

    public function managingEntities()
    {

    }

    public function structures($idManagingEntity)
    {
        $managingEntity = TableRegistry::get('Aziende.Aziende')->get($idManagingEntity);

        $this->set('managingEntity', $managingEntity);
    }

    public function interviewPdf($id){

        $interviews = TableRegistry::get('Surveys.SurveysInterviews');

        $interview = $interviews->get($id);

        $surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
        $interview['answers'] = $surveysAnswers->getAnswersByInterview($id);

        $this->viewBuilder()->layout('default');

        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $interviewTitle = str_replace(' ', '_', $interview['title']);

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'download' => true,
                'filename' => 'Ispezione_' . $interviewTitle . '.pdf'
            ]
        ]);

        $viewVars = ['interview' => $interview];

        $this->set($viewVars);

        setcookie('downloadStarted', '1', false, '/');
    }

    public function chapters()
    {
        $placeholders = [];
        //$placeholders = TableRegistry::get('Surveys.SurveysPlaceholders')->find()->toArray();

        $this->set('placeholders', $placeholders);
    }

    public function chapterPreview($id)
    {
        $chapters = TableRegistry::get('Surveys.SurveysChaptersContents');

        $chapter = $chapters->get($id);	

        $this->viewBuilder()->layout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $viewVars = ['chapter' => $chapter];

        $this->set($viewVars);
    }

}
