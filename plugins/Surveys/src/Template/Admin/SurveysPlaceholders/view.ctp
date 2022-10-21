<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Surveys Placeholder'), ['action' => 'edit', $surveysPlaceholder->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Surveys Placeholder'), ['action' => 'delete', $surveysPlaceholder->id], ['confirm' => __('Are you sure you want to delete # {0}?', $surveysPlaceholder->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Surveys Placeholders'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Surveys Placeholder'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="surveysPlaceholders view large-9 medium-8 columns content">
    <h3><?= h($surveysPlaceholder->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Label') ?></th>
            <td><?= h($surveysPlaceholder->label) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($surveysPlaceholder->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($surveysPlaceholder->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($surveysPlaceholder->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($surveysPlaceholder->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Deleted') ?></th>
            <td><?= $surveysPlaceholder->deleted ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
