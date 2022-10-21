<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('List Surveys Placeholders'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="surveysPlaceholders form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($surveysPlaceholder,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Add Surveys Placeholder') ?></legend>
        <?php
            echo $this->Form->input('label');
            echo $this->Form->input('description');
            echo $this->Form->input('deleted');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
