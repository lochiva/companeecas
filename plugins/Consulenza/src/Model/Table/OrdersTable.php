<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class OrdersTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('orders');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Consulenza.Order');
        $this->belongsTo('Consulenza.Aziende',[
        	'foreignKey' => 'azienda_id',
        	'propertyName' => 'azienda'
        	]);
        $this->belongsTo('Consulenza.Typeofbusinesses',[
        	'foreignKey' => 'typeofbusiness_id',
        	'propertyName' => 'type_of_business'
        	]);
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_orders',
            'propertyName' => 'jobs'
        ]);
        $this->hasMany('Consulenza.Tasks',[
          'foreignKey' => 'order_id'
        ]);
        $this->belongsTo('UsersPartner',['foreignKey' => 'userPartner_id', 'propertyName' => 'partner','className' => 'Users','joinType' => 'LEFT']);
        $this->hasMany('Consulenza.Frozentasks',[
          'foreignKey' => 'order_id'
        ]);
    }

    public function getAllYears() {
        return $this->find('list', [
            'keyField' => 'year',
            'valueField' => 'year',
            'groupField' => 'year'
        ]);
    }

}
