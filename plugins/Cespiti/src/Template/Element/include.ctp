<?php
use Cake\Routing\Router;

//Inclusioni
echo $this->Html->css('Cespiti.jquery.tablesorter.pager');
echo $this->Html->css('Cespiti.cespiti');

echo $this->Html->script( 'Cespiti.jquery.tablesorter.js' );
echo $this->Html->script( 'Cespiti.jquery.tablesorter.metadata.js' );
echo $this->Html->script( 'Cespiti.jquery.tablesorter.pager.js' );
echo $this->Html->script( 'Cespiti.jquery.tablesorter.widgets.js' );

echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.js' );
echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.date.extensions.js' );
echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.extensions.js' );
echo $this->Html->script( '../plugins/colorpicker/bootstrap-colorpicker.min.js' );
echo $this->Html->script( 'app.min.js' );

echo $this->Html->script('Cespiti.function');

echo $this->Html->script('Cespiti.cespiti');

?>
