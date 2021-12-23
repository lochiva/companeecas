<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Companee: <?= __c($this->fetch('title')) ?></title>

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
<style>
body{
  padding-top: 0px;
}
</style>
<body>
<?= $this->fetch('content') ?>

<?= $this->Html->script('bootstrap.min.js') ?>
<?= $this->Html->script('ie10-viewport-bug-workaround.js') ?>

</body>
</html>
