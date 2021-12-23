<?php
namespace Crm\Controller\Ws;

use Cake\Routing\Router;
use Crm\Controller\AppController as BaseController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Scadenzario Controller
 *
 * @property \Scadenzario\Model\Table\ScadenzarioTable $Scadenzario
 */
class AppController extends BaseController
{

    public function initialize()
    {
        parent::initialize();
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
}
