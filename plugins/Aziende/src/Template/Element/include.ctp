<?php
use Cake\Routing\Router;

//Inclusioni
echo $this->Html->css('Aziende.jquery.tablesorter.pager');
echo $this->Html->css('Aziende.aziende');

echo $this->Html->script( 'Aziende.jquery.tablesorter.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.metadata.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.pager.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.widgets.js' );
echo $this->Html->script( 'Aziende.angular/modaleApp.js' );
?>
<style>
<?php if (!empty($sediTipi)): ?>
  <?php foreach ($sediTipi as $value): ?>
      .sediTipiColor-<?= $value['id'] ?>{
          color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
      .sediTipiBG-<?= $value['id'] ?>{
          background-color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
  <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($ruoli)): ?>
  <?php foreach ($ruoli as $value): ?>
      .ruoliColor-<?= $value['id'] ?>{
          color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
      .ruoliBG-<?= $value['id'] ?>{
          background-color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
  <?php endforeach; ?>
<?php endif; ?>

</style>
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

    <?php if($this->request->controller == "Orders"){ ?>
        var idAzienda = '<?php echo $idAzienda; ?>';
    <?php } ?>

    <?php if($this->request->controller == "Fornitori"){ ?>
        var idFornitore = '<?php echo $idFornitore; ?>';
    <?php } ?>

    <?php if($this->request->controller == "Clienti"){ ?>
        var idCliente = '<?php echo $idCliente; ?>';
    <?php } ?>

    <?php if($this->request->controller == "Aziende"){ ?>
        var idFornitore = '<?php echo $idAzienda; ?>';
        var idCliente = '<?php echo $idAzienda; ?>';
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

if($this->request->controller == "Orders"){
    echo $this->Html->script('Aziende.orders');
}

if($this->request->controller == "Fornitori"){
    echo $this->Html->script('Aziende.fornitori');
}

if($this->request->controller == "Clienti"){
    echo $this->Html->script('Aziende.clienti');
}
