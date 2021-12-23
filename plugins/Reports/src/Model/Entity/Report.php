<?php
namespace Reports\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity
 */
class Report extends Entity
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
        'code' => true,
        'province_code' => true,
        'type_reporter' => true,
        'victim_id' => true,
        'witness_id' => true,
        'interview_id' => true,
        'node_id' => true,
        'user_create_id' => true,
        'user_update_id' => true,
        'status' => true,
        'opening_date' => true,
        'closing_date' => true,
        'closing_outcome_id' => true,
        'transfer_date' => true,
        'created' => true,
        'modified' => true
    ];
}
