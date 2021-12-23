<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Cancella Sesso'),
                ['action' => 'delete', $gender->id],
                ['confirm' => __('Sei sicuro di voler cancellare # {0}?', $gender->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('Lista Sessi'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="genders form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($gender,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Modifica Sesso') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
            echo $this->Form->input('user_text');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Salva'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
