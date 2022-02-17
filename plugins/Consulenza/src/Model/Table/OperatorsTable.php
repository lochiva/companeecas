<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class OperatorsTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('operators');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Consulenza.Phase');
        $this->belongsTo('Document.Users',['foreignKey' => 'user_id']);
    }
    
    
    
}