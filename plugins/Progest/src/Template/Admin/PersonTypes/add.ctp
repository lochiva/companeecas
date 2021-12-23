<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('List Person Types'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="personTypes form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($personType,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Add Person Type') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
                    echo '<div class="input input-group color-picker colorpicker-component">'.
                    $this->Form->input('color').
                    '<span class="input-group-addon"><i></i></span></div>';
            ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
