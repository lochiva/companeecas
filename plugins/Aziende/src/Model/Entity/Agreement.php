<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Agreement Entity
 *
 * @property int $id
 * @property int $procedure_id
 * @property \Cake\I18n\Time $date_agreement
 * @property \Cake\I18n\Time $date_agreement_expiration
 * @property \Cake\I18n\Time|null $date_extension_expiration
 * @property float $guest_daily_price
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Procedure $procedure
 * @property \Aziende\Model\Entity\AgreementsToSedi[] $agreements_to_sedi
 */
class Agreement extends Entity
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
        'procedure_id' => true,
        'date_agreement' => true,
        'date_agreement_expiration' => true,
        'date_extension_expiration' => true,
        'guest_daily_price' => true,
        'created' => true,
        'modified' => true,
        'procedure' => true,
        'agreements_to_sedi' => true
    ];
}
