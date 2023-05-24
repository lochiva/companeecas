<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Interviews Status  (https://www.companee.it)
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
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysInterviewsStatus Entity
 *
 * @property int $id
 * @property string $name
 * @property int $ordering
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SurveysInterviewsStatus extends Entity
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
        'ordering' => true,
        'created' => true,
        'modified' => true
    ];
}
