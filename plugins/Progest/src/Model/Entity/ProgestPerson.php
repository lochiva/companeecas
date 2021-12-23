<?php
namespace Progest\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProgestPerson Entity
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string $birthtown
 * @property string $birthstate
 * @property \Cake\I18n\Time $birthdate
 * @property string $fiscalcode
 * @property string $gender
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Person extends Entity
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
