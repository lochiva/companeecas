<?php
/**
* Controller is a plugin for manage attachment
*
* Companee :    Orders  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
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
class OrdersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Document.Document');
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Order');
        $this->loadComponent('Aziende.Contatti');

        $this->set('title', 'Ordini');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($idAzienda = 0)
    {

        //Carico i dati dell'azienda se mi è stata passata
        $azienda = array();
        $orders = array();
        $contatti = array();
        if($idAzienda != 0){

            $azienda = $this->Azienda->_get($idAzienda);
            //$orders = $this->Order->getOrdersAzienda($idAzienda);
            if($idAzienda !== 'all'){
              $pass['id'] = $idAzienda;
              $pass['tipo'] = 'azienda';

              $contatti = $this->Contatti->getContatti($pass);
            }

        }
        $status = TableRegistry::get('Aziende.OrdersStatus')->getList();


        $this->set('ordersStatus',$status);
        $this->set('idAzienda',$idAzienda);
        $this->set('azienda',$azienda);
        //$this->set('orders',$orders);
        $this->set('contatti',$contatti);

    }

}
