<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Modifica Tipologia Uscita Ospite'), ['action' => 'edit', $guestsExitType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Cancella Tipologia Uscita Ospite'), ['action' => 'delete', $guestsExitType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $guestsExitType->id)]) ?> </li>
        <li><?= $this->Html->link(__('Lista Tipologia Uscita Ospite'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Nuovo Tipologia Uscita Ospite'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="view large-9 medium-8 columns content">
    <h3><?= h($guestsExitType->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome') ?></th>
            <td><?= h($guestsExitType->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Richiesta conferma') ?></th>
            <td><?= $guestsExitType->required_confirmation ? 'Sì' : 'No' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Richieste note') ?></th>
            <td><?= $guestsExitType->required_note ? 'Sì' : 'No' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Avviabile da ente') ?></th>
            <td><?= $guestsExitType->startable_by_ente ? 'Sì' : 'No' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipologia Ente') ?></th>
            <td><?= $guestsExitType->tipo->name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordinamento') ?></th>
            <td><?= $this->Number->format($guestsExitType->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Creato') ?></th>
            <td><?= h($guestsExitType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modificato') ?></th>
            <td><?= h($guestsExitType->modified) ?></td>
        </tr>
    </table>
</div>
