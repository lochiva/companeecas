<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Statements Notification (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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
