<?php
use Cake\Routing\Router;
?>


<div class="login-box users form">
<?= $this->Form->create($user) ?>
  <div class="login-logo" style="background-color:#00a65a;">
    <a href="<?= Router::url('/') ?>"><img src="<?=Router::url('/img/logo_lochiva-companee.png');?>" /></a>
  </div><!-- /.login-logo -->
  <div class="login-box-body">
  	<p class="login-box-msg"><?= __('Inserisci la mail con cui ti sei registrato') ?></p>
  	 <div class="form-group has-feedback">
        <?= $this->Form->input('email', array('class' => 'form-control','placeholder' => 'Email', 'label'=>false)) ?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row margin-bottom">
        <div class="col-xs-12">
          <?= $this->Form->button(__('Invia Richiesta'), array('class' => 'btn btn-success btn-block btn-flat')); ?>
        </div><!-- /.col -->
      </div>
        <a href="<?=Router::url('/registration/home/login');?>">Torna alla pagina di login</a>
  </div><!-- /.login-box-body -->
<?= $this->Form->end() ?>
</div><!-- /.login-box -->
