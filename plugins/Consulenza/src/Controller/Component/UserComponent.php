<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class UserComponent extends Component
{
    
    public function getPartners(){

        $users = TableRegistry::get('Consulenza.Users');

        $res = $users->find('all')->where(['isPartner' => 1])->order('cognome ASC')->toArray();

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }

    public function getUsers(){

        $users = TableRegistry::get('Consulenza.Users');

        $res = $users->find('all')->order('cognome ASC')->toArray();

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }

    public function _newEntity(){
        $users = TableRegistry::get('Consulenza.Users');
        return $users->newEntity();
    }
    
    public function _patchEntity($doc,$request){
        $users = TableRegistry::get('Consulenza.Users');
        return $users->patchEntity($doc,$request);
    }
    
    public function _save($doc){
        $users = TableRegistry::get('Consulenza.Users');
        return $users->save($doc);
    }
    
    public function _get($id){
        $users = TableRegistry::get('Consulenza.Users');
        return $users->get($id);
        
    }
    
    public function _delete($doc){
        $users = TableRegistry::get('Consulenza.Users');
        return $users->delete($doc);
    }
    
}