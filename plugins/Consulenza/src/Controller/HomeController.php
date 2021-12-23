<?php
namespace Consulenza\Controller;

use Consulenza\Controller\AppController;
use Cake\Event\Event;


/**
 * Home Controller
 *
 * @property \Consulenza\Model\Table\HomeTable $Home */
class HomeController extends AppController
{

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


       
    }

}
