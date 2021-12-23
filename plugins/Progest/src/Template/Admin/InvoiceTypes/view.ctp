<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Invoice Type'), ['action' => 'edit', $invoiceType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Invoice Type'), ['action' => 'delete', $invoiceType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $invoiceType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Invoice Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Invoice Type'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="invoiceTypes view large-9 medium-8 columns content">
    <h3><?= h($invoiceType->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($invoiceType->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($invoiceType->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($invoiceType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($invoiceType->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($invoiceType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($invoiceType->modified) ?></td>
        </tr>
    </table>
</div>
