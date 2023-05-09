<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Order  (https://www.companee.it)
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
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class OrderComponent extends Component
{
    public function getOrdersAzienda($id, $limit = 50, $selectedId = '')
    { 
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->find('all')
                    ->where([
                        'Orders.id_azienda' => $id, 
                        'OR' => [
                            'OrdersStatus.selectable' => '1',
                            'Orders.id' => $selectedId
                        ]
                    ])
                    ->order(['Orders.id' => 'DESC'])
                    ->limit($limit)
                    ->contain(['Contatti', 'OrdersStatus'])
                    ->toArray();
    }

    public function getTotOrders()
    {
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->find('all')->count();
    }

    public function _newEntity(){
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->newEntity();
    }

    public function _patchEntity($doc,$request){
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->patchEntity($doc,$request);
    }

    public function _save($doc){
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->save($doc);
    }

    public function _get($id){
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->get($id,['contain' => 'Aziende']);

    }

    public function _delete($doc){
        $ordersTable = TableRegistry::get('Aziende.Orders');
        return $ordersTable->softdelete($doc);
    }

    public function getOrders($pass = array(), $idOrAction = 0){

        $ordersTable = TableRegistry::get('Aziende.Orders');
        $opt = array();
        $toRet = array();
        $opt['contain'] = ['Contatti','OrdersStatus'];
        $columns = [
          0 => ['val' => 'Orders.name', 'type' => 'text'],
          1 => ['val' => 'Orders.note', 'type' => 'text'],
          2 => ['val' => 'contatto', 'type' => 'text', 'having' => 1],
          3 => ['val' => 'Orders.created', 'type' => 'text'],
          4 => ['val' => 'Orders.closed' , 'type' => 'text'],
          5 => ['val' => 'OrdersStatus.name' , 'type' => 'text']
        ];

        $opt['fields'] = [
          'id' => 'Orders.id',
          'name' => 'Orders.name',
          'note' => 'Orders.note',
          'contatto' => 'CONCAT(Contatti.Nome,SPACE(1),Contatti.Cognome)',
          'created' => 'Orders.created',
          'id_azienda' => 'Orders.id_azienda',
          'closed' => 'Orders.closed',
          'status' => 'OrdersStatus.name',
          'id_status' => 'Orders.id_status'
        ];

        if($idOrAction > 0 && $idOrAction !== 'all' ){
          $opt['conditions'] = ['Orders.id_azienda' => $idOrAction];
        }else{
          $opt['contain'][] = 'Aziende';
          $opt['fields']['azienda'] = 'Aziende.denominazione';
          array_unshift($columns, ['val' => 'Aziende.denominazione', 'type'=>'text']);
        }

        $toRet['res'] = $ordersTable->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $ordersTable->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;


    }

    public function getOrdersAutocomplete($nome, $idAzienda)
    {
        $ot = TableRegistry::get('Aziende.Orders');
        $ot = $ot->find('all')->select(['id' => 'id','text' => 'name'])
                  ->where(['name LIKE' =>'%'.$nome.'%', 'id_azienda' => $idAzienda ])->order(['name'=>'ASC']);
        return $ot->toArray();

    }

  /**
   * Metodo che fa le query per trovare il numero di ordini per mese a ritroso dalla
   * data attuale.
   *
   * @param  integer $monthsNum numeri di mesi che si vuole visualizzare, default 6
   * @return array              dati formattati pronti per il chart
   */
    public function getOrdersChart($monthsNum = 15)
    {
        $ordersTable = TableRegistry::get('Aziende.Orders');
        $monthsNames = array('01'=>'Gennaio','02'=>'Febbraio','03'=>'Marzo','04'=>'Aprile',
            '05'=>'Maggio','06'=>'Giugno','07'=>'Luglio','08'=>'Agosto','09'=>'Settembre',
            '10'=>'Ottobre','11'=>'Novembre','12'=>'Dicembre');
        $date = new \DateTime(date('Y-m-15'));
        $orders = array();
        for( $i = 0; $i < $monthsNum; $i++ ){
            $orders['labels'][] = $monthsNames[$date->format('m')];
            $orders['data']['Nuovi']['color'] = '#3b8bba';
            $orders['data']['Attivi']['color'] = '#f7c36e';
            $orders['data']['Chiusi']['color'] = '#c1c7d1';

            $ordersMonth = $ordersTable->find()->select(['opened'=>'SUM(IF(`created` LIKE "'.$date->format('Y-m').'%",1,0))',
              'active' => 'SUM(IF(`created` < "'.$date->format('Y-m-t 24').'" AND( `closed` > "'.$date->format('Y-m').'" OR `closed` = "0000-00-00 00:00:00" ),1,0 ))',
              'close' => ' SUM(IF(`closed` LIKE "'.$date->format('Y-m').'%",1,0))'
              ])->first();
            $orders['data']['Nuovi']['data'][] = $ordersMonth['opened'];
            $orders['data']['Attivi']['data'][] = $ordersMonth['active'];
            $orders['data']['Chiusi']['data'][] = $ordersMonth['close'];
            $date->modify(' -1 month');
        }

        $orders['labels'] = array_reverse($orders['labels']);
        foreach(  $orders['data'] as $key => $data){
          $orders['data'][$key]['data'] = array_reverse($data['data']);
        }


        return $orders;
        
    }

    public function saveOrder($dati)
    {
        $ordersTable = TableRegistry::get('Aziende.Orders');
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

        if($res && !empty($statusUpdate)){
           TableRegistry::get('Aziende.OrdersHistory')->saveOrderChange($res);
        }

        return $res;

    }

}
