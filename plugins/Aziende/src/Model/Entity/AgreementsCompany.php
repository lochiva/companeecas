<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * AgreementsCompany Entity
 *
 * @property int $id
 * @property int $agreement_id
 * @property int $aziende_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Agreement $agreement
 * @property \Aziende\Model\Entity\Azienda $aziende
 */
class AgreementsCompany extends Entity
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
        'name' => true,
        'isDefault' => true,
        'created' => true,
        'modified' => true,
        'agreement' => true
    ];
}
