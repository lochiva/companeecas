<?php
/**
* Cespiti is a plugin for manage attachment
*
* Companee :    Include  (https://www.companee.it)
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
