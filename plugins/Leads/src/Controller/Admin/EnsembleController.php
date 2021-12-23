<?php

namespace Leads\Controller\Admin;

use Leads\Controller\Admin\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;

class EnsembleController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('default');
    }

    public function manage()
    {
        
    }

}
