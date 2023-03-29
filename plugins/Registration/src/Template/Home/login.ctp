<?php
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
  <div class="login-logo" >
    <a href="<?= Router::url('/') ?>"><?=$this->Html->Image('/img/local/logo_cover.png', [ 'width'=>"360", 'alt'=>'logo'] );?></a>
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
