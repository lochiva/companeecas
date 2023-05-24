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

$registrationType = Configure::read('dbconfig.registration.REGISTRATION_TYPE') ?? 0 ;
$connectionTimetask = Configure::read('dbconfig.calendar.TIMETASK_CONNECTION') ?? 0 ;

?>
<?= $this->Html->css('Registration.password'); ?>
<?= $this->Html->script('Registration.password', ['block']); ?>
<div>
    <h1><i class="glyphicon glyphicon-user"></i> Gestione Utenti</h1>
    <h3>Da questa pagina è possibile gestire tutti i dati di registrazione inerenti gli utenti.</h3>
</div>
<hr>
<div class="users form add-user">
<?= $this->Form->create($user) ?>
    <fieldset>
        <?= $this->Form->input('username') ?>
        <?php echo $this->Form->input('password', array('id' => 'newPassword', 'value' => '', 'required' => false, 'type' => 'password', 'autocomplete' => 'new-password')) ?>
        <div hidden class="col-md-12" id="divPasswordValidation">
            <b>La password deve contenere:</b><br>
            <span id="lowercaseValidation" class="invalid">almeno una lettera <b>minuscola</b></span><br>
            <span id="uppercaseValidation" class="invalid">almeno una lettera <b>maiuscola</b></span><br>
            <span id="numberValidation" class="invalid">almeno un <b>numero</b></span><br>
            <span id="specialValidation" class="invalid">almeno un <b>carattere speciale</b></span><br>
            <span id="lengthValidation" class="invalid">un minimo di <b>8 caratteri</b></span><br>
        </div>
        <?php echo $this->Form->input('confirm_password', array('id'=>'confirmPassword', 'value' => '', 'required' => false, 'type' => 'password', 'autocomplete' => 'new-password')) ?>
        <div class="col-md-12" id="divCheckPasswordMatch"></div>
        <?= $this->Form->input('email') ?>
        <?= $this->Form->input('role', [
            'options' => [ 'admin' => 'Admin' , 'area_iv' => 'Area IV', 'ragioneria' => 'Ragioneria', 'ente_ospiti' => 'Ente ospiti', 'ente_contabile' => 'Ente contabile'],
            'default' => 'ente_ospiti'
        ]) ?>
        <?= $this->Form->input('level',['type' => 'number', 'value' => 0]) ?>
        <?= $this->Form->input('auth_email', ['type' => 'checkbox', 'checked' => 'checked', 'label' => ['text' => "Autenticato"]]) ?>
        <?php if($connectionTimetask == '1'){ ?>
        <?php echo $this->Form->input('timetask_token', ['label' => ['text' => "Token Timetask"]]); ?>
        <?php } ?>
        <?php

        switch ($registrationType) {
            case '1':
                //Registrazione con anagrafica
                echo $this->Element('user_anag_consulenza');
            break;

            case '0':
            default:
                //Registrazione base solo con email, username e password.
            break;
        }

        ?>
   </fieldset>
   <a class="btn btn-warning" href="<?=Router::url('/admin/registration/Users')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class' => 'btn btn-success', 'id' => 'btnSave']); ?>
    <?= $this->Form->end() ?>
</div>
