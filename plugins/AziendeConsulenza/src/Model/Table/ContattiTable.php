<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class ContattiTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('contatti');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Contatto');
        $this->belongsTo('Aziende.ContattiRuoli',['foreignKey' => 'id_ruolo', 'propertyName' => 'ruolo']);
        $this->belongsTo('Aziende.Sedi',['foreignKey' => 'id_sede', 'propertyName' => 'sede']);
    }
    
    
    
}