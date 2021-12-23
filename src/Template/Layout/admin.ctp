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

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin</title>

    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('dashboard.css') ?>
    <?= $this->Html->css('admin.css') ?>
    <?= $this->Html->css('../plugins/select2/select2.min.css') ?>
    <?= $this->Html->css('../plugins/datepicker/datepicker3.css') ?>
      <?= $this->Html->css('AdminLTE.min.css') ?>
      <?= $this->Html->css('font-awesome.min.css') ?>
      <?= $this->Html->css('../fonts/ionicons/css/ionicons.min.css') ?>
    <?= $this->Html->css('../plugins/imgareaselect/css/imgareaselect-animated.css')?>
    <?= $this->Html->css('../plugins/colorpicker/bootstrap-colorpicker.min.css') ?>

    <?= $this->Html->script('jquery-2.2.4.min.js') ?>
    <?= $this->Html->script('jquery-ui.min.js') ?>
    <?= $this->Html->script('general.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('../plugins/colorpicker/bootstrap-colorpicker.min.js') ?>
    <?= $this->Html->script('tablesorter/jquery.tablesorter') ?>
    <?= $this->Html->script('tablesorter/jquery.metadata') ?>
    <?= $this->Html->script('tablesorter/jquery.tablesorter.pager') ?>
    <?= $this->Html->script('tablesorter/jquery.tablesorter.widgets') ?>
    <?= $this->Html->script('../plugins/select2/select2.full.min.js') ?>
    <?= $this->Html->script('../plugins/input-mask/jquery.inputmask.js') ?>
    <?= $this->Html->script('../plugins/input-mask/jquery.inputmask.date.extensions.js') ?>
    <?= $this->Html->script('../plugins/input-mask/jquery.inputmask.extensions.js') ?>
    <?= $this->Html->script('../plugins/timepicker/bootstrap-timepicker.min.js') ?>
    <?= $this->Html->script('../plugins/imgareaselect/scripts/jquery.imgareaselect.min.js') ?>
    <?= $this->Html->script('app.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

    <?= $this->Element('admin_header');?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <?= $this->Element('admin_menu');?>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </div>

    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('ie10-viewport-bug-workaround.js') ?>

</body>
</html>
