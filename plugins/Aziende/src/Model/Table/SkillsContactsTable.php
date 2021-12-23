<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SkillsContacts Model
 *
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SkillsContactsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('skills_contacts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->integer('id_contatto')
            ->requirePresence('id_contatto', 'create')
            ->notEmpty('id_contatto');

        $validator
            ->integer('id_skill')
            ->requirePresence('id_skill', 'create')
            ->notEmpty('id_skill');

        return $validator;
    }
}
