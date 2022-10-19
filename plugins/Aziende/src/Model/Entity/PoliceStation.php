<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * PoliceStation Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $police_station_type_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\PoliceStationType $police_station_type
 */
class PoliceStation extends Entity
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
        'description' => true,
        'police_station_type_id' => true,
        'created' => true,
        'modified' => true,
        'police_station_type' => true,
        'ordering' => true
    ];
}
