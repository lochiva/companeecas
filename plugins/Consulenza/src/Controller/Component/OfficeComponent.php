<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class OfficeComponent extends Component
{
    
    public function getOffices(){

        $offices = TableRegistry::get('Consulenza.Offices');

        $res = $offices->find('all')->order('name ASC')->toArray();

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }

    public function _newEntity(){
        $offices = TableRegistry::get('Consulenza.Offices');
        return $offices->newEntity();
    }
    
    public function _patchEntity($doc,$request){
        $offices = TableRegistry::get('Consulenza.Offices');
        return $offices->patchEntity($doc,$request);
    }
    
    public function _save($doc){
        $offices = TableRegistry::get('Consulenza.Offices');
        return $offices->save($doc);
    }
    
    public function _get($id){
        $offices = TableRegistry::get('Consulenza.Offices');
        return $offices->get($id);
        
    }
    
    public function _delete($doc){
        $offices = TableRegistry::get('Consulenza.Offices');
        return $offices->delete($doc);
    }
    
}