<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('List Police Station Types'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Police Stations'), ['controller' => 'PoliceStations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Police Station'), ['controller' => 'PoliceStations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="policeStationTypes form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($policeStationType,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Add Police Station Type') ?></legend>
        <?php
            echo $this->Form->input('type');
            echo $this->Form->input('label_in_letter');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
