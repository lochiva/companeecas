<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('List Police Stations'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="policeStations form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($policeStation,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Add Police Station') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('description');
            echo '<div>';
            echo $this->Form->label('police_station_type_id', 'Tipo');
            echo $this->Form->select('police_station_type_id', $policeStationTypes, ['empty' => 'Selezionare']);
            echo '</div>';
            echo $this->Form->input('ordering');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
