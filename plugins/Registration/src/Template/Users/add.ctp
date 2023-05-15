<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Add  (https://www.companee.it)
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

$registrationType = Configure::read('dbconfig.registration.REGISTRATION_TYPE');

?>
<div class="users form">
<?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Registra nuovo utente') ?></legend>
        <?= $this->Form->input('email') ?>
        <?= $this->Form->input('username') ?>
        <?= $this->Form->input('password') ?>

        <?php

        switch ($registrationType) {
            case '1':
                //Registrazione con anagrafica
                echo $this->Element('user_anag_fe');
            break;

            case '0':
            default:
                //Registrazione base solo con email, username e password.
            break;
        }

        ?>

   </fieldset>
<?= $this->Form->button(__('Submit')); ?>
<?= $this->Form->end() ?>
</div>
<div>
    <ul>
        <li>
            <a href="<?=Router::url('/home/index');?>">Torna alla Home del sito</a>
        </li>
    </ul>
</div>
