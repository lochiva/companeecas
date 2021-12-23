<?php
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
