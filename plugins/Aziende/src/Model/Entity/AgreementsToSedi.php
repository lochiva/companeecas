<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * AgreementsToSedi Entity
 *
 * @property int $id
 * @property int $agreement_id
 * @property int $sede_id
 * @property float $capacity
 * @property bool $active
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Agreement $agreement
 * @property \Aziende\Model\Entity\Sede $sede
 */
class AgreementsToSedi extends Entity
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
        'agreement_id' => true,
        'sede_id' => true,
        'capacity' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'agreement' => true,
        'sede' => true
    ];
}
