<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * GuestsNotification Entity
 *
 * @property int $id
 * @property int $type_id
 * @property int $azienda_id
 * @property int $sede_id
 * @property int $guest_id
 * @property int $user_maker_id
 * @property string $text
 * @property int|null $user_done_id
 * @property bool $done
 * @property \Cake\I18n\Date|null $done_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Type $type
 * @property \Aziende\Model\Entity\Azienda $azienda
 * @property \Aziende\Model\Entity\Sede $sede
 * @property \Aziende\Model\Entity\Guest $guest
 * @property \Aziende\Model\Entity\UserMaker $user_maker
 * @property \Aziende\Model\Entity\UserDone $user_done
 */
class GuestsNotification extends Entity
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
        'type_id' => true,
        'azienda_id' => true,
        'sede_id' => true,
        'guest_id' => true,
        'user_maker_id' => true,
        'text' => true,
        'user_done_id' => true,
        'done' => true,
        'done_date' => true,
        'created' => true,
        'modified' => true,
        'type' => true,
        'azienda' => true,
        'sede' => true,
        'guest' => true,
        'user_maker' => true,
        'user_done' => true
    ];
}
