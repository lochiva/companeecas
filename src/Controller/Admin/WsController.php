<?php
namespace App\Controller\Admin;
################################################################################
#
# Companee :   Ws (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################

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
