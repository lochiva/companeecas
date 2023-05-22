<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Auth Email Sended  (https://www.companee.it)
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
use Cake\Core\Configure;

echo $this->Html->css('Registration.style');

?>
<div class="auth-email-sended">
    <?php echo $this->Html->image('Registration.mail_sended.png'); ?>
    <br/>
    <span class="email-sended"><?=$emailSended?></span>

    <p class="msg1">È stata inviata un'e-mail per la conferma del tuo profilo</p>
    <p class="msg2">Fai clic sul collegamento riportato nell'email per confermare ed abilitare la tua utenza.</p>
    <p class="msg2">Se non vedi l'email, verifica nella cartella della posta indesiderata.</p>
    <div>
        <a href="<?=Router::url('/')?>">
            <button class="btn btn-success btn-block btn-flat" >Torna alla Home</button>
        </a>
        <a href="<?=Router::url('/registration/home/login')?>">
            <button class="btn btn-success btn-block btn-flat" >Accedi</button>
        </a>
    </div>
</div>
