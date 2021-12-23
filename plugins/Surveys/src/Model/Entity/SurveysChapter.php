<?php
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysChapter Entity
 *
 * @property int $id
 * @property int $id_survey
 * @property int $chapter
 * @property int $chapter_data
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SurveysChapter extends Entity
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
        'id_survey' => true,
        'chapter' => true,
        'chapter_data' => true,
        'created' => true,
        'modified' => true
    ];
}
