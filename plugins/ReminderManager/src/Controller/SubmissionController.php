<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Submission  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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
