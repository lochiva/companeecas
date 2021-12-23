<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class OfficesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('offices');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->entityClass('Consulenza.Phase');
        //$this->belongsTo('Document.Processes',['foreignKey' => 'process_id']);
    }
    
    
    
}