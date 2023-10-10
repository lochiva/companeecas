<?php
/** 
* Companee :  index   (https://www.companee.it)
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
				<?php if (
					$user['role'] == 'admin' || 
					$user['role'] == 'area_iv' || 
					$user['role'] == 'ragioneria' || 
					$user['role'] == 'ragioneria_adm' ||
					$user['role'] == 'questura' ||
					(($user['role'] == 'ente_ospiti' || $user['role'] == 'ente_contabile') && $this->Utils->isValidEnte($user['id']))
				) { ?>
					<?= $this->Html->Image('/img/local/logo_homepage.png',['class'=>'logo_centro']) ?>
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

	<?php if (
		$user['role'] == 'admin' || 
		$user['role'] == 'area_iv' || 
		$user['role'] == 'ragioneria' || 
		$user['role'] == 'ragioneria_adm' ||
		$user['role'] == 'questura' ||
		(($user['role'] == 'ente_ospiti' || $user['role'] == 'ente_contabile' ) && $this->Utils->isValidEnte($user['id'])) 
	) { ?>
		<!-- Ricerca ospite -->
		<?= $this->element('Aziende.box_search_guest'); ?>
	<?php } ?>

	<?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv') { ?>
		<!-- Notifiche -->
		<?= $this->element('Aziende.box_notifications', ['notificationsCount' => $notificationsCount, 'notifications' => $notifications, 'enteType' => 1]); ?>

		<!-- Notifiche Emergenza Ucraina -->
		<?= $this->element('Aziende.box_notifications', ['notificationsCount' => $notificationsUkraineCount, 'notifications' => $notificationsUkraine, 'enteType' => 2]); ?>
	<?php } ?>

	<?php if ($user['role'] == 'admin' || $user['role'] == 'ragioneria' || $user['role'] == 'ragioneria_adm') { ?>
		<!-- Notifiche Ragioneria -->
		<?= $this->element('Aziende.box_statements_notifications', ['notificationsCount' => $statementsNotificationsCount]); ?>
		
	<?php } ?>
</section>