<?php

namespace Progest\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ServiceComponent extends Component
{


    public function initialize(array $config)
    {
        parent::initialize($config);

    }

    public function contactsForService($idService,$pass)
    {
        if(empty($pass['start']) || empty($pass['end'])){
            $pass['start'] = '';
            $pass['end'] = '';
        }
        $serviceTable = TableRegistry::get('Progest.Services');
        return $serviceTable->getContactsForService($idService,$pass);
    }

    public function servicePerCategory($idCategory = 0)
    {
        $serviceTable = TableRegistry::get('Progest.Services');
        return $serviceTable->find()->select(['id'=>'Services.id','name'=>'Services.name','editable'=>'Services.editable'])
          ->contain('CategoriesGroup')->where(['CategoriesGroup.id_category'=>$idCategory]);
    }

    public function getOrderServices($idOrder)
    {
        $servicesOrderTable = TableRegistry::get('Progest.ServicesOrders');
        return $servicesOrderTable->find()->contain(['Services'])
          ->where(['ServicesOrders.id_order'=>$idOrder])->toArray();
    }

}
