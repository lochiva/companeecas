<?php
namespace Leads\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * LeadsQuestionTypes Model
 *
 * @method \Leads\Model\Entity\LeadsQuestionType get($primaryKey, $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType newEntity($data = null, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType[] newEntities(array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType[] patchEntities($entities, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestionType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadsQuestionTypesTable extends AppTable
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

        $this->setTable('leads_question_types');
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
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('label')
            ->maxLength('label', 255)
            ->requirePresence('label', 'create')
            ->notEmpty('label');

        return $validator;
    }
}
