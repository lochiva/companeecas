<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class PayamentContidionsTable extends AppTable
{
    public function initialize(array $config)
    {
        $this->table('payament_conditions');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->entityClass('Aziende.Contatto');
    }
}
