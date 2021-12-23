<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class ContattiRuoliTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('contatti_ruoli');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Aziende.Ruolo');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->hasOne('Aziende.Contatti',['foreignKey' => 'id_ruolo', 'propertyName' => 'contatti']);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('ruolo', 'Il nome del ruolo è obbligatorio.')
            ->notEmpty('color', 'Il colore del ruolo è obbligatorio.')
            ->notEmpty('order', 'l\'ordinamento del ruolo è obbligatorio.');

    }

    public function getList($conditions = array())
  	{
  			return $this->find()->select(['id'=>'id','text'=>'ruolo'])->order(['ordering' => 'ASC'])
  				->where($conditions)->toArray();
  	}



}
