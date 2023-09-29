<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SurveysInterviewsPayments Model
 *
 * @property \Aziende\Model\Table\InterviewsTable&\Cake\ORM\Association\BelongsTo $Interviews
 * @property \Aziende\Model\Table\PaymentsTable&\Cake\ORM\Association\BelongsTo $Payments
 *
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\SurveysInterviewsPayment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysInterviewsPaymentsTable extends Table
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

        $this->setTable('surveys_interviews_payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Interviews', [
            'foreignKey' => 'interview_id',
            'joinType' => 'INNER',
            'className' => 'Surveys.SurveysInterviews'
        ]);
        $this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Payments'
        ]);
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

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['interview_id'], 'Interviews'));
        $rules->add($rules->existsIn(['payment_id'], 'Payments'));

        return $rules;
    }
}
