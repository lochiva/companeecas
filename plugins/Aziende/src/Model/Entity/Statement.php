<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Statement Entity
 *
 * @property int $id
 * @property int $agreement_id
 * @property int $period_id
 * @property string $period_label
 * @property \Cake\I18n\Date $period_start_date
 * @property \Cake\I18n\Date $period_end_date
 *
 * @property \Aziende\Model\Entity\Agreement $agreement
 * @property \aziende\Model\Entity\Period $period
 * @property \aziende\Model\Entity\StatementCompany[] $statement_company
 */
class Statement extends Entity
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
        'period_id' => true,
        'period_label' => true,
        'period_start_date' => true,
        'period_end_date' => true,
        'agreement' => true,
        'period' => true,
        'deleted' => true,
        'companies' => true
    ];
}
