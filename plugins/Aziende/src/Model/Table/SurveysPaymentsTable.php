<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SurveysPayments Model
 *
 * @property \Aziende\Model\Table\SurveysTable&\Cake\ORM\Association\BelongsTo $Surveys
 *
 * @method \Aziende\Model\Entity\SurveysPayment get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\SurveysPayment findOrCreate($search, callable $callback = null, $options = [])
 */
class SurveysPaymentsTable extends Table
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

        $this->setTable('surveys_payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Surveys', [
            'foreignKey' => 'survey_id',
            'joinType' => 'INNER',
            'className' => 'Surveys.Surveys'
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
        $rules->add($rules->existsIn(['survey_id'], 'Surveys'));

        return $rules;
    }
}
