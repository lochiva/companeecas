<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Status  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/

namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Status Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Guest[] $guests
 * @property \Aziende\Model\Entity\ReportComnapy[] $report_comnapy
 * @property \Aziende\Model\Entity\ReportCompany[] $report_company
 * @property \Aziende\Model\Entity\StatementCompany[] $statement_company
 * @property \Aziende\Model\Entity\Offer[] $offers
 * @property \Aziende\Model\Entity\Order[] $orders
 * @property \Aziende\Model\Entity\SurveysInterview[] $surveys_interviews
 * @property \Aziende\Model\Entity\Survey[] $surveys
 */
class Status extends Entity
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
        'name' => true,
        'created' => true,
        'modified' => true,
        'guests' => true,
        'report_comnapy' => true,
        'report_company' => true,
        'statement_company' => true,
        'offers' => true,
        'orders' => true,
    ];
}
