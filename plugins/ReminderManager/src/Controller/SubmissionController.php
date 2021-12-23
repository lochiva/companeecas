<?php
namespace ReminderManager\Controller;

use ReminderManager\Controller\AppController;
use Cake\Core\Configure;

/**
 * Submission Controller
 *
 * @property \ReminderManager\Model\Table\SubmissionTable $Submission */
class SubmissionController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

    }

    public function newSchedini(){

    }

    public function detail($idSubmission = 0){

      $attribute = "";

      if(!is_numeric($idSubmission)){
        $attribute = $idSubmission;
        $idSubmission = 0;
      }

      $this->set('idSubmission', $idSubmission);
      $this->set('attribute', $attribute);

    }
}
