<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Presenza Entity
 *
 * @property int $id
 * @property int $guest_id
 * @property \Cake\I18n\Date $date
 * @property int $sede_id
 * @property bool $presente
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Guest $guest
 * @property \Aziende\Model\Entity\Sede $sede
 */
class Presenza extends Entity
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
        'sede_id' => true,
        'presente' => true,
        'created' => true,
        'modified' => true,
        'guest' => true,
        'sede' => true
    ];
}
