<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Person Type'), ['action' => 'edit', $personType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Person Type'), ['action' => 'delete', $personType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $personType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Person Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Person Type'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="personTypes view large-9 medium-8 columns content">
    <h3><?= h($personType->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($personType->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($personType->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($personType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($personType->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($personType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($personType->modified) ?></td>
        </tr>
    </table>
</div>
