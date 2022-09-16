<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $costsCategory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $costsCategory->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Costs Categories'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="costsCategories form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($costsCategory,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Costs Category') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
        ?>
        <div class="input text">
        <?php
            
            echo $this->Form->label('description', 'Descrizione');
            echo $this->Form->textarea('description');
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
