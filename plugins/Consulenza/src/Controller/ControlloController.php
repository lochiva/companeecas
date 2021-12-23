<?php
namespace Consulenza\Controller;

use Consulenza\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;



/**
 * Home Controller
 *
 * @property \Consulenza\Model\Table\HomeTable $Home */
class ControlloController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Consulenza.Controllo');
        $this->loadComponent('Calendar.Calendar');
    }		

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index','info']);

        $user = $this->request->session()->read('Auth.User');

        if($user['role']!='admin'){ 
            $this->redirect('/calendar');
        }
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $Orders = TableRegistry::get('Consulenza.Orders');
        
    	// ottengo un array con tutti i soci ed ogni attivita di oggi per ogni socio
     	$user_tasks = $this->Controllo->getUserTasks();

        foreach($user_tasks as $key => $user){
            foreach($user['tasks'] as $k => $task){
                $user_tasks[$key]['tasks'][$k]['backgroundColor'] = $this->Calendar->getBackgrounColorTaskLive($task->id);
                $order = $Orders->find('all')->where(array('Orders.id'=>$task->order_id))->contain('Aziende')->toArray();
                $user_tasks[$key]['tasks'][$k]['azienda'] = $order[0]['azienda']['denominazione'];
            }
        }

    	//Passo le variabili al view
    	$this->set('user_tasks' , $user_tasks);         	

    }

}