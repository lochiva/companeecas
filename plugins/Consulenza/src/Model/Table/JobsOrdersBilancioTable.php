<?php

namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

class JobsOrdersBilancioTable extends AppTable
{

	 public function initialize(array $config)
	 {
	 	$this->table('jobs_orders');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
       
        $this->belongsTo('PhasesBilancio',['foreignKey' => 'phase_id', 'propertyName' => 'phase','className' => 'Phases']);

        $this->belongsTo('Consulenza.JobsJobsAttributes',[
            'foreignKey' => false,
            'propertyName' => 'JobsJobsAttributes',
            'conditions' => ['JobsOrdersBilancio.job_id = JobsJobsAttributes.job_id']
            ]);
	 }

}