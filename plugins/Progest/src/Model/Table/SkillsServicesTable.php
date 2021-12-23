<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SkillsServices Model
 *
 * @method \Progest\Model\Entity\ProgestSkillsService get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestSkillsService newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestSkillsService[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestSkillsService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestSkillsService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestSkillsService[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestSkillsService findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SkillsServicesTable extends Table
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

        $this->setTable('progest_skills_services');
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
            ->integer('id_service')
            ->requirePresence('id_service', 'create')
            ->notEmpty('id_service');

        $validator
            ->integer('id_skill')
            ->requirePresence('id_skill', 'create')
            ->notEmpty('id_skill');

        return $validator;
    }
}
