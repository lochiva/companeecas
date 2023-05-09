<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Stampe  (https://www.companee.it)
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
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Aziende Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class StampeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Aziende.Azienda');

        $this->set('title', 'Stampe');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index','info']);

        $this->viewBuilder()->layout('Aziende.stampa');
    }

    
    public function printCover($idAzienda)
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->get($idAzienda);

        $aziendaSede = TableRegistry::get('Aziende.Sedi')
            ->find()
            ->where(['id_azienda' => $idAzienda, 'id_tipo' => 1])
            ->first();

        $this->set('azienda', $azienda);
        $this->set('aziendaSede', $aziendaSede);
    }

}
