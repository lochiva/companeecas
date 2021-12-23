<?php
use Cake\Routing\Router;
?>

<div class="login-box users form">
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
  <div class="login-logo">
    <a href="../../index2.html"><img src="<?=Router::url('/img/logo_lochiva-companee.png');?>" /></a>
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
    </form>
    <a href="<?=Router::url('/registration/users/recovery_password');?>">Password dimenticata?</a>
    <a class="pull-right" href="<?=Router::url('/registration/users/register');?>">Registra utente</a>
  </div><!-- /.login-box-body -->
<?= $this->Form->end() ?>
</div><!-- /.login-box -->
