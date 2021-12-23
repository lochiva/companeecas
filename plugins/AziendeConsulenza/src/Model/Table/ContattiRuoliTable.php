<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class ContattiRuoliTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('contatti_ruoli');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->entityClass('Aziende.Sede');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
    }
    
    
    
}