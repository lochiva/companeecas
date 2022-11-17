<?php
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * Survey Entity
 *
 * @property int $id
 * @property int $id_configurator
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $status
 * @property string $yes_no_questions
 * @property string $version
 * @property \Cake\I18n\Date $valid_from
 * @property int $cloned_by
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Survey extends Entity
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
        'id_configurator' => true,
        'title' => true,
        'subtitle' => true,
        'description' => true,
        'status' => true,
        'yes_no_questions' => true,
        'version' => true,
        'valid_from' => true,
        'cloned_by' => true,
        'created' => true,
        'modified' => true
    ];

    protected function _getFullTitle()
    {
        $title = $this->title;
        if ($this->status == 3) {
            $title = $title . '*';
        }
        return $title;
    }
}
