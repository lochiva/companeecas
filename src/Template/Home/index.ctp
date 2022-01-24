<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
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

	<?php if($this->request->session()->read('Auth.User.role') == 'admin' || $this->request->session()->read('Auth.User.role') == 'centro'){ ?>

	<div class="row" style="margin:0;">    
		<!-- Messaggio di benvenuto -->
		<div id="box-benvenuto" class="box box-warning" style="text-align:center; padding:15px;">
			<img src="<?=Router::url('/img/logo_homepage.png');?>" class="logo_centro" />
			<h2>Benvenuta/o!</h2>
			<br />
			<p>Ti diamo il benvenuto su Companee.
		</div>
	</div>

	<?php }elseif($this->request->session()->read('Auth.User.role') == 'nodo'){ ?>
	<div class="row" style="margin:0;">    
		<!-- Messaggio di benvenuto -->
		<div id="box-benvenuto" class="box box-warning" style="text-align:center; padding:15px;">
			<?php if($this->Utils->isValidUserNodo($this->request->session()->read('Auth.User.id'))){ ?>
				<?php if($base64 = $this->Utils->hasNodoLogo($this->request->session()->read('Auth.User.id'))){ ?>
					<img src="<?=$base64?>" class="logo-nodo-home" />
				<?php }else{ ?>
					<div>
						<div class="div-logo-image">
							<img src="<?=Router::url('/img/logo.png');?>" height="140" class="logo_nodo" />
						</div>
						<div class="div-logo-text">
							<span>
								<strong>Nodo provinciale di <?= $provincia ?></strong><br />
								rete regionale <strong>contro <br />
								le discriminazioni</strong> in Piemonte
							</span>
						</div>
					</div>
				<?php } ?>
				<h2>Benvenuta/o!</h2>
				<br />
				<p>Ti diamo il benvenuto su Companee.</p>
			<?php } else { ?>
				<h2>Attenzione!</h2>
				<br />
				<p>Il profilo utente presenta un'anomalia che pregiudica l'utilizzo dell'applicativo. Si prega di contattare l'amministrazione.</p>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

</section>