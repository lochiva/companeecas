<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SediTipiTable extends Table
{
    
    public function initialize(array $config)
    {
        $this->setTable('sedi_tipi');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Aziende.Sede');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
    }
    
    
    
}