<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class JobsOrdersComponent extends Component
{
    
    

    public function _newEntity(){
        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
        return $jobsOrders->newEntity();
    }
    
    public function _patchEntity($doc,$request){
        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
        return $jobsOrders->patchEntity($doc,$request);
    }
    
    public function _save($doc){
        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
        return $jobsOrders->save($doc);
    }
    
    public function _get($id){
        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
        return $jobsOrders->get($id);
        
    }
    
    public function _delete($doc){
        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
        return $jobsOrders->delete($doc);
    }

    public function setInviato($order_id){

        if(isset($order_id) && $order_id > 0){
            
            // 1) leggo l'ordine per avere l'attuale phase

            $order = $this->_get($order_id);

            $phases = TableRegistry::get('Consulenza.Phases');

            if(sizeof($order)>0){
                $phase = $phases->find('all')->where(array('Phases.id' => $order->phase_id))->toArray();
            }

            // 2) controllo che la phase che ha attualmente sia effettivamente in stato READY
            if($phase[0]->status=='READY'){

                // 3) cerco  la phase per quell'order id che abbia lo stato DONE e gliela setto, restituendogli la milestone
                $new_phase = $phases->find('all')->where(array('Phases.process_id' => $order->process_id,'Phases.status = "DONE"'))->toArray();

                if(sizeof($new_phase)>0){

                    $order->phase_id = $new_phase[0]->id;
                    
                    if($this->_save($order)){
                        return $new_phase[0]->milestone;
                    } else {
                        return "Errore nel salvataggio";
                    }
                        
                }else
                {
                    return "Non e' possibile inviare questo report (non è presente nessuna fase con stato READY per il processo di quest'ordine)";
                }

            } else {
                return "Non e' possibile inviare questo report (stato diverso da READY)";
            }

        } else { 
            return false;
        } 

    }

    public function setIrapInviato($order_id){

        if(isset($order_id) && $order_id > 0){

            $order = $this->_get($order_id);

            $order->irapInviato = '1';

            if($this->_save($order)){
                return true;
            } else {
                return "Errore nel salvataggio";
            }
                    
        } else {
            return false;
        } 

    }    

    
}