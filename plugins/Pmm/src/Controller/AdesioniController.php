<?php

namespace Pmm\Controller;

use Pmm\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

class AdesioniController extends AppController
{
	public function initialize()
    {
        parent::initialize();

        $this->loadComponent("Pmm.Adesioni");
        $this->loadComponent('Pmm.Profile');
        $this->loadComponent('Pmm.ContrattiPdr');
        $this->loadComponent('Pmm.Utility');
    }


    public function index()
    {
    	$this->set('pos_list',$this->Profile->getPosList());
    	$this->set('pdr_list',$this->ContrattiPdr->getPdrList());
    }

    public function pos($id = "")
    {
        if($id != "")
        { 
            // lo stato deve essere preimpostato a PMM
            $filter = ['filter-pos' => $id, 'filter-status' => Configure::read('localConfig.STATOPMM')];

            if($this->Utility->setFilterInSession(Configure::read('localConfig.adesioni_filter_prefix'),$filter))
            {
                $this->setAction('index');
            }else
            { 
                $this->Flash->set('Si è verificato un errore.');
                $this->redirect($this->referer());
            }

        }else
        {
            $this->redirect($this->referer());       
        }
    }

}