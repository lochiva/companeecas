<?php
namespace Leads\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * LeadsEnsembles Model
 *
 * @method \Leads\Model\Entity\LeadsEnsemble get($primaryKey, $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble newEntity($data = null, array $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble[] newEntities(array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble[] patchEntities($entities, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsEnsemble findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadsEnsemblesTable extends AppTable
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

        $this->setTable('leads_ensembles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Leads.LeadsQuestions',['foreignKey' => 'id_ensemble']);
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmpty('description');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }
}
