<?php
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysAnswerData Entity
 *
 * @property int $id
 * @property int $interview_id
 * @property int $question_id
 * @property string $value
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SurveysAnswerData extends Entity
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
        'question_id' => true,
        'value' => true,
        'options' => true,
        'type' => true,
        'final_value' => true,
        'created' => true,
        'modified' => true
    ];
}
