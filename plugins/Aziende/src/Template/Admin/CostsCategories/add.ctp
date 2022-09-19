<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('List Costs Categories'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="costsCategories form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($costsCategory,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Add Costs Category') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
        ?>
        <div class="input text">
        <?php
            
            echo $this->Form->label('description', 'Descrizione');
            echo $this->Form->textarea('description', ['style' => ['width: 500px;', 'resize: none;']]);
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
