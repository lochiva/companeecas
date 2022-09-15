<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * GuestsExitRequestStatus Entity
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class GuestsExitRequestStatus extends Entity
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
        'name' => true,
        'color' => true,
        'created' => true,
        'modified' => true
    ];
}