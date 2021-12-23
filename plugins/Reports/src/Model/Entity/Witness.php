<?php
namespace Reports\Model\Entity;

use Cake\ORM\Entity;

/**
 * Witness Entity
 */
class Witness extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => true,
        'type' => true,
        'user_update_id' => true,
        'firstname' => true,
        'lastname' => true,
        'gender_id' => true,
        'gender_user_text' => true,
        'country_id' => true,
        'birth_year' => true,
        'citizenship_id' => true,
        'citizenship_user_text' => true,
        'educational_qualification_id' => true,
        'educational_qualification_user_text' => true,
        'religion_id' => true,
        'religion_user_text' => true,
        'type_occupation_id' => true,
        'type_occupation_user_text' => true,
        'marital_status_id' => true,
        'marital_status_user_text' => true,
        'in_italy_from_year' => true,
        'residency_permit_id' => true,
        'residency_permit_user_text' => true,
        'lives_with' => true,
        'telephone' => true,
        'mobile' => true,
        'email' => true,
        'city_id' => true,
        'province_id' => true,
        'region_id' => true,
        'business_name' => true, 
        'piva' => true, 
        'address_legal' => true, 
        'city_id_legal' => true,
        'province_id_legal' => true,
        'region_id_legal' => true,
        'address_operational' => true, 
        'city_id_operational' => true,
        'province_id_operational' => true,
        'region_id_operational' => true,
        'legal_representative' => true,
        'telephone_legal' => true,
        'mobile_legal' => true,
        'email_legal' => true,
        'operational_contact' => true,
        'telephone_operational' => true,
        'mobile_operational' => true,
        'email_operational' => true,
        'created' => true,
        'modified' => true
    ];
}
