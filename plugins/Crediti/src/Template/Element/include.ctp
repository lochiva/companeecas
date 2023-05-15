<?php
/**
* Crediti is a plugin for manage attachment
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
echo $this->Html->css('Aziende.jquery.tablesorter.pager');
echo $this->Html->css('Aziende.aziende');
echo $this->Html->css('Crediti.crediti');

echo $this->Html->script( 'Aziende.jquery.tablesorter.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.metadata.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.pager.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.widgets.js' );

?>

<script>

    var pathServer = '<?=Router::url('/')?>';

</script>
