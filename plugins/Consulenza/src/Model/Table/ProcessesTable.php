<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class ProcessesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('processes');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Consulenza.Process');
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_processes',
        ]);
        /*
        $this->belongsTo('Consulenza.Aziende',[
        	'foreignKey' => 'azienda_id', 
        	'propertyName' => 'azienda'
        	]);
        $this->belongsTo('Consulenza.Typeofbusinesses',[
        	'foreignKey' => 'typeofbusiness_id', 
        	'propertyName' => 'type_of_business'
        	]);
        
        */
    }
    
    
    
}