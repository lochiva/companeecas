<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Nuova Tipologia Uscita Ospite'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="index large-9 medium-8 columns content">
    <h3><?= __('Tipologie Uscite Ospiti') ?></h3>
    <table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('required_request') ?></th>
                <th scope="col"><?= $this->Paginator->sort('required_request_file') ?></th>
                <th scope="col"><?= $this->Paginator->sort('required_request_note') ?></th>
                <th scope="col"><?= $this->Paginator->sort('required_confirmation') ?></th>
                <th scope="col"><?= $this->Paginator->sort('required_file') ?></th>
                <th scope="col"><?= $this->Paginator->sort('required_note') ?></th>
                <th scope="col"><?= $this->Paginator->sort('startable_by_ente') ?></th>
                <th scope="col"><?= $this->Paginator->sort('toSAI') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ente_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modello_decreto') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modello_notifica') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ordering') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Azioni') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guestsExitTypes as $guestsExitType): ?>
            <tr>
                <td><?= $this->Number->format($guestsExitType->id) ?></td>
                <td><?= h($guestsExitType->name) ?></td>
                <td><?= $guestsExitType->required_request ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->required_request_file ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->required_request_note ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->required_confirmation ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->required_file ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->required_note ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->startable_by_ente ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->toSAI ? 'Sì' : 'No' ?></td>
                <td><?= $guestsExitType->tipo->name ?></td>
                <td><?= $guestsExitType->decreto ? $guestsExitType->decreto->title : '' ?></td>
                <td><?= $guestsExitType->notifica ? $guestsExitType->notifica->title : '' ?></td>
                <td><?= $this->Number->format($guestsExitType->ordering) ?></td>
                <td><?= h($guestsExitType->created) ?></td>
                <td><?= h($guestsExitType->modified) ?></td>
                <td class="actions">
                    <a class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'view', $guestsExitType->id]) ?>" title="<?= __('View') ?>"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'edit', $guestsExitType->id]) ?>" title="<?= __('Edit') ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                    <?= $this->Form->create(null,['url' => ['action' => 'delete', $guestsExitType->id], 'style' => 'display:inline;' ]) ?>
                    <button onclick="if (confirm('<?= __('Are you sure you want to delete # {0}?', $guestsExitType->id) ?>')) { document.post_58e39137cc0fa725607991.submit(); } event.returnValue = false; return false;"
                      class="btn btn-xs btn-danger" type="submit"><i class="glyphicon glyphicon-remove"></i></button>
                    <?= $this->Form->end() ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
