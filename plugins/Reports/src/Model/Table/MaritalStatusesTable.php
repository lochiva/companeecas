<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * MaritalStatuses Model
 *
 * @method \Reports\Model\Entity\MaritalStatus get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\MaritalStatus newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\MaritalStatus[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\MaritalStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\MaritalStatus|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\MaritalStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\MaritalStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\MaritalStatus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MaritalStatusesTable extends AppTable
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

        $this->setTable('reports_marital_statuses');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.MaritalStatus');

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
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name')
            ->allowEmptyString('name', false);

        $validator
            ->integer('ordering')
            ->allowEmpty('ordering', true);

        $validator
            ->boolean('user_text');

        return $validator;
    }

}
