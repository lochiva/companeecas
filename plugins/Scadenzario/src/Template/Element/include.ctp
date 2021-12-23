<?php
use Cake\Routing\Router;

//Inclusioni
echo $this->Html->css('Scadenzario.jquery.tablesorter.pager'); 
echo $this->Html->css('Scadenzario.scadenzario'); 

echo $this->Html->script( 'Scadenzario.jquery.tablesorter.js' ); 
echo $this->Html->script( 'Scadenzario.jquery.tablesorter.metadata.js' ); 
echo $this->Html->script( 'Scadenzario.jquery.tablesorter.pager.js' ); 
echo $this->Html->script( 'Scadenzario.jquery.tablesorter.widgets.js' ); 

echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.js' ); 
echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.date.extensions.js' ); 
echo $this->Html->script( '../plugins/input-mask/jquery.inputmask.extensions.js' ); 
echo $this->Html->script( '../plugins/colorpicker/bootstrap-colorpicker.min.js' ); 
echo $this->Html->script( 'app.min.js' );
?>

<script>

	$(document).ready(function(){
	   $("#inputData").inputmask("d/m/y",{ "placeholder": "dd/mm/yyyy" });
	   $("#inputDataEseguito").inputmask("d/m/y",{ "placeholder": "dd/mm/yyyy" });
	});

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

echo $this->Html->script('Scadenzario.function'); 

if($this->request->controller == "Home"){
    echo $this->Html->script('Scadenzario.scadenzario'); 
}

/*
if($this->request->controller == "Sedi"){ 
    echo $this->Html->script('Aziende.sedi'); 
} 

if($this->request->controller == "Contatti"){ 
    echo $this->Html->script('Aziende.contatti'); 
} 	
*/