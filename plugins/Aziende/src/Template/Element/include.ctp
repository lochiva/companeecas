<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    include  (https://www.companee.it)
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

echo $this->Html->script( 'Aziende.jquery.tablesorter.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.metadata.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.pager.js' );
echo $this->Html->script( 'Aziende.jquery.tablesorter.widgets.js' );
echo $this->Html->script( 'Aziende.angular/modaleApp.js' );
?>
<style>
<?php if (!empty($sediTipiMinistero)): ?>
  <?php foreach ($sediTipiMinistero as $value): ?>
      .sediTipiMinisteroColor-<?= $value['id'] ?>{
          color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
      .sediTipiMinisteroBG-<?= $value['id'] ?>{
          background-color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
  <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($sediTipiCapitolato)): ?>
  <?php foreach ($sediTipiCapitolato as $value): ?>
      .sediTipiCapitolatoColor-<?= $value['id'] ?>{
          color:<?= empty($value['color']) ? '#000000' : $value['color'] ?>;
      }
      .sediTipiCapitolatoBG-<?= $value['id'] ?>{
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
