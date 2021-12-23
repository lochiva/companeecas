<?php
use Cake\Routing\Router;
?>
<div>
    <h1><i class="glyphicon glyphicon-log-in"></i> Gestione Gruppi</h1>
    <h3>Da questa pagina è possibile gestire i gruppi del portale.</h3>
</div>
<hr>
<div class="users form add-user">
<?= $this->Form->create($group) ?>
    <fieldset>
        <?= $this->Form->input('name') ?>
        <?= $this->Form->input('note')?>
   </fieldset>
   <a class="btn btn-warning" href="<?=Router::url('/admin/groups')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class' => 'btn btn-success']); ?>
    <?= $this->Form->end() ?>
</div>
