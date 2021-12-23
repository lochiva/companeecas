<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Witnesses Model
 *
 * @method \Reports\Model\Entity\Witness get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\Witness newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\Witness[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\Witness|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Witness|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Witness patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\Witness[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\Witness findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WitnessesTable extends AppTable
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

        $this->setTable('reports_witnesses');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.Witness');

        $this->belongsTo('Users')
            ->setForeignKey('user_update_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Genders')
            ->setClassName('Reports.Genders')
            ->setForeignKey('gender_id')
            ->setJoinType('LEFT');

        $this->belongsTo('EducationalQualifications')
            ->setClassName('Reports.EducationalQualifications')
            ->setForeignKey('educational_qualification_id')
            ->setJoinType('LEFT');
			
        $this->belongsTo('MaritalStatuses')
            ->setClassName('Reports.MaritalStatuses')
            ->setForeignKey('marital_status_id')
            ->setJoinType('LEFT');
			
        $this->belongsTo('OccupationTypes')
            ->setClassName('Reports.OccupationTypes')
            ->setForeignKey('type_occupation_id')
            ->setJoinType('LEFT');

        $this->belongsTo('Religions')
            ->setClassName('Reports.Religions')
            ->setForeignKey('religion_id')
            ->setJoinType('LEFT');

        $this->belongsTo('ResidencyPermits')
            ->setClassName('Reports.ResidencyPermits')
            ->setForeignKey('residency_permit_id')
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

        $this->belongsTo('RegionLegal')
            ->setClassName('Luoghi')
            ->setForeignKey('region_id_legal')
            ->setJoinType('LEFT');

        $this->belongsTo('ProvinceLegal')
            ->setClassName('Luoghi')
            ->setForeignKey('province_id_legal')
            ->setJoinType('LEFT');

        $this->belongsTo('CityLegal')
            ->setClassName('Luoghi')
            ->setForeignKey('city_id_legal')
            ->setJoinType('LEFT');

        $this->belongsTo('RegionOperational')
            ->setClassName('Luoghi')
            ->setForeignKey('region_id_operational')
            ->setJoinType('LEFT');

        $this->belongsTo('ProvinceOperational')
            ->setClassName('Luoghi')
            ->setForeignKey('province_id_operational')
            ->setJoinType('LEFT');

        $this->belongsTo('CityOperational')
            ->setClassName('Luoghi')
            ->setForeignKey('city_id_operational')
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
            ->scalar('type')
            ->maxLength('type', 16)
            ->requirePresence('type')
            ->allowEmptyString('type', false);

        $validator
            ->integer('user_update_id')
            ->requirePresence('user_update_id')
            ->allowEmpty('user_update_id', false);

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 64)
            ->allowEmptyString('firstname', true);

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 64)
            ->allowEmptyString('lastname', true);

        $validator
            ->integer('gender_id')
            ->allowEmpty('gender_id', true);

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

        $validator
            ->scalar('business_name')
            ->maxLength('business_name', 255)
            ->allowEmptyString('business_name', true);

        $validator
            ->scalar('piva')
            ->maxLength('piva', 32)
            ->allowEmptyString('piva', true);

        $validator
            ->scalar('address_legal')
            ->maxLength('address_legal', 255)
            ->allowEmptyString('address_legal', true);

        $validator
            ->integer('city_id_legal')
            ->allowEmpty('city_id_legal', true);

        $validator
            ->integer('province_id_legal')
            ->allowEmpty('province_id_legal', true);

        $validator
            ->integer('region_id_legal')
            ->allowEmpty('region_id_legal', true);

        $validator
            ->scalar('address_operational')
            ->maxLength('address_operational', 255)
            ->allowEmptyString('address_operational', true);

        $validator
            ->integer('city_id_operational')
            ->allowEmpty('city_id_operational', true);

        $validator
            ->integer('province_id_operational')
            ->allowEmpty('province_id_operational', true);

        $validator
            ->integer('region_id_operational')
            ->allowEmpty('region_id_operational', true);

        $validator
            ->scalar('legal_representative')
            ->maxLength('legal_representative', 255)
            ->allowEmptyString('legal_representative', true);

        $validator
            ->scalar('telephone_legal')
            ->maxLength('telephone_legal', 32)
            ->allowEmptyString('telephone_legal', true);

        $validator
            ->scalar('mobile_legal')
            ->maxLength('mobile_legal', 32)
            ->allowEmptyString('mobile_legal', true);

        $validator
            ->scalar('email_legal')
            ->maxLength('email_legal', 64)
            ->allowEmptyString('email_legal', true);

        $validator
            ->scalar('operational_contact')
            ->maxLength('operational_contact', 255)
            ->allowEmptyString('operational_contact', true);

        $validator
            ->scalar('telephone_operational')
            ->maxLength('telephone_operational', 32)
            ->allowEmptyString('telephone_operational', true);

        $validator
            ->scalar('mobile_operational')
            ->maxLength('mobile_operational', 32)
            ->allowEmptyString('mobile_operational', true);

        $validator
            ->scalar('email_operational')
            ->maxLength('email_operational', 64)
            ->allowEmptyString('email_operational', true);

        return $validator;
    }

}
