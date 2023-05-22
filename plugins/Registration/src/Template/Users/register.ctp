<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Register  (https://www.companee.it)
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
<?= $this->Html->css('Registration.password'); ?>
<?= $this->Html->script('Registration.password', ['block']); ?>
<div class="login-box users form">
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create($user) ?>
  <div class="login-logo" style="background-color:#00a65a;">
    <a href="<?= Router::url('/') ?>"><img src="<?=Router::url('/img/logo_lochiva-companee.png');?>" /></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Registrati al portale</p>
    <form method="post">
      <div class="form-group">
        <div class="input-group">
          <?= $this->Form->input('username', array('class' => 'form-control','placeholder' => 'Username', 'label'=>false, 'style' => 'border-right: 0px;')) ?>
          <div class="input-group-addon"  style="border-left: 0px;">
            <span class="glyphicon glyphicon-user"></span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <?php echo $this->Form->input('password', array('id' => 'newPassword', 'value' => '', 'required' => true, 'label' => false, 'type' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'new-password', 'style' => 'padding: 6px 12px;')) ?>
        <div hidden class="col-md-12" id="divPasswordValidation">
            <b>La password deve contenere:</b><br>
            <span id="lowercaseValidation" class="invalid">almeno una lettera <b>minuscola</b></span><br>
            <span id="uppercaseValidation" class="invalid">almeno una lettera <b>maiuscola</b></span><br>
            <span id="numberValidation" class="invalid">almeno un <b>numero</b></span><br>
            <span id="specialValidation" class="invalid">almeno un <b>carattere speciale</b></span><br>
            <span id="lengthValidation" class="invalid">un minimo di <b>8 caratteri</b></span><br>
        </div>
      </div>
      <div class="form-group">
        <div class="input-group">
          <?php echo $this->Form->input('confirm_password', array('id'=>'confirmPassword', 'value' => '', 'required' => true, 'label' => false, 'type' => 'password', 'class' => 'form-control', 'placeholder' => 'Conferma password', 'autocomplete' => 'new-password', 'style' => 'border-right: 0px;')) ?>
          <div class="input-group-addon" style="border-left: 0px;">
            <span class="glyphicon glyphicon-lock"></span>
          </div>
        </div>
        <div class="col-md-12" id="divCheckPasswordMatch"></div>
      </div>
      <div class="row margin-bottom">
        <div class="col-xs-12">
          <?= $this->Form->button(__('Registrati'), array('id' => 'btnSave', 'class' => 'btn btn-success btn-block btn-flat')); ?>
        </div><!-- /.col -->
      </div>
    </form>
    <a href="<?=Router::url('/registration/home/login');?>">Torna indietro</a>
  </div><!-- /.login-box-body -->
<?= $this->Form->end() ?>
</div><!-- /.login-box -->
