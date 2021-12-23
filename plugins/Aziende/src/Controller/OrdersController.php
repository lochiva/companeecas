<?php
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
