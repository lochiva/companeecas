<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = "Companee";
?>
<!DOCTYPE html>
<html>
<head>

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>



    <?= $this->Html->css('bootstrap.min.css') ?>
    <!--<?= $this->Html->css('_all-skins.min.css') ?> NON ESISTE!!!!!-->
    <?= $this->Html->css('skins/skin-yellow.min.css') ?>
    <?= $this->Html->css('../plugins/iCheck/flat/yellow.css') ?>
    <?= $this->Html->css('../plugins/morris/morris.css') ?>
    <?= $this->Html->css('../plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>
    <?= $this->Html->css('../plugins/datepicker/datepicker3.css') ?>
    <?= $this->Html->css('../plugins/daterangepicker/daterangepicker-bs3.css') ?>
    <?= $this->Html->css('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>
	<?= $this->Html->css('../plugins/datatables/dataTables.bootstrap.css') ?>
	<?= $this->Html->css('../plugins/select2/select2.min.css') ?>
    <?= $this->Html->css('AdminLTE.min.css') ?>
    <?= $this->Html->css('companee-style.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('../fonts/ionicons/css/ionicons.min.css') ?>

	<?= $this->Html->script('jquery-2.2.4.min.js') ?>
	<?= $this->Html->script('jquery-ui.min.js') ?>
	<?= $this->Html->script('raphael-min.js') ?>
	<?= $this->Html->script('moment.js') ?>
	<?= $this->Html->script('bootstrap.min.js') ?>
	<!--<?= $this->Html->script('../plugins/morris/morris.min.js') ?> QUI GENERA ERRORI!!!!!!-->
	<?= $this->Html->script('../plugins/sparkline/jquery.sparkline.min.js') ?>
	<?= $this->Html->script('../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') ?>
	<?= $this->Html->script('../plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>
	<?= $this->Html->script('../plugins/knob/jquery.knob.js') ?>
	<?= $this->Html->script('../plugins/daterangepicker/daterangepicker.js') ?>
	<?= $this->Html->script('../plugins/datepicker/bootstrap-datepicker.js') ?>
	<?= $this->Html->script('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>
	<?= $this->Html->script('../plugins/slimScroll/jquery.slimscroll.min.js') ?>
	<?= $this->Html->script('../plugins/fastclick/fastclick.min.js') ?>
	<?= $this->Html->script('../plugins/select2/select2.full.min.js') ?>
	<?= $this->Html->script('../plugins/input-mask/jquery.inputmask.js') ?>
	<?= $this->Html->script('../plugins/input-mask/jquery.inputmask.date.extensions.js') ?>
	<?= $this->Html->script('../plugins/input-mask/jquery.inputmask.extensions.js') ?>
	<?= $this->Html->script('../plugins/timepicker/bootstrap-timepicker.min.js') ?>
	<?= $this->Html->script('app.min.js') ?>
	<!--<?= $this->Html->script('pages/dashboard.js') ?> QUI GENERA ERRORI!!!!!!-->
	<?= $this->Html->script('demo.js') ?>




    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
      });
   </script>
</head>
<body class="hold-transition login-page">
            <?php echo $this->element('path_server'); ?>            

			<?= $this->Flash->render() ?>
			<?= $this->fetch('content') ?>


</body>
</html>
