<?php
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysQuestionMetadata Entity
 *
 * @property int $id
 * @property int $survey_id
 * @property int $question_id
 * @property bool $show_in_table
 * @property string $short_label
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SurveysQuestionMetadata extends Entity
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
        'question_id' => true,
        'show_in_table' => true,
        'show_in_export' => true,
        'label_table' => true,
        'label_export' => true,
        'created' => true,
        'modified' => true
    ];
}
