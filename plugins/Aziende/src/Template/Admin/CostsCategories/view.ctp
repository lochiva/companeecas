<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Costs Category'), ['action' => 'edit', $costsCategory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Costs Category'), ['action' => 'delete', $costsCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $costsCategory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Costs Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Costs Category'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="costsCategories view large-9 medium-8 columns content">
    <h3><?= h($costsCategory->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($costsCategory->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($costsCategory->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($costsCategory->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($costsCategory->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($costsCategory->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($costsCategory->modified) ?></td>
        </tr>
    </table>
</div>
