<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class SediTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('sedi');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Sede');
        $this->belongsTo('Aziende.SediTipi',['foreignKey' => 'id_tipo', 'propertyName' => 'tipoSede']);
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda', 'propertyName' => 'azienda']);
    }
    
    
    
}