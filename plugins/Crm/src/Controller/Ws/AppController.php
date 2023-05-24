<?php
/**
* Crm is a plugin for manage attachment
*
* Companee :    App  (https://www.companee.it)
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
