<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Recovery Password  (https://www.companee.it)
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
<h1>Procedura di recupero password</h1>
<hr>
<p>Hai recentemente richiesto di reimpostare la password.<br />
Il tuo username è il seguente: <b><?php echo $user; ?></b><br />
Fai clic sul link in basso per iniziare il processo di ripristino password.</p>
<a href="<?=Router::url('/registration/users/newPassword/' . $recoveryCode,true)?>">Recupera password</a>
<p>Se non hai richiesto la modifica della password, puoi ignorare questo messaggio e la password resterà invariata.</p>
