<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Credit  (https://www.companee.it)
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
namespace Crediti\Model\Entity;

use Cake\ORM\Entity;

/**
 * Credit Entity.
 */
class Credit extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'azienda_id' => true,
        'cod_sispac' => true,
        'num_documento' => true,
        'data_emissione' => true,
        'data_scadenza' => true,
        'importo' => true,
        'azienda' => true,
    ];
}
