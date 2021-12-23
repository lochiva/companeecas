<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Victims Model
 *
 * @method \Reports\Model\Entity\Victim get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\Victim newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\Victim[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\Victim|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Victim|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Victim patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\Victim[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\Victim findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VictimsTable extends AppTable
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

        $this->setTable('reports_victims');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.Victim');

        $this->belongsTo('Users')
            ->setForeignKey('user_update_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Countries')
            ->setClassName('Luoghi')
            ->setForeignKey('country_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Citizenships')
            ->setClassName('Luoghi')
            ->setForeignKey('citizenship_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Regions')
            ->setClassName('Luoghi')
            ->setForeignKey('region_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Provinces')
            ->setClassName('Luoghi')
            ->setForeignKey('province_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Cities')
            ->setClassName('Luoghi')
            ->setForeignKey('city_id')
            ->setJoinType('LEFT');

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
            ->integer('user_update_id')
            ->requirePresence('user_update_id')
            ->allowEmpty('user_update_id', false);

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 64)
            ->requirePresence('firstname')
            ->allowEmptyString('firstname', false);

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 64)
            ->requirePresence('lastname')
            ->allowEmptyString('lastname', false);

        $validator
            ->integer('gender_id')
            ->requirePresence('gender_id')
            ->allowEmpty('gender_id', false);

        $validator
            ->scalar('gender_user_text')
            ->maxLength('gender_user_text', 64)
            ->allowEmptyString('gender_user_text', true);

        $validator
            ->integer('country_id')
            ->allowEmpty('country_id', true);

        $validator
            ->integer('birth_year')
            ->allowEmpty('birth_year', true);

        $validator
            ->integer('citizenship_id')
            ->allowEmpty('citizenship_id', true);

        $validator
            ->scalar('citizenship_user_text')
            ->maxLength('citizenship_user_text', 64)
            ->allowEmptyString('citizenship_user_text', true);

        $validator
            ->integer('educational_qualification_id')
            ->allowEmpty('educational_qualification_id', true);

        $validator
            ->scalar('educational_qualification_user_text')
            ->maxLength('educational_qualification_user_text', 64)
            ->allowEmptyString('educational_qualification_user_text', true);

        $validator
            ->integer('region_id')
            ->allowEmpty('region_id', true);

        $validator
            ->scalar('region_user_text')
            ->maxLength('region_user_text', 64)
            ->allowEmptyString('region_user_text', true);

        $validator
            ->integer('type_occupation_id')
            ->allowEmpty('type_occupation_id', true);

        $validator
            ->scalar('type_occupation_user_text')
            ->maxLength('type_occupation_user_text', 64)
            ->allowEmptyString('type_occupation_user_text', true);

        $validator
            ->integer('marital_status_id')
            ->allowEmpty('marital_status_id', true);

        $validator
            ->scalar('marital_status_user_text')
            ->maxLength('marital_status_user_text', 64)
            ->allowEmptyString('marital_status_user_text', true);

        $validator
            ->integer('in_italy_from_year')
            ->allowEmpty('in_italy_from_year', true);

        $validator
            ->integer('residency_permit_id')
            ->allowEmpty('residency_permit_id', true);

        $validator
            ->scalar('residency_permit_user_text')
            ->maxLength('residency_permit_user_text', 64)
            ->allowEmptyString('residency_permit_user_text', true);

        $validator
            ->scalar('lives_with')
            ->maxLength('lives_with', 255)
            ->allowEmptyString('lives_with', true);

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 32)
            ->allowEmptyString('telephone', true);

        $validator
            ->scalar('mobile')
            ->maxLength('mobile', 32)
            ->allowEmptyString('mobile', true);

        $validator
            ->scalar('email')
            ->maxLength('email', 64)
            ->allowEmptyString('email', true);

        $validator
            ->integer('city_id')
            ->allowEmpty('city_id', true);

        $validator
            ->integer('province_id')
            ->allowEmpty('province_id', true);

        $validator
            ->integer('region_id')
            ->allowEmpty('region_id', true);    

        return $validator;
    }

}
