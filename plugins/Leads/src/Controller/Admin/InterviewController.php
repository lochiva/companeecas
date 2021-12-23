<?php

namespace Leads\Controller\Admin;

use Leads\Controller\Admin\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

class InterviewController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Leads.Interview');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('default');
    }

    public function home()
    {
        
    }

    public function answers($idInterview)
    {
        if($this->request->is('post')){
            $answers = TableRegistry::get('Leads.LeadsAnswers');
            
            $data = $this->request->data;

            $error = false;
            foreach($data as $idQuestion => $answer){
                $entity = $answers->find()->where(['id_interview' => $idInterview, 'id_question' => $idQuestion])->first();

                if(!$entity){
                    $entity = $answers->newEntity();

                    $entity->id_interview = $idInterview;
                    $entity->id_question = $idQuestion;
                }

                if(is_array($answer)){
                    if(!empty($answer['tmp_name'])){
                        $answer = $this->Interview->uploadAnswerFile($answer);
                    }else{
                        $answer = '';
                    }
                }

                $entity->question_answer = $answer;

                if(!$answers->save($entity)){
                    $error = true;
                }
            }

            if(!$error){
                $this->Flash->success('Intervista salvata con successo.');
                return $this->redirect(['action' => 'home']);
            }else{
                $this->Flash->error('Errore nel salvataggio dell\'intervista. Una o più domande non sono state salvate correttamente.');
            }
        }

        $interview = TableRegistry::get('Leads.LeadsInterviews')->get($idInterview);
        $questions = TableRegistry::get('Leads.LeadsQuestions')->getQuestionsEnsemble($interview->id_ensemble);
        $answers = TableRegistry::get('Leads.LeadsAnswers')->getAnswersInterview($idInterview);

        $this->set('interview', $interview);
        $this->set('questions', $questions);
        $this->set('answers', $answers);
    }

}
