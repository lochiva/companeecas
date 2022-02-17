<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class UsersTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('users');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Consulenza.Frozentask');

        $this->hasMany('Consulenza.Tasks',[
        	'classname' => 'user_id',
        	'propertyName' => 'tasks'
        	]);
      
        /*
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_orders',
        ]);
        */
    }



}
