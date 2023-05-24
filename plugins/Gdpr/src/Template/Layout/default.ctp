<?php
/**
* Gdpr is a plugin for manage attachment
*
* Companee :    Default  (https://www.companee.it)
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
use Cake\Core\Configure;
use Cake\Routing\Router;

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

    <?= $this->Html->css('bootstrap.min.css') ?>
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
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/le-frog/jquery-ui.css">

    <?= $this->Html->script('jquery-2.2.4.min.js') ?>
    <?= $this->Html->script('jquery-ui.min.js') ?>
    <?= $this->Html->script('general.js') ?>
    <?= $this->Html->script('raphael-min.js') ?>
    <?= $this->Html->script('moment.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('../plugins/sparkline/jquery.sparkline.min.js') ?>
    <?= $this->Html->script('../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') ?>
    <?= $this->Html->script('../plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>
    <?= $this->Html->script('../plugins/knob/jquery.knob.js') ?>
    <?= $this->Html->script('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>
    <?= $this->Html->script('../plugins/slimScroll/jquery.slimscroll.min.js') ?>
    <?= $this->Html->script('../plugins/fastclick/fastclick.min.js') ?>
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
    <?= $this->Html->script('../plugins/pace/pace') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->css('Gdpr.gdpr'); ?>
    <?= $this->Html->script('Gdpr.gdpr'); ?>

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

            $('.inputNumber').focusout(function(){
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
        <?= $this->element('path_server') ?>
        <div class="wrappwer">
            <header class="main-header">
              <a href="<?= Router::url('/');?>" class="logo">
                  <!-- LOGO -->
                  <img class="logo-mini text-center" src="<?php echo Router::url('/');?>img/logo_lochiva-companee_xs.png" />
                  <img class="logo-lg" src="<?php echo Router::url('/');?>img/logo_lochiva-companee.png" />
              </a>
              <nav class="navbar navbar-static-top">
              </nav>
            </header>
            <div id="container" class="content-wrapper no-margin-left">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
            <footer class="main-footer no-margin-left">
                <?= $this->element('footer') ?>
            </footer>
        </div>
    </body>
</html>
