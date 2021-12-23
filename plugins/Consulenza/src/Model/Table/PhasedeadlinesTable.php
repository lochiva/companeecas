<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class PhasesdeadlinesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('phasedeadlines');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Consulenza.Order');
        $this->belongsTo('Consulenza.Phases',[
        	'foreignKey' => 'phase_id', 
        	'propertyName' => 'phase'
        	]);
        /*
        $this->belongsTo('Consulenza.Typeofbusinesses',[
        	'foreignKey' => 'typeofbusiness_id', 
        	'propertyName' => 'type_of_business'
        	]);
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_orders',
        ]);
        */
    }
    
    
    
}