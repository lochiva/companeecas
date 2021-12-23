<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sediTipi->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sediTipi->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Sedi Tipi'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="sediTipi form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($sediTipi,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Sedi Tipi') ?></legend>
        <?php
            echo $this->Form->input('tipo');
            echo $this->Form->input('ordering');
                    echo '<div class="input input-group color-picker colorpicker-component">'.
                    $this->Form->input('color').
                    '<span class="input-group-addon"><i></i></span></div>';
            ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
