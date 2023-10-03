<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Date;

/**
 * Payment Entity
 *
 * @property int $id
 * @property int $statement_company_id
 * @property float $net_amount
 * @property string $oa_number
 * @property string $os_number
 * @property \Cake\I18n\Date $os_date
 * @property string $billing_reference
 * @property \Cake\I18n\Date $billing_date
 * @property string $protocol
 * @property string $cig
 * @property string $notes
 * @property int $user_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time|null $deleted
 *
 * @property \Aziende\Model\Entity\StatementCompany $statement_company
 * @property \Aziende\Model\Entity\User $user
 */
class Payment extends Entity
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
        'statement_company_id' => true,
        'net_amount' => true,
        'billing_net_amount' => true,
        'oa_number_net' => true,
        'os_number_net' => true,
        'os_date_net' => true,
        'vat_amount' => true,
        'billing_vat_amount' => true,
        'oa_number_vat' => true,
        'os_number_vat' => true,
        'os_date_vat' => true,
        'billing_reference' => true,
        'billing_date' => true,
        'protocol' => true,
        'cig' => true,
        'notes' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'statement_company' => true,
        'user' => true
    ];
}
