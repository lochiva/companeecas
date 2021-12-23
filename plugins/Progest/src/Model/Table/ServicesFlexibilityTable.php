<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * ProgestServicesFlexibility Model
 *
 * @method \Progest\Model\Entity\ProgestServicesFlexibility get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestServicesFlexibility newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesFlexibility[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesFlexibility|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestServicesFlexibility patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesFlexibility[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesFlexibility findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesFlexibilityTable extends AppTable
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

        $this->setTable('progest_services_flexibility');
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

}
