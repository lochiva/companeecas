<?php
use Cake\Routing\Router;
?>
<div>
    <h1><i class="glyphicon glyphicon-cog"></i> Gestione Configurazioni di sistema</h1>
    <h3>Da questa pagina è possibile gestire tutte le configurazioni di sistema.</h3>
</div>
<hr>
<div class="configurations form add-config">
<?= $this->Form->create($configuration) ?>
    <fieldset>
        <?= $this->Form->input('key_conf') ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('label') ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('tooltip') ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('value') ?>
        <div style="clear:both"></div>
        
   </fieldset>
   <a class="btn btn-warning" href="<?=Router::url('/admin/configurations')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class' => 'btn btn-success']); ?>
    <?= $this->Form->end() ?>
</div>