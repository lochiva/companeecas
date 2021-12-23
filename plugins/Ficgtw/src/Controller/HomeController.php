<?php
namespace Ficgtw\Controller;

use Ficgtw\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(array('index','add','edit','delete','history','view','viewRev'));
    }



    public function index()
    {



    }


}
