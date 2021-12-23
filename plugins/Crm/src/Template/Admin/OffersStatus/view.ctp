<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Offers Status'), ['action' => 'edit', $offersStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Offers Status'), ['action' => 'delete', $offersStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $offersStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Offers Status'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Offers Status'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="offersStatus view large-9 medium-8 columns content">
    <h3><?= h($offersStatus->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($offersStatus->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Color') ?></th>
            <td><?= h($offersStatus->color) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($offersStatus->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($offersStatus->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($offersStatus->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($offersStatus->modified) ?></td>
        </tr>
    </table>
</div>
