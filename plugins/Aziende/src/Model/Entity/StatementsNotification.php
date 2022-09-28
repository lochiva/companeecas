<?php
namespace aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * StatementsNotification Entity
 *
 * @property int $id
 * @property int $statement_id
 * @property int $statement_company_id
 * @property int $user_maker_id
 * @property int $user_done_id
 * @property bool $done
 * @property \Cake\I18n\Date $done_date
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Statement $statement
 * @property \aziende\Model\Entity\StatementCompany $statement_company
 * @property \aziende\Model\Entity\UserMaker $user_maker
 * @property \aziende\Model\Entity\UserDone $user_done
 */
class StatementsNotification extends Entity
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
        'statement_id' => true,
        'statement_company_id' => true,
        'user_maker_id' => true,
        'user_done_id' => true,
        'done' => true,
        'done_date' => true,
        'created' => true,
        'modified' => true,
        'statement' => true,
        'statement_company' => true,
        'user_maker' => true,
        'user_done' => true
    ];
}
