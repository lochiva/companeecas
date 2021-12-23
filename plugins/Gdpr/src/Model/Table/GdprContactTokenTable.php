<?php
namespace Gdpr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GdprContactToken Model
 *
 * @method \Gdpr\Model\Entity\GdprContactToken get($primaryKey, $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken newEntity($data = null, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken[] newEntities(array $data, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken[] patchEntities($entities, array $data, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GdprContactTokenTable extends Table
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

        $this->setTable('gdpr_contact_token');
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
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmpty('token');

        $validator
            ->email('email')
            ->maxLength('email', 100)
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->integer('used')
            ->requirePresence('used', 'create')
            ->notEmpty('used');

        return $validator;
    }

}
