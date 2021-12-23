<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SurveysToStructures Model
 *
 * @method \Surveys\Model\Entity\SurveysToStructure get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysToStructure findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysToStructuresTable extends Table
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

        $this->setTable('surveys_to_structures');
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
            ->integer('id_survey')
            ->requirePresence('id_survey', 'create')
            ->allowEmpty('id_survey', false);

        $validator
            ->integer('id_azienda')
            ->requirePresence('id_azienda', 'create')
            ->allowEmpty('id_azienda', false);

        $validator
            ->integer('id_sede')
            ->allowEmpty('id_sede', true);

        return $validator;
    }

    public function getStructuresForSurvey($idSurvey)
    {
        return $this->find()
            ->select(['SurveysToStructures.id_azienda', 'label' => 'a.denominazione', 'sedi' => 'GROUP_CONCAT(SurveysToStructures.id_sede)'])
            ->where(['id_survey' => $idSurvey])
            ->join([
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'LEFT',
                    'conditions' => ['a.id = SurveysToStructures.id_azienda']
                ],
            ])
            ->group('SurveysToStructures.id_azienda')
            ->order('label')
            ->toArray();
    }
 }
