<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * PoliceStationType Entity
 *
 * @property int $id
 * @property string $type
 * @property string $label_in_letter
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\PoliceStation[] $police_stations
 */
class PoliceStationType extends Entity
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
        'label_in_letter' => true,
        'created' => true,
        'modified' => true,
        'police_stations' => true
    ];
}
