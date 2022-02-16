<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$user = $this->request->session()->read('Auth.User');
?>
<?php //echo $this->Html->script('home'); ?>



<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Home
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a><i class="fa fa-dashboard"></i> Home</a></li>
	</ol>
</section>
<!-- Main content -->
<section class="content">
	<div class="row" style="margin:0;">  
		<!-- Messaggio di benvenuto -->
		<div id="box-benvenuto" class="box box-warning" style="text-align:center; padding:15px;">
			<div class="row" style="margin:0;">  
				<?php if ($user['role'] == 'admin' || ($user['role'] == 'ente' && $this->Utils->isValidEnte($user['id']))) { ?>
					<img src="<?=Router::url('/img/logo_homepage.png');?>" class="logo_centro" />
					<h2>Benvenuta/o!</h2>
					<br />
					<p>Ti diamo il benvenuto su Companee.
				<?php } else { ?>
					<h2>Attenzione!</h2>
					<br />
					<p>Il profilo utente presenta un'anomalia che pregiudica l'utilizzo dell'applicativo. Si prega di contattare l'amministrazione.</p>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if ($user['role'] == 'admin') { ?>
		<!-- Notifiche -->
		<?= $this->element('Aziende.box_notifications', ['notificationsCount' => $notificationsCount, 'notifications' => $notifications]); ?>
	<?php } ?>
</section>