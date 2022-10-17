<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $surveysInterviewsStatus->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $surveysInterviewsStatus->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Surveys Interviews Statuses'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="surveysInterviewsStatuses form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($surveysInterviewsStatus,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Surveys Interviews Status') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
