<?php

namespace Calendar\Controller;

use App\Controller\AppController as BaseController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class AppController extends BaseController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $concatUser = TableRegistry::get('Aziende.Contatti')->find()
            ->where(['id_user'=>$this->Auth->user('id')])->first();
        $this->request->session()->write('User.Contact',$concatUser);
        //$this->Auth->allow(['index']);
        $this->set('contactUser',$concatUser);
    }

}
