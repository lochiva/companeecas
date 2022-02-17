<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class SediTipiCapitolatoTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('sedi_tipi_capitolato');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }
    
    
    
}