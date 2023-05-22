<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    App  (https://www.companee.it)
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

namespace Document\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{

    protected function lockWrite()
    {
        $lockFile = ROOT.DS.'src'.DS.'files'.DS.'lockDoc';
        if(file_exists($lockFile)){
            return false;
        }else{
            touch($lockFile);
            return true;
        }

    }

    protected function unlockWrite()
    {
        $lockFile = ROOT.DS.'src'.DS.'files'.DS.'lockDoc';
        unlink($lockFile);
    }

}
