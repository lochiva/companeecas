<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    index  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
?>

<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('New Police Station'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="policeStations index large-9 medium-8 columns content">
    <h3><?= __('Police Stations') ?></h3>
    <table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('police_station_type_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ordering') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Azioni') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($policeStations as $policeStation): ?>
            <tr>
                <td><?= $this->Number->format($policeStation->id) ?></td>
              <td><?= h($policeStation->name) ?></td>
                <td><?= $policeStation->has('type') ? $policeStation->type->type : '' ?></td>
                <td><?= $policeStation->ordering ?></td>
              <td><?= h($policeStation->created) ?></td>
              <td><?= h($policeStation->modified) ?></td>
                <td class="actions">
                    <a class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'view', $policeStation->id]) ?>" title="<?= __('View') ?>"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-xs btn-primary" href="<?= $this->Url->build(['action' => 'edit', $policeStation->id]) ?>" title="<?= __('Edit') ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                    <?= $this->Form->create(null,['url' => ['action' => 'delete', $policeStation->id], 'style' => 'display:inline;' ]) ?>
                    <button onclick="if (confirm('<?= __('Are you sure you want to delete # {0}?', $policeStation->id) ?>')) { document.post_58e39137cc0fa725607991.submit(); } event.returnValue = false; return false;"
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
