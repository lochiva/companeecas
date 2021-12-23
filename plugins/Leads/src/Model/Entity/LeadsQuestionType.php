<?php
namespace Leads\Model\Entity;

use Cake\ORM\Entity;

/**
 * LeadsQuestionType Entity
 *
 * @property int $id
 * @property string $type
 * @property string $label
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class LeadsQuestionType extends Entity
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
        'type' => true,
        'label' => true,
        'created' => true,
        'modified' => true
    ];
}
