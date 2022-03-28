<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * GuestsExitType Entity
 *
 * @property int $id
 * @property string $name
 * @property bool $required_confirmation
 * @property bool $required_note
 * @property bool $startable_by_ente
 * @property int $ordering
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class GuestsExitType extends Entity
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
        'required_confirmation' => true,
        'required_note' => true,
        'startable_by_ente' => true,
        'ordering' => true,
        'created' => true,
        'modified' => true
    ];
}
