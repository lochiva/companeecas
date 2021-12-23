<?php
namespace Consulenza\Controller;

use Consulenza\Controller\AppController;

/**
 * Home Controller
 *
 * @property \Consulenza\Model\Table\HomeTable $Home */
class NoticeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Consulenza.Notice');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

     $notice = $this->Notice->getMyNotice();

     //print_r($notice);exit;

    //Passo le variabili al view
    $this->set('notice' , $notice);

    }

}
