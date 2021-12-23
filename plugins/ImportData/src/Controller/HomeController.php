<?php
namespace ImportData\Controller;

use ImportData\Controller\AppController;
use Cake\Core\Configure;

/**
 * ImportData Controller
 */
class HomeController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index(){

		$tables = Configure::read('importDataConfig.Tables');

        $this->set('tables' , $tables);

    }

}
