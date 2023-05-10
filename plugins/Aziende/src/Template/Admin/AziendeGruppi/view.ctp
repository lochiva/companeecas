<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    View  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
?>

<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Edit Enti Gruppi'), ['action' => 'edit', $aziendeGruppi->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Enti Gruppi'), ['action' => 'delete', $aziendeGruppi->id], ['confirm' => __('Are you sure you want to delete # {0}?', $aziendeGruppi->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Enti Gruppi'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Enti Gruppi'), ['action' => 'add']) ?> </li>
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
