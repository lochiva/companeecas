<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Sispac   (https://www.companee.it)
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
namespace ReminderManager\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class SispacComponent extends Component
{

  public function getSispacFromFileName($filename){

    $a = substr($filename,0,-4);

    $codSispac = $a;

    return $codSispac;

  }


}
