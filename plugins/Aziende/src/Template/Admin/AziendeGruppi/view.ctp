<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Nodi Gruppi'), ['action' => 'edit', $aziendeGruppi->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Nodi Gruppi'), ['action' => 'delete', $aziendeGruppi->id], ['confirm' => __('Are you sure you want to delete # {0}?', $aziendeGruppi->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Nodi Gruppi'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Nodi Gruppi'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="aziendeGruppi view large-9 medium-8 columns content">
    <h3><?= h($aziendeGruppi->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($aziendeGruppi->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($aziendeGruppi->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($aziendeGruppi->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($aziendeGruppi->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($aziendeGruppi->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($aziendeGruppi->modified) ?></td>
        </tr>
    </table>
</div>
