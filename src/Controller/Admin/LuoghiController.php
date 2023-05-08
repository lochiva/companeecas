<?php
namespace App\Controller\Admin;
################################################################################
#
# Companee :   Luoghi (https://www.companee.it)
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

class LuoghiController extends AppController
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
        // Allow Groups to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['logout']);
    }

    public function index()
    {
      
    }
}
