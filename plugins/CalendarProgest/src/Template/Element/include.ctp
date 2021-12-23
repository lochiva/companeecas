<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
//Inclusioni css
echo $this->Html->css('Calendar.fullcalendar.min');
//echo $this->Html->css('Calendar.fullcalendar.print');
echo $this->Html->css('Calendar.bootstrap-timepicker');
echo $this->Html->css('Calendar.bootstrap-colorpicker.min');
echo $this->Html->css('Calendar.bootstrap-colorselector');
echo $this->Html->css('Calendar.calendar');


//Inclusioni js (ad eccezione di quello dedicato che è dopo lo script per avere a disposizione le variabili inizializzate dal php)
echo $this->Html->script('Calendar.fullcalendar.min');
echo $this->Html->script('Calendar.fullcalendar-rightclick');
echo $this->Html->script('Calendar.locale-all');
echo $this->Html->script('Calendar.bootstrap-timepicker');
echo $this->Html->script('Calendar.bootstrap-colorpicker.min');
echo $this->Html->script('Calendar.bootstrap-colorselector');
echo $this->Html->script('Calendar.jquery.inputmask');
echo $this->Html->script('Calendar.jquery.inputmask.date.extensions');
echo $this->Html->script('Calendar.jquery.inputmask.extensions');

?>

<script>

    var pathServer = '<?=Router::url('/')?>';
    var idTagScadenzario  = <?=Configure::read('dbconfig.scadenzario.TAG')?>;
    var defaultEventDuration = '<?= Configure::read('dbconfig.calendar.DEFAULT_DURATION') ?>';

</script>

<?php
echo $this->Html->script('Calendar.calendar');
echo $this->Html->script('Calendar.pianificazione');
?>
