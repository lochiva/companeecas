<?php
namespace Cespiti\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cespiti Entity
 *
 * @property int $id
 * @property int $id_azienda
 * @property int $id_fattura_passiva
 * @property string $num
 * @property string $descrizione
 * @property bool $stato
 * @property string $note
 * @property bool $delete
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Cespiti extends Entity
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
