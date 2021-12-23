<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class PhaseComponent extends Component
{
    
    
    public function getPhaseByProcess($id = ""){

        $out = array();

        if($id != ""){

            $phases = TableRegistry::get('Consulenza.Phases');

            $opt['process_id'] = $id;
            $opt['plannable'] = 1;

            $res = $phases->find('all')->where($opt)->order('ordering ASC')->toArray();

            //echo "<pre>"; print_r($res); echo "</pre>";

            if(!empty($res)){

                foreach ($res as $key => $phase) {
                    $out[] = array('id' => $phase->id, 'name' => $phase->name);
                }

            }

        }

        return $out;
    }


    public function _newEntity(){
        $phases = TableRegistry::get('Consulenza.Phases');
        return $phases->newEntity();
    }
    
    public function _patchEntity($doc,$request){
        $phases = TableRegistry::get('Consulenza.Phases');
        return $phases->patchEntity($doc,$request);
    }
    
    public function _save($doc){
        $phases = TableRegistry::get('Consulenza.Phases');
        return $phases->save($doc);
    }
    
    public function _get($id){
        $phases = TableRegistry::get('Consulenza.Phases');
        return $phases->get($id);
        
    }
    
    public function _delete($doc){
        $phases = TableRegistry::get('Consulenza.Phases');
        return $phases->delete($doc);
    }

    
}