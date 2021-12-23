<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Modifica Sesso'), ['action' => 'edit', $gender->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Cancella Sesso'), ['action' => 'delete', $gender->id], ['confirm' => __('Sei sicuro di voler cancellare # {0}?', $gender->id)]) ?> </li>
        <li><?= $this->Html->link(__('Lista Sessi'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Nuovo Sesso'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="genders view large-9 medium-8 columns content">
    <h3><?= h($gender->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($gender->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($gender->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= h($gender->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Text') ?></th>
            <td><?= h($gender->user_text) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($gender->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($gender->modified) ?></td>
        </tr>
    </table>
</div>
