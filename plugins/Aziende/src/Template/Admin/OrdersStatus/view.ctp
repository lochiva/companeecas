<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Orders Status'), ['action' => 'edit', $ordersStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Orders Status'), ['action' => 'delete', $ordersStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ordersStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Orders Status'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Orders Status'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ordersStatus view large-9 medium-8 columns content">
    <h3><?= h($ordersStatus->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($ordersStatus->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($ordersStatus->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($ordersStatus->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($ordersStatus->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Selectable') ?></th>
            <td><?= $ordersStatus->selectable ? 'Sì' : 'No' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($ordersStatus->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($ordersStatus->modified) ?></td>
        </tr>
    </table>
</div>
