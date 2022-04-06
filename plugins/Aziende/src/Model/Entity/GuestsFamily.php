<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * GuestsFamily Entity
 *
 * @property int $id
 * @property int $family_id
 * @property int $guest_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Guest $guest
 */
class GuestsFamily extends Entity
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
        'family_id' => true,
        'guest_id' => true,
        'created' => true,
        'modified' => true,
        'guest' => true
    ];
}
