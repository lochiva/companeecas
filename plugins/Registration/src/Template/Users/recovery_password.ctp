<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Recovery Password  (https://www.companee.it)
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
