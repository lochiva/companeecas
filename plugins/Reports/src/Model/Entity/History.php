<?php
namespace Reports\Model\Entity;

use Cake\ORM\Entity;

/**
 * History Entity
 */
class History extends Entity
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
        'report_id' => true,
        'node_id' => true,
        'date' => true,
        'event' => true,
        'motivation' => true,
        'outcome_id' => true,
        'message' => true,
        'created' => true
    ];
}
