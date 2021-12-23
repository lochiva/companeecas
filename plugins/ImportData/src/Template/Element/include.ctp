<?php
use Cake\Routing\Router;

//Inclusioni
echo $this->Html->css('ImportData.importData');

echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.js' );
echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.date.extensions.js' );
echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.extensions.js' );
echo $this->Html->script( '../plugins/colorpicker/bootstrap-colorpicker.min.js' );
echo $this->Html->script( 'app.min.js' );

echo $this->Html->script('ImportData.function');

echo $this->Html->script('ImportData.importData');

?>
