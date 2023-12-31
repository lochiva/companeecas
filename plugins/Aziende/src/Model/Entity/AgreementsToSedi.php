<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Agreements To Sedi  (https://www.companee.it)
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
 * AgreementsToSedi Entity
 *
 * @property int $id
 * @property int $agreement_id
 * @property int $sede_id
 * @property int $capacity
 * @property int $capacity_increment
 * @property bool $active
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Agreement $agreement
 * @property \Aziende\Model\Entity\Sede $sede
 */
class AgreementsToSedi extends Entity
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
        'sede_id' => true,
        'capacity' => true,
        'capacity_increment' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'agreement' => true,
        'sede' => true,
        'agreement_company_id' => true,
        'agreement_company_data' => true
    ];
}
