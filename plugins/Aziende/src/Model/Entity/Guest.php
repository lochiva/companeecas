<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Guest  (https://www.companee.it)
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
 * Guest Entity.
 */
class Guest extends Entity
{
    protected $_accessible = [
        '*' => true,
    ];

    public function cleanDirty($param)
    {
        if(is_array($param)){
          foreach($param as $val){
              unset($this->_dirty[$val]);
          }
        }else{
             unset($this->_dirty[$param]);
        }
    }

}