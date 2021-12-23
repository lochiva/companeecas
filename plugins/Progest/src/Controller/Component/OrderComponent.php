<?php

namespace Progest\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class OrderComponent extends Component
{
    protected $tablePeople;
    public $components = ['Excel'];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->tablePeople = TableRegistry::get('Progest.People');
    }

    public function getForTable($pass = array(), $idOrAction = 0)
    {

        $ordersTable = TableRegistry::get('Progest.Orders');
        $opt = array();
        $toRet = array();
        $opt['contain'] = ['Contatti','OrdersStatus','People','InvoiceTypes'];
        $columns = [
          0 => ['val' => 'InvoiceTypes.name', 'type' => 'text'],
          1 => ['val' => 'persona', 'type' => 'text', 'having' => 1],
          2 => ['val' => 'Orders.note', 'type' => 'text'],
          3 => ['val' => 'Orders.start_date', 'type' => 'date'],
          4 => ['val' => 'Orders.end_date' , 'type' => 'date'],
          5 => ['val' => 'Orders.activation_date' , 'type' => 'date'],
          6 => ['val' => 'OrdersStatus.name' , 'type' => 'text']
        ];

        $opt['fields'] = [
          'id' => 'Orders.id',
          'note' => 'Orders.note',
          'id_azienda' => 'Orders.id_azienda',
          'start' => 'Orders.start_date',
          'end' => 'Orders.end_date',
          'activation' => 'Orders.activation_date',
          'persona' => 'CONCAT(People.surname,SPACE(1),People.name)',
          'ivoice_type' => 'InvoiceTypes.name',
          'status' => 'OrdersStatus.name',
          'Orders.id_status'
        ];

        if($idOrAction > 0 && $idOrAction !== 'all' ){
          $opt['conditions'] = ['Orders.id_azienda' => $idOrAction];
          $opt['order'] = ['Orders.start_date' => 'DESC','InvoiceTypes.ordering' => 'ASC' ];
        }else{
          $opt['order'] = ['Orders.start_date' => 'DESC','Aziende.denominazione' => 'ASC','InvoiceTypes.ordering' => 'ASC' ];
          $opt['contain'][] = 'Aziende';
          $opt['fields']['azienda'] = 'Aziende.denominazione';
          array_unshift($columns, ['val' => 'Aziende.denominazione', 'type'=>'text']);
        }

        $toRet['res'] = $ordersTable->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $ordersTable->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;
    }

    public function tableToExcel($pass = array(), $idOrAction = 0)
    {
          $pass['query']['size'] = 'all';
          $res = $this->getForTable($pass,$idOrAction);
          $data = array();

          foreach($res['res'] as $key =>  $order){

              $data[$key] = [
                $order['name'],$order['persona'],$order['note'],
                $order['contatto'],\PHPExcel_Shared_Date::PHPToExcel($order['created']),$order['status'],

              ];
              if($idOrAction === 'all'){
                array_unshift($data[$key], $order['azienda']);
              }
          }
          $opt = array('title' => 'Lista buoni Ordine','filter'=> true, 'columns' => []);
          if($idOrAction === 'all'){
              $opt['columns']['Committente'] = 'string';
          }
          $opt['columns'] = array_merge($opt['columns'],['Oggetto'=>'string',
            'Persona'=>'string','Note'=>'string','Contatto di Riferimento'=>'string',
            'Creato'=>'date','Stato'=>'string']);

          $this->Excel->generateExcel($data,$opt);
          $this->Excel->download();
    }

    public function get($id)
    {
        $ordersTable = TableRegistry::get('Progest.Orders');
        return $ordersTable->get($id,['contain' => ['Aziende', 'ServicesOrders','ContactsOrders']]);
    }

    public function saveOrder($dati)
    {
        $ordersTable = TableRegistry::get('Progest.Orders');
        if(!empty($dati['id'])){
            $order = $ordersTable->get($dati['id']);
        }else{
            $order = $ordersTable->newEntity();
        }
        foreach($dati as $key=>$val){
            if(strpos($key, 'date') !== false && !empty($val)){
              $dati[$key] = Time::createFromFormat('d/m/Y',$val);
            }
        }
        if($order->id_status != $dati['id_status']){
            $statusUpdate = $dati['id_status'];
            if($dati['id_status'] == 2){
                $dati['closed'] = Time::now();
            }
        }
        $order = $ordersTable->patchEntity($order, $dati);
        $res = $ordersTable->save($order);

        if($res){
           if(!empty($statusUpdate)){
               TableRegistry::get('Aziende.OrdersHistory')->saveOrderChange($res);
           }
           if(!empty($dati['old_id'])){
               $ordersTable->updateAll(['id_status' => 2],['id' => $dati['old_id']]);
           }
        }

        return $res;

    }

    public function deleteService($id)
    {
        $serviceTable = TableRegistry::get('Progest.ServicesOrders');
        $service = $serviceTable->get($id);
        return $serviceTable->delete($service);
    }

    public function deleteContact($id)
    {
        $contactsTable = TableRegistry::get('Progest.ContactsOrders');
        $contact = $contactsTable->get($id);
        return $contactsTable->delete($contact);
    }

}
