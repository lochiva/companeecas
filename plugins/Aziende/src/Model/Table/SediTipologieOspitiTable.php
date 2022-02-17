<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class SediTipologieOspitiTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('sedi_tipologie_ospiti');
        $this->setPrimaryKey('id');
        $this->setDisplayField('name');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('ordering')
            ->requirePresence('ordering', 'create')
            ->notEmpty('ordering');

        return $validator;
    }
    
    public function getList($conditions = array())
  	{
        return $this->find()
            ->order(['ordering' => 'ASC'])
            ->where($conditions)
            ->toArray();
  	}
    
}