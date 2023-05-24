<?php
/** 
* Companee :  index2   (https://www.companee.it)
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
use Cake\Routing\Router;
?>
<div>
    <h1>Questa pagina richiede l'autenticazione per potervi accedere</h1>
    <ul>
        <li>
            <a href="<?=Router::url('/home/index');?>">Torna alla Home del sito</a>
        </li>
        <li>
            <a href="<?=Router::url('/users/add');?>">Pagina di registrazione di Front End</a><br/>
        </li>
        <li>
            <a href="<?=Router::url('/users/logout');?>">Pagina di logout</a><br/>
        </li>
    </ul>
</div>