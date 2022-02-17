<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class OrdersHistoryTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('orders_status_history');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Aziende.Order');
        $this->belongsTo('Aziende.Orders',['foreignKey' => 'id_order', 'propertyName' => 'order']);
    }

    public function saveOrderChange($data)
    {
        $history = $this->newEntity();
        $history->id_status = $data['id_status'];
        $history->id_order = $data['id'];
        if(!empty($_SESSION['Auth']['User']['id'])){
					$history->id_user = $_SESSION['Auth']['User']['id'];
				}

        return $this->save($history);
    }

    public function initializeHistory($orders)
    {
        $count = 0;
        foreach($orders as $order){
            $entity = $this->newEntity();
            $entity->id_status = 1;
            $entity->id_order = $order['id'];
            $entity->created = $order['created'];

            $this->save($entity);
            $count++;
        }

        return $count;
    }


}
