<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysPayment Entity
 *
 * @property int $id
 * @property int $survey_id
 *
 * @property \Aziende\Model\Entity\Survey $survey
 */
class SurveysPayment extends Entity
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
        'survey_id' => true,
        'survey' => true
    ];
}
