<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class FrozentasksTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('frozentasks');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->entityClass('Consulenza.Frozentask');
        $this->belongsTo('Consulenza.Jobs',[
        	'foreignKey' => 'job_id', 
        	'propertyName' => 'job'
        	]);
        $this->belongsTo('Consulenza.Users',[
        	'foreignKey' => 'user_id', 
        	'propertyName' => 'user'
        	]);
        $this->belongsTo('Consulenza.Orders',[
            'foreignKey' => 'order_id', 
            'propertyName' => 'order'
            ]);
        $this->belongsTo('Consulenza.Phases',[
            'foreignKey' => 'phase_id', 
            'propertyName' => 'phase'
            ]);
        /*
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_orders',
        ]);
        */
    }
    
    
    
}