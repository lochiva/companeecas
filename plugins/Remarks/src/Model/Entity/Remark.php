<?php
/**
* Remarks is a plugin for manage attachment
*
* Companee :    Remark  (https://www.companee.it)
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
namespace Remarks\Model\Entity;

use Cake\ORM\Entity;

/**
 * Remark Entity
 *
 * @property int $id
 * @property int $reference_id
 * @property string $remark
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Remarks\Model\Entity\Reference $reference
 */
class Remark extends Entity
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
        'user_id' => true,
        'reference' => true,
        'reference_id' => true,
        'remark' => true,
        'rating' => true,
        'visibility' => true,
        'attachment' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true
    ];
}
