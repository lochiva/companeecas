<?php

namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

class JobsOrdersIrapEncTable extends AppTable
{

	 public function initialize(array $config)
	 {
	 	$this->table('jobs_orders');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
       
        $this->belongsTo('PhasesIrapEnc',['foreignKey' => 'phase_id', 'propertyName' => 'phase','className' => 'Phases']);

        $this->belongsTo('Consulenza.JobsJobsAttributes',[
            'foreignKey' => false,
            'propertyName' => 'JobsJobsAttributes',
            'conditions' => ['JobsOrdersIrapEnc.job_id = JobsJobsAttributes.job_id']
            ]);
	 }

}