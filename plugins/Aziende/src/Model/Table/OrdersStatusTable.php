<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class OrdersStatusTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('orders_status');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Aziende.Order');
        $this->hasMany('Aziende.Orders',['foreignKey' => 'id_status', 'propertyName' => 'order']);

    }

    public function getList($conditions = array())
    {
        return $this->find()->order(['ordering' => 'ASC'])->toArray();

    }



}
