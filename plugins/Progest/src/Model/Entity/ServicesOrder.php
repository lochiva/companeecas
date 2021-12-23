<?php
namespace Progest\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProgestServicesOrder Entity
 *
 * @property int $id
 * @property int $id_order
 * @property int $id_service
 * @property float $ore
 * @property float $ore_festive
 * @property string $dettaglio
 * @property int $fle_orario
 * @property int $fle_giorni
 * @property int $fle_operatore
 * @property int $id_apl
 * @property bool $cell
 * @property bool $chiavi
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class ServicesOrder extends Entity
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
        'id' => true,
        'id_order' => true,
    ];
}
