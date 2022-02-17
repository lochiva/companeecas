<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class JobsattributesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('jobsattributes');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Consulenza.Typeofbusiness');
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_jobsattributes',
            'className' => 'Jobs'
        ]);
        
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
    }
    
    public function getJobsFiltered($keyAttr) {
        return $this->find('all')
            ->contain(['Jobs'])
            ->where(['Jobsattributes.key_attribute' => $keyAttr]);
    }

    public function getWorkloadJobs() {
        return $this->find('all')
            ->contain(['Jobs'])
            ->where(['Jobsattributes.key_attribute' => 'WORKLOAD'])
            ->first();
    }

    public function getAccountingJobs() {
        return $this->find('all')
            ->contain(['Jobs'])
            ->where(['Jobsattributes.key_attribute' => 'ACCOUNTING'])
            ->first();
    }

}