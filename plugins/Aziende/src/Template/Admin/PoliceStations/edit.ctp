<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $policeStation->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $policeStation->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Police Stations'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="policeStations form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($policeStation,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Police Station') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('description');
            echo $this->Form->input('police_station_type_id', ['options' => $policeStationTypes]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
