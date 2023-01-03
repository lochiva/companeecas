<?php
use Cake\Core\Configure;
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
        <?= $cakeDescription ?>: <?= __c($this->fetch('title')) ?>
    </title>
    <?= $this->Html->meta('icon') ?>


    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Material+Icons">
    <?= $this->Html->css('vue-material.min.css') ?>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <!--<?= $this->Html->css('_all-skins.min.css') ?> NON ESISTE!!!!!-->
    <?= $this->Utils->templateCss() ?>
    <?= $this->Html->css('../plugins/morris/morris.css') ?>
    <?= $this->Html->css('../plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>
    <?= $this->Html->css('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>
	<?= $this->Html->css('../plugins/datatables/dataTables.bootstrap.css') ?>
	<?= $this->Html->css('../plugins/select2/select2.min.css') ?>
  <?= $this->Html->css('../plugins/pace/pace.css')?>
    <?= $this->Html->css('AdminLTE.min.css') ?>

  <?= $this->Html->css('../plugins/datepicker/datepicker3.css') ?>
  <?= $this->Html->css('../plugins/daterangepicker/daterangepicker.css') ?>
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('../fonts/ionicons/css/ionicons.min.css') ?>
	<?= $this->Html->css('../plugins/imgareaselect/css/imgareaselect-animated.css')?>
	<?= $this->Html->css('../plugins/reveal/reveal.css')?>
  <?= $this->Html->css('companee-style.css')?>
	<!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/le-frog/jquery-ui.css">-->
  <?= $this->Html->css('../plugins/vue-select/vue-select.min.css') ?>
  <?= $this->Html->css('../plugins/vue-material/vue-material.min.css') ?>



	<?= $this->Html->script('jquery-2.2.4.min.js') ?>
	<?= $this->Html->script('jquery-ui.min.js') ?>
	<?= $this->Html->script('general.js') ?>
	<?= $this->Html->script('raphael-min.js') ?>
	<?= $this->Html->script('moment-with-locales.js') ?>
	<?= $this->Html->script('bootstrap.min.js') ?>
	<!--<?= $this->Html->script('../plugins/morris/morris.min.js') ?> QUI GENERA ERRORI!!!!!!-->
	<?= $this->Html->script('../plugins/sparkline/jquery.sparkline.min.js') ?>
	<?= $this->Html->script('../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') ?>
	<?= $this->Html->script('../plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>
	<?= $this->Html->script('../plugins/knob/jquery.knob.js') ?>
	<?= $this->Html->script('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>
	<?= $this->Html->script('../plugins/slimScroll/jquery.slimscroll.min.js') ?>
	<?= $this->Html->script('../plugins/fastclick/fastclick.min.js') ?>
	<!--<?= $this->Html->script('../plugins/chartjs/Chart.min.js') ?>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
	<?= $this->Html->script('../plugins/select2/select2.full.min.js') ?>
  <?= $this->Html->script('../plugins/select2/i18n/it') ?>
	<?= $this->Html->script('../plugins/input-mask/jquery.inputmask.js') ?>
	<?= $this->Html->script('../plugins/input-mask/jquery.inputmask.date.extensions.js') ?>
	<?= $this->Html->script('../plugins/input-mask/jquery.inputmask.extensions.js') ?>
	<?= $this->Html->script('../plugins/timepicker/bootstrap-timepicker.min.js') ?>
	<?= $this->Html->script('../plugins/imgareaselect/scripts/jquery.imgareaselect.min.js') ?>
	<?= $this->Html->script('app.min.js') ?>
  <?= $this->Html->script('../plugins/daterangepicker/daterangepicker.js') ?>
  <?= $this->Html->script('../plugins/datepicker/bootstrap-datepicker.js') ?>
	<?= $this->Html->script('tablesorter/jquery.tablesorter.js') ?>
	<?= $this->Html->script('tablesorter/jquery.tablesorter.metadata.js') ?>
	<?= $this->Html->script('tablesorter/jquery.tablesorter.pager.js') ?>
	<?= $this->Html->script('tablesorter/jquery.tablesorter.widgets.js') ?>
	<?= $this->Html->script('../plugins/reveal/jquery.reveal.js') ?>
	<!--<?= $this->Html->script('pages/dashboard.js') ?> QUI GENERA ERRORI!!!!!!-->
	<?= $this->Html->script('demo.js') ?>
  <?= $this->Html->script('../plugins/pace/pace') ?>
	<?php // if($this->request->params['plugin'] == "Consulenza"){ echo $this->Html->script('Consulenza.consulenza.js'); } ?>
  <?= $this->Html->script('../plugins/angular/angular.min') ?>
  <?= $this->Html->script('../plugins/vuejs/vue.min.js') ?>
  <?= $this->Html->script('../plugins/axios/axios.min.js') ?>
  <?= $this->Html->script('../plugins/vue-select/vue-select.min.js') ?>
  <?= $this->Html->script('../plugins/vue-material/vue-material.min.js') ?>
  <?= $this->Html->script('../plugins/vue-sortable/Sortable.min.js') ?>
  <?= $this->Html->script('../plugins/vue-draggable/vuedraggable.umd.min.js') ?>
  <?= $this->Html->script('../plugins/vue-intersection-observer/intersection-observer.js') ?>
  <?= $this->Html->script('../plugins/vue-observe-visibility/vue-observe-visibility.min.js') ?>
  <?= $this->Html->script('../plugins/vuejs-datepicker/vuejs-datepicker.js') ?>
  <?= $this->Html->script('../plugins/vuejs-datepicker/it.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <script>
      $(document).ajaxStart(function() { Pace.restart();$('#template-spinner').show(); });
      $(document).ajaxStop(function() {$('#template-spinner').hide(); });
      $(document).ajaxError(function(e,object) {
          if(object.status == 403 || object.status == 401){
              location.reload();
          }
        });

      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        $(".select2noSearch").select2({minimumResultsForSearch: Infinity});

        // Datepicker inputs
        $.datepicker.setDefaults($.datepicker.regional['it']);
        $(".datepicker").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});

        $(document).on('focusout', '.inputNumber', function(){
            $(this).val(calculateStringOperation($(this).val()));
        });
        addSelectPlaceholder();

      });
   </script>
</head>
<body class="skin-<?= h($this->Utils->templateClass()) ?> fixed sidebar-mini<?php if(isset($_COOKIE['sidebar-closed'])) echo " sidebar-collapse"; ?>">
  <style>
    #template-spinner{
      position: fixed;
      z-index: 99999;
      height: 2em;
      width: 2em;
      overflow: show;
      margin: auto;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
    }
  </style>
  <div id="template-spinner" hidden>
    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"  ></i>
  </div>
	<div class="wrappwer">
	    <header class="main-header">
	        <!--<div class="header-title">
	            <span><?= $this->fetch('title') ?></span>
	        </div>

	        <div class="header-help">
	            <span><a target="_blank" href="http://book.cakephp.org/3.0/">Documentation</a></span>
	            <span><a target="_blank" href="http://api.cakephp.org/3.0/">API</a></span>
	        </div>-->
	        <?= $this->element('header') ?>
	    </header>
	    <aside class="main-sidebar" >
	    	<?= $this->element('sidebar') ?>
	    </aside>
	    <div id="container" class="content-wrapper">
			<?= $this->Flash->render() ?>
			<?= $this->fetch('content') ?>
	    </div>
	    <footer class="main-footer">
	    	<?= $this->element('footer') ?>
	    </footer>

		<div class="control-sidebar-bg"></div>
    </div>

    <?= $this->fetch('script-vue') ?>
</body>
</html>
