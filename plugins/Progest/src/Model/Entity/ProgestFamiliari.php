<?php
namespace Progest\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProgestFamiliari Entity
 *
 * @property int $id
 * @property int $id_person
 * @property string $name
 * @property string $surname
 * @property string $id_grado_parentela
 * @property string $tel
 * @property string $cell
 * @property string $email
 * @property string $address
 * @property string $comune
 * @property string $cap
 * @property string $provincia
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ProgestFamiliari extends Entity
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
        '*' => true,
        'id' => false
    ];
}
