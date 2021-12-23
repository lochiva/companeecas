<?php
namespace Reports\Model\Entity;

use Cake\ORM\Entity;

/**
 * Victim Entity
 */
class Victim extends Entity
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
        'created' => true,
        'modified' => true
    ];
}
