<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Cost  (https://www.companee.it)
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
 * Cost Entity
 *
 * @property int $id
 * @property int $statement_company
 * @property int $category_id
 * @property float $amount
 * @property float $share
 * @property string $attachment
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \aziende\Model\Entity\CostsCategory $costs_category
 */
class Cost extends Entity
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
        'statement_company' => true,
        'category_id' => true,
        'amount' => true,
        'share' => true,
        'attachment' => true,
        'filename' => true,
        'created' => true,
        'modified' => true,
        'costs_category' => true,
        'deleted' => true,
        'description' => true,
        'supplier' => true,
        'number' => true,
        'date' => true,
        'notes' => true,
    ];
}
