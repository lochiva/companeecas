<?php
use Cake\Routing\Router;

//Inclusioni
echo $this->Html->css('Aziende.jquery.tablesorter.pager');
echo $this->Html->css('Aziende.aziende'); 

echo $this->Html->script( 'Aziende.jquery.tablesorter.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.metadata.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.pager.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.widgets.js' );

?>

<script>

    var pathServer = '<?=Router::url('/')?>';

</script>
