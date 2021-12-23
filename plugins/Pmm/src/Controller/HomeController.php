<?php
namespace Pmm\Controller;

use Pmm\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Home Controller
 *
 * @property \Calendar\Model\Table\HomeTable $Home
 */
class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->redirect('/');
    }

  /**
   * [provvigioni description]
   * @return [type] [description]
   */
    public function provvigioni()
    {
        $anni = Configure::read('localConfig.provvigioni.anni');
        $mesi = Configure::read('localConfig.provvigioni.mesi');

        $this->set('anni',$anni);
        $this->set('mesi',$mesi);
    }

}
