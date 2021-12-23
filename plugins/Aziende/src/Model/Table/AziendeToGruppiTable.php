<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AziendeToGruppi Model
 *
 * @method \Aziende\Model\Entity\AziendeToGruppi get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\AziendeToGruppi newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\AziendeToGruppi[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\AziendeToGruppi|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AziendeToGruppi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AziendeToGruppi[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AziendeToGruppi findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AziendeToGruppiTable extends Table
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

        $this->setTable('aziende_to_gruppi');
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
            ->integer('id_gruppo')
            ->requirePresence('id_gruppo', 'create')
            ->notEmpty('id_gruppo');

        $validator
            ->integer('id_azienda')
            ->requirePresence('id_azienda', 'create')
            ->notEmpty('id_azienda');

        return $validator;
    }
}
