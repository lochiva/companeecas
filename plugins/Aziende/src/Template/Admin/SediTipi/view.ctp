<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Sedi Tipi'), ['action' => 'edit', $sediTipi->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sedi Tipi'), ['action' => 'delete', $sediTipi->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sediTipi->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sedi Tipi'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sedi Tipi'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sediTipi view large-9 medium-8 columns content">
    <h3><?= h($sediTipi->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Tipo') ?></th>
            <td><?= h($sediTipi->tipo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($sediTipi->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sediTipi->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($sediTipi->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($sediTipi->created) ?></td>
        </tr>
    </table>
</div>
