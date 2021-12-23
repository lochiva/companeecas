<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Grado Parentela'), ['action' => 'edit', $gradoParentela->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Grado Parentela'), ['action' => 'delete', $gradoParentela->id], ['confirm' => __('Are you sure you want to delete # {0}?', $gradoParentela->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Grado Parentela'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Grado Parentela'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="gradoParentela view large-9 medium-8 columns content">
    <h3><?= h($gradoParentela->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($gradoParentela->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($gradoParentela->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($gradoParentela->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($gradoParentela->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($gradoParentela->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($gradoParentela->modified) ?></td>
        </tr>
    </table>
</div>
