<?php
namespace Leads\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * LeadsQuestions Model
 *
 * @method \Leads\Model\Entity\LeadsQuestion get($primaryKey, $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion newEntity($data = null, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion[] newEntities(array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion[] patchEntities($entities, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsQuestion findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadsQuestionsTable extends AppTable
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

        $this->setTable('leads_questions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('QuestionTypes', [
                'className' => 'Leads.LeadsQuestionTypes'
            ])
            ->setForeignKey('id_type');
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
            ->integer('id_ensemble')
            ->requirePresence('id_ensemble', 'create')
            ->notEmpty('id_ensemble');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('id_type')
            ->requirePresence('id_type', 'create')
            ->notEmpty('id_type');

        $validator
            ->scalar('info')
            ->maxLength('info', 255)
            ->allowEmpty('info');

        $validator
            ->scalar('options')
            ->allowEmpty('options');

        $validator
            ->integer('ordering');

        $validator
            ->boolean('deleted');

        return $validator;
    }

    public function getLastOrdering($idEnsemble)
    {
        $question = $this->find()
                    ->where(['LeadsQuestions.id_ensemble' => $idEnsemble])
                    ->order(['LeadsQuestions.ordering DESC'])
                    ->first();

        return $question['ordering'];
    }

    public function getQuestionsEnsemble($idEnsemble)
    {
        $questions = $this->find()
                    ->where(['LeadsQuestions.id_ensemble' => $idEnsemble])
                    ->order(['LeadsQuestions.ordering ASC'])
                    ->toArray();

        return $questions;
    }
}
