<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Auth Email  (https://www.companee.it)
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
<h1>Procedura di autenticazione della email</h1>
<hr>
<p>Gentile utente, grazie per esserti registrato. Clicca sul link seguente per confermare la tua email e completare la tua procedura di registrazione.</p>
<a href="<?=Router::url('/registration/users/authEmail/' . $authCode,true)?>">Conferma</a>
<p>Se non sei tu ad esserti registrato al nostro portale, puoi ignorare questo messaggio.</p>
