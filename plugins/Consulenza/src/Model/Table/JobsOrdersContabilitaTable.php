<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

class JobsOrdersContabilitaTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('jobs_orders');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('PhasesContabilita',['foreignKey' => 'phase_id', 'propertyName' => 'phase','className' => 'Phases']);

        $this->belongsTo('Consulenza.JobsJobsAttributes',[
            'foreignKey' => false,
            'propertyName' => 'JobsJobsAttributes',
            'conditions' => ['JobsOrdersContabilita.job_id = JobsJobsAttributes.job_id']
            ]);

        $this->belongsTo('UsersContabilita',['foreignKey' => 'user_id', 'propertyName' => 'operatore','className' => 'Users']);



    }


}
