<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Aziende  (https://www.companee.it)
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
 * Aziende Entity.
 */
class Aziende extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'denominazione' => true,
        'cognome' => true,
        'nome' => true,
        'famiglia' => true,
        'cod_paese' => true,
        'piva' => true,
        'cf' => true,
        'cod_eori' => true,
        'cod_sispac' => true,
        'telefono' => true,
        'email_info' => true,
        'email_contabilita' => true,
        'email_solleciti' => true,
        'fax' => true,
        'cliente' => true,
        'fornitore' => true,
    ];
}
