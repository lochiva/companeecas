<?php
namespace Progest\Controller\Ws;

use Progest\Controller\Ws\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * Ws Offers Controller
 *
 */
class OrdersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Progest.Order');
    }

    public function intModal()
    {
        $personTypes = TableRegistry::get('Progest.PersonTypes')->getList();
        $invoiceTypes = TableRegistry::get('Progest.InvoiceTypes')->getList();
        $services = TableRegistry::get('Progest.Services')->getListCat(1);
        $servicesFlexibility = TableRegistry::get('Progest.ServicesFlexibility')->getList();
        $servicesApl = TableRegistry::get('Progest.ServicesApl')->getList();
        $contactsRole = TableRegistry::get('Aziende.ContattiRuoli')->getList(['active' => 1]);
        $servicesFrequency = TableRegistry::get('Progest.ServicesFrequency')->getList();

        $this->_result = array('response' => 'OK', 'data' => ['person' => $personTypes,
            'invoice'=>$invoiceTypes, 'services' => $services, 'servicesFlexibility'=>$servicesFlexibility,
            'servicesApl'=>$servicesApl, 'contactsRole' => $contactsRole, 'servicesFrequency'=>$servicesFrequency],
            'msg' => "OK");
    }

    public function table($idOrAction = 0,$xls = false)
    {
        $pass['query'] = $this->request->query;
        if($xls){
          $this->Order->tableToExcel($pass,$idOrAction);
          die;
        }
        $orders = $this->Order->getForTable($pass,$idOrAction);

        //debug($orders);
        $out = array('rows'=>[], 'total_rows'=>$orders['tot'] );
        if(!empty($orders['res'])){
          foreach($orders['res'] as $key => $order){
            $button = "";
            $button.= '<div class="btn-group">';
            $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $order['id'] . '" data-toggle="modal" data-target="#myModalOrder"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
            $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
            $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
            $button.= '<ul  style="width:170px !important;" class="dropdown-menu">';
            $button.= '<li><a data-toggle="modal" data-target="#myModalOrder" class="duplicate" href="#" data-id="' . $order['id'] . '" ><i style="margin-right: 7px;" class="fa fa-clone"></i> Proroga/Variazione</a></li>';
            $button.= '<li><a class="delete" href="#" data-id="' . $order['id'] . '" ><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
            $button.= '</ul>';
            $button.= '</div>';
            $button.= '</div>';
            $out['rows'][$key] = [
              htmlspecialchars((!empty($order['ivoice_type'])? $order['ivoice_type'] : '')),
              htmlspecialchars($order['persona']),
              htmlspecialchars($order['note']),
              (!empty($order['start'])?$order['start']->i18nFormat('dd/MM/yyyy') : ''),
              (!empty($order['end'])?$order['end']->i18nFormat('dd/MM/yyyy') : ''),
              (!empty($order['activation'])?$order['activation']->i18nFormat('dd/MM/yyyy') : ''),
              '<span class="badge orderStatusBG-'.$order['id_status'].'">'.htmlspecialchars($order['status']).'</span>',
              $button
            ];
            if($idOrAction === 'all'){
              array_unshift($out['rows'][$key], '<a href="'.Router::url('/aziende/home/info/'.$order['id_azienda']).'">'.$order['azienda'].'</a>');
            }
          }
        }


        $this->_result = $out;

    }

    public function get($id = 0)
    {
        if($id > 0){
            $order = $this->Order->get($id);
            $this->_result = array('response' => 'OK', 'data' => $order, 'msg' => "Order trovato");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }
    }

    public function save($idOrder = 0)
    {
      if($idOrder == 0){
          unset($this->request->data['id']);
      }

      array_walk_recursive($this->request->data, array($this,'trimByReference') );
      $order = $this->Order->saveOrder($this->request->data);

      if ($order) {
          $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
      }else{
          $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
      }
    }

    public function deleteService($id = 0)
    {
        if($id > 0){

            if($this->Order->deleteService($id)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }
    }

    public function deleteContact($id = 0)
    {
        if($id > 0){

            if($this->Order->deleteContact($id)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }
    }
}
