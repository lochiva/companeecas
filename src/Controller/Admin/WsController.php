<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class WsController extends AppController
{

    public function isAuthorized($group)
    {
        // Admin can access every action
        if (isset($group['role']) && $group['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

    }

    public function beforeRender(Event $event)
    {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }

    public function tableProvince()
    {
      $this->loadComponent('Luoghi');
      $pass['query'] = $this->request->query;
      $province = $this->Luoghi->getProvinceTable($pass);
      $out = array('rows'=>[], 'total_rows'=>$province['tot'] );
      //debug($province);
      foreach ($people['res'] as $key => $person) {

      }

      $this->_result = $out;
    }
}
