<?php
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysInterviewsGuest Entity
 *
 * @property int $id
 * @property int $interview_id
 * @property int $guest_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Surveys\Model\Entity\Interview $interview
 * @property \Surveys\Model\Entity\Guest $guest
 */
class SurveysInterviewsGuest extends Entity
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
        'interview_id' => true,
        'guest_id' => true,
        'created' => true,
        'modified' => true,
        'interview' => true,
        'guest' => true
    ];
}
