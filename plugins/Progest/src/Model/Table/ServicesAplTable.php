<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * ProgestServicesApl Model
 *
 * @method \Progest\Model\Entity\ProgestServicesApl get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestServicesApl newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesApl[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesApl|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestServicesApl patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesApl[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesApl findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesAplTable extends AppTable
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

        $this->setTable('progest_services_apl');
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
