<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GuestsExitTypes Model
 *
 * @method \Aziende\Model\Entity\GuestsExitType get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsExitTypesTable extends Table
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

        $this->setTable('guests_exit_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Tipi', [
            'foreignKey' => 'ente_type',
            'className' => 'Aziende.AziendeTipi',
            'propertyName' => 'tipo'
        ]);
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->boolean('required_confirmation');

        $validator
            ->boolean('required_note');

        $validator
            ->boolean('startable_by_ente');

        $validator
            ->boolean('toSAI');

        $validator
            ->integer('ente_type');

        $validator
            ->integer('ordering');

        return $validator;
    }
}
