<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Statement Company  (https://www.companee.it)
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
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * StatementCompany Entity
 *
 * @property int $id
 * @property int $statement_id
 * @property int $company_id
 * @property \Cake\I18n\Date $billing_date
 * @property string $billing_reference
 * @property float $billing_net_amount
 * @property float $billing_vat_amount
 * @property int $status_id
 * @property \Cake\I18n\Time $approved_date
 * @property string $uploaded_path
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \aziende\Model\Entity\Statement $statement
 * @property \Aziende\Model\Entity\AgreementsCompany $agreements_company
 * @property \aziende\Model\Entity\Status $status
 */
class StatementCompany extends Entity
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
        'company_id' => true,
        'billing_date' => true,
        'billing_reference' => true,
        'billing_net_amount' => true,
        'billing_vat_amount' => true,
        'status_id' => true,
        'approved_date' => true,
        'uploaded_path' => true,
        'filename' => true,
        'created' => true,
        'modified' => true,
        'statement' => true,
        'agreements_company' => true,
        'status' => true,
        'compliance' => true,
        'compliance_filename' => true,
        'notifications' => true,
        'due_date' => true
    ];
}
