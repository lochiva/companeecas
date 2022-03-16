<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GuestsStatuses Model
 *
 * @method \Aziende\Model\Entity\GuestsStatus get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsStatus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsStatusesTable extends Table
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

        $this->setTable('guests_statuses');
        $this->setDisplayField('name');
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('color')
            ->maxLength('color', 7)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        $validator
            ->boolean('visibility');

        return $validator;
    }
}
