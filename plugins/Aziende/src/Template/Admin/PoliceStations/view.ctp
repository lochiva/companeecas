<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    view  (https://www.companee.it)
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
        <li><?= $this->Html->link(__('Edit Police Station'), ['action' => 'edit', $policeStation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Police Station'), ['action' => 'delete', $policeStation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policeStation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Police Stations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Police Station'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="policeStations view large-9 medium-8 columns content">
    <h3><?= h($policeStation->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($policeStation->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Police Station Type') ?></th>
            <td><?= $policeStation->has('police_station_type') ? $policeStation->police_station_type->type : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $policeStation->ordering ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($policeStation->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($policeStation->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($policeStation->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($policeStation->description)); ?>
    </div>
</div>
