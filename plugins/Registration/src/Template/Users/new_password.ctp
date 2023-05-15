<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    New Password  (https://www.companee.it)
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
	  	<p class="login-box-msg"><?= __('Inserisci la nuova password') ?></p>
	  	<div class="form-group has-feedback">
	  	 	<?= $this->Form->input('id',['type' => 'hidden']) ?>
	        <?= $this->Form->input('password', array('value' => '', 'class' => 'form-control','placeholder' => 'Nuova password', 'label'=>false)) ?>
	        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
	    </div>
	  	<div class="form-group has-feedback">
	        <?= $this->Form->input('ck_password', array('value' => '', 'type'=>'password', 'class' => 'form-control','placeholder' => 'Ripeti password', 'label'=>false)) ?>
	        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
	    </div>
	    <div class="row margin-bottom">
	        <div class="col-xs-12">
	          <?= $this->Form->button(__('Salva'), array('class' => 'btn btn-success btn-block btn-flat')); ?>
	        </div><!-- /.col -->
	    </div>
  	</div><!-- /.login-box-body -->
<?= $this->Form->end() ?>
</div>
