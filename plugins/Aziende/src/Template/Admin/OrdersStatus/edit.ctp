<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $ordersStatus->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $ordersStatus->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Orders Status'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="ordersStatus form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($ordersStatus,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Orders Status') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
            echo '<div class="input input-group color-picker colorpicker-component">'.
            $this->Form->input('color').
            '<span class="input-group-addon"><i></i></span></div>';
            echo $this->Form->input('selectable');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
