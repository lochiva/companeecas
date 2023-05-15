<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Recovery Password Sended  (https://www.companee.it)
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
<div class="login-box recovery-password-sended">
    <div class="login-logo" style="background-color:#00a65a;">
      <a href="<?= Router::url('/') ?>"><img src="<?=Router::url('/img/logo_lochiva-companee.png');?>" /></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <?php echo $this->Html->image('Registration.mail_sended.png'); ?>
        <br/>
        <span class="btn-block email-sended"><?=$emailSended?></span>

        <p class="msg1">È stata inviata un'e-mail per la reimpostazione della password</p>
        <p class="msg2">Fai clic sul collegamento riportato nell'e-mail per modificare la password.</p>
        <p class="msg2">Se non vedi l'e-mail, verifica nella cartella della posta indesiderata.</p>
        <div>
            <div class="row margin-bottom">
                <div class="col-sm-8">
                    <a href="<?=Router::url('/registration/users/recoveryPassword')?>">
                        <button class="btn btn-success btn-block btn-flat" >Torna al recupera password</button>
                    </a>
                </div><!-- /.col -->
                <div class="col-sm-4">
                    <a href="<?=Router::url('/registration/home/login')?>">
                        <button class="btn btn-success btn-block btn-flat" >Accedi</button>
                    </a>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
</div>
