<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * GuestsHistory Entity
 *
 * @property int $id
 * @property int $guest_id
 * @property int $azienda_id
 * @property int $sede_id
 * @property int $operator_id
 * @property \Cake\I18n\Date|null $operation_date
 * @property int $guest_status_id
 * @property int|null $exit_type_id
 * @property int $cloned_guest_id
 * @property int $destination_id
 * @property int $provenance_id
 * @property string $note
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Guest $guest
 * @property \Aziende\Model\Entity\Azienda $azienda
 * @property \Aziende\Model\Entity\Sede $sede
 * @property \Model\Entity\User $operator
 * @property \Aziende\Model\Entity\GuestStatus $guest_status
 * @property \Aziende\Model\Entity\GuestsExitType $exit_type
 * @property \Aziende\Model\Entity\Guest $cloned_guest
 * @property \Aziende\Model\Entity\Sede $destination
 */
class GuestsHistory extends Entity
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
        'guest_id' => true,
        'azienda_id' => true,
        'sede_id' => true,
        'operator_id' => true,
        'operation_date' => true,
        'guest_status_id' => true,
        'exit_type_id' => true,
        'cloned_guest_id' => true,
        'destination_id' => true,
        'provenance_id' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'guest' => true,
        'azienda' => true,
        'sede' => true,
        'operator' => true,
        'guest_status' => true,
        'exit_type' => true,
        'cloned_guest' => true,
        'destination' => true
    ];
}
