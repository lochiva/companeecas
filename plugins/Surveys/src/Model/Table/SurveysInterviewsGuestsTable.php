<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SurveysInterviewsGuests Model
 *
 * @property \Surveys\Model\Table\InterviewsTable&\Cake\ORM\Association\BelongsTo $Interviews
 * @property \Surveys\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 *
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsGuest findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysInterviewsGuestsTable extends Table
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

        $this->setTable('surveys_interviews_guests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Interviews', [
            'foreignKey' => 'interview_id',
            'joinType' => 'INNER',
            'className' => 'Surveys.Interviews'
        ]);
        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER',
            'className' => 'Surveys.Guests'
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
        $rules->add($rules->existsIn(['guest_id'], 'Guests'));

        return $rules;
    }
}
