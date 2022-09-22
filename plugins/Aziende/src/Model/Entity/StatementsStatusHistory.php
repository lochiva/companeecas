<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * StatementsStatusHistory Entity
 *
 * @property int $id
 * @property int $statement_company_id
 * @property int $user_id
 * @property int $status_id
 * @property string $note
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\StatementCompany $statement_company
 * @property \Registration\Model\Entity\User $user
 * @property \Aziende\Model\Entity\Status $status
 */
class StatementsStatusHistory extends Entity
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
        'user_id' => true,
        'status_id' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'statement_company' => true,
        'user' => true,
        'status' => true
    ];
}
