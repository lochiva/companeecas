<?php
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysInterview Entity
 *
 * @property int $id
 * @property int $id_survey
 * @property int $id_azienda
 * @property int $id_user
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SurveysInterview extends Entity
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
        'id_azienda' => true,
        'id_sede' => true,
        'id_user' => true,
        'title' => true,
        'subtitle' => true,
        'description' => true,
        'status' => true,
        'not_valid' => true,
        'signature_date' => true,
        'cloned_by' => true,
        'version' => true,
        'created' => true,
        'modified' => true
    ];
}
