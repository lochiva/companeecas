<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class SediTipiMinisteroTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('sedi_tipi_ministero');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }
    
    
    
}