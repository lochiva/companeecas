<?php

namespace Pmm\Controller;

use Pmm\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

class AjaxController extends AppController
{
	public function initialize()
    {
        parent::initialize();

        $this->loadComponent("Pmm.Adesioni");
				$this->loadComponent("Pmm.POS");
        $this->loadComponent("Pmm.Utility");

    }

    public function beforeFilter(Event $event)
    {
    	parent::beforeFilter($event);

        $this->autoRender = false;

    }


    public function index()
    {
    	$this->redirect('/');
    }

    public function getAdesioni()
    {
    	//echo "<pre>";print_r($this->request->query);die;

    	echo json_encode($this->Adesioni->getAdesioniForTable($this->request->query));
        die;
    }

		public function getPOS()
		{
			echo json_encode($this->POS->getPOSForTable($this->request->query));
			die;
		}


    public function applyFiltersAdesioni()
    {
       echo $this->Utility->setFilterInSession(Configure::read('localConfig.adesioni_filter_prefix'),$this->request->data);
       die;
    }

}
