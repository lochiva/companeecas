<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;

class StatementsNotificationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Azienda');
    }

    public function isAuthorized($user)
    {
        if($user['role'] == 'admin' || $user['role'] == 'ragioneria') {
            return true;
        }
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $user = $this->Auth->user();
    }

    public function index()
    {
    }

}
