<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class JobsTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->table('jobs');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Consulenza.Job');
        $this->belongsToMany('Consulenza.Orders', [
            'joinTable' => 'jobs_orders',
        ]);
        $this->belongsToMany('Consulenza.Processes', [
            'joinTable' => 'jobs_processes',
        ]);
        $this->belongsToMany('Consulenza.Jobsattributes', [
            'joinTable' => 'jobs_jobsattributes',
        ]);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->hasMany('Consulenza.Tasks',[
          'foreignKey' => 'job_id'
        ]);

    }



}
