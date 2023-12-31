<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Login  (https://www.companee.it)
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
<style>
body.login-page{
  <?= 'background: url("'.$background.'") no-repeat center center fixed;'; ?>
  -moz-background-size: cover;
  -webkit-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style>
<div class="login-box users form">
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
  <div class="login-logo" style="margin-top:200px" >
    <!-- aggiunto margine e rimossa img logo <a href="<?= Router::url('/') ?>"><?=$this->Html->Image('/img/local/logo_cover.png', [ 'width'=>"360", 'alt'=>'logo'] );?></a> -->
  </div><!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Accedi per iniziare la sessione</p>
    <form action="../../index2.html" method="post">
      <div class="form-group has-feedback">
        <?= $this->Form->input('username', array('class' => 'form-control','placeholder' => 'Username', 'label'=>false)) ?>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <?= $this->Form->input('password', array('class' => 'form-control','placeholder' => 'Password', 'label'=>false)) ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row margin-bottom">
        <div class="col-xs-12">
          <?= $this->Form->button(__('Login'), array('class' => 'btn btn-success btn-block btn-flat')); ?>
        </div><!-- /.col -->
      </div>
      <div class="form-group margin-bottom">
        <?= $this->Form->input('remember_me', array('type'=> 'checkbox', 'label' => 'Resta connesso')) ?>
      </div>
    </form>
    <?php if($enabledPasswordRecovery){ ?>
      <a href="<?=Router::url('/registration/users/recovery_password');?>">Password dimenticata?</a>
    <?php } ?>
    
    <?php if($enabledRegistration){ ?>
        <a class="pull-right" href="<?=Router::url('/registration/users/register');?>">Registra utente</a>
    <?php } ?>
    
    <?php if ($enabledGoogleLogin) { ?>
      <hr>
      <a class="btn btn-block google btn-primary btn-flat" href="<?= $this->Url->build(['controller' => 'google', 'action' => 'googlelogin']); ?>"> 
        <i class="fa fa-google modal-icons"></i> Accedi con Google
      </a>
    <?php } ?>
    <?php if ($enabledVerifyData) { ?>
      <?= $this->element('Gdpr.modal_verify_data'); ?>
    <?php } ?>
  </div><!-- /.login-box-body -->
<?= $this->Form->end() ?>
</div><!-- /.login-box -->
<div class="login-footer" >
  <img src="<?=Router::url('/img/stringa_loghi_.jpg');?>" />
</div><!-- /.login-footer -->