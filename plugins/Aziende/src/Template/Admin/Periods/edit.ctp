<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $period->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $period->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Periods'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="periods form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($period,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Period') ?></legend>
        <?php
            echo $this->Form->input('label');
            echo $this->Form->input('start_date');
            echo $this->Form->input('end_date');
            echo $this->Form->input('ordering');
            echo $this->Form->input('visible');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
