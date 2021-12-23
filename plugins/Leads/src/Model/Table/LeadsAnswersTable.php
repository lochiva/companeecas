<?php
namespace Leads\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * LeadsAnswers Model
 *
 * @method \Leads\Model\Entity\LeadsAnswer get($primaryKey, $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer newEntity($data = null, array $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer[] newEntities(array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer[] patchEntities($entities, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsAnswer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadsAnswersTable extends AppTable
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

        $this->setTable('leads_answers');
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
            ->integer('id_interview')
            ->requirePresence('id_interview', 'create')
            ->notEmpty('id_interview');

        $validator
            ->integer('id_question')
            ->requirePresence('id_question', 'create')
            ->notEmpty('id_question');

        $validator
            ->scalar('question_answer')
            ->allowEmpty('question_answer');

        return $validator;
    }

    public function getAnswersInterview($idInterview)
    {
        $res = $this->find()
                    ->where(['LeadsAnswers.id_interview' => $idInterview])
                    ->toArray();

        $answers = []; 
        foreach($res as $a){
            $answers[$a['id_question']] = [
                'id' => $a['id'],
                'answer' => $a['question_answer']
            ];
        }

        return $answers;
    }
}
