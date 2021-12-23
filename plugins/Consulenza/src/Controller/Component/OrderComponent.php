<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class OrderComponent extends Component
{


    public function getOrdersByIdAzienda($id = ""){

        $out = array();

        if($id != ""){

            $orders = TableRegistry::get('Consulenza.Orders');

            $res = $orders->find('all')->where(['azienda_id' => $id])->order('year DESC')->toArray();

            //echo "<pre>"; print_r($res); echo "</pre>";

            foreach ($res as $key => $value) {
                $out[] = array(
                    'id' => $value->id,
                    'year' => $value->year
                    );
            }

        }

        return $out;
    }

    public function getAziendaFromOrder($id = ""){

        $out = array();

        if($id != ""){

            $orders = TableRegistry::get('Consulenza.Orders');

            $opt['Orders.id'] = $id;

            $res = $orders->find('all')->where($opt)->contain(['Aziende'])->toArray();
            //echo "<pre>"; print_r($res); echo "</pre>";

            if(isset($res[0]->azienda) && !empty($res[0]->azienda)){
                $out = array('id' => $res[0]->azienda->id, 'name' => $res[0]->azienda->denominazione);
            }

        }

        return $out;

    }

    public function getDataOrder($params){

        $data = array();

        if(!empty($params) && isset($params['azienda_id']) && isset($params['year'])){

            $orders = TableRegistry::get('Consulenza.Orders');

            $opt['azienda_id'] = $params['azienda_id'];
            if(isset($params['previus']) && $params['previus'] == true){
                $opt['year <'] = $params['year'];
            }else{
                $opt['year'] = $params['year'];
            }


            $res = $orders->find('all')->where($opt)->contain(['Jobs'])->toArray();
            //echo "<pre>"; print_r($res); echo "</pre>";

            $data = $res;

        }

        return $data;

    }

    public function lockOrderById($id){

        $order = $this->_get($id);

        $order->isLocked = 1;

        $this->_save($order);

    }

    public function unlockOrderById($id){

      $order = $this->_get($id);

      $order->isLocked = 0;

      $this->_save($order);
    }

    public function _newEntity(){
        $orders = TableRegistry::get('Consulenza.Orders');
        return $orders->newEntity();
    }

    public function _patchEntity($doc,$request){
        $orders = TableRegistry::get('Consulenza.Orders');
        return $orders->patchEntity($doc,$request);
    }

    public function _save($doc){
        $orders = TableRegistry::get('Consulenza.Orders');
        return $orders->save($doc);
    }

    public function _get($id){
        $orders = TableRegistry::get('Consulenza.Orders');
        return $orders->get($id);

    }

    public function _delete($doc){
        $orders = TableRegistry::get('Consulenza.Orders');
        return $orders->delete($doc);
    }


}
