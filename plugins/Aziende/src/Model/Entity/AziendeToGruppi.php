<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * AziendeToGruppi Entity
 *
 * @property int $id
 * @property int $id_gruppo
 * @property int $id_azienda
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class AziendeToGruppi extends Entity
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
