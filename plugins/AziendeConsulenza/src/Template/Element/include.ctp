<?php
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

    <?php if($this->request->controller == "Sedi"){ ?>
        var idAzienda = <?php echo $idAzienda; ?>;
    <?php } ?>

    <?php if($this->request->controller == "Contatti"){ ?>
        var id = <?php echo $id; ?>;
        var tipo = '<?php echo $tipo; ?>';
        var idAzienda = '<?php echo $idAzienda; ?>';
    <?php } ?>

</script>

<?php

echo $this->Html->script('Aziende.function');

if($this->request->controller == "Home"){
    echo $this->Html->script('Aziende.aziende');
}

if($this->request->controller == "Sedi"){
    echo $this->Html->script('Aziende.sedi');
}

if($this->request->controller == "Contatti"){
    echo $this->Html->script('Aziende.contatti');
}
