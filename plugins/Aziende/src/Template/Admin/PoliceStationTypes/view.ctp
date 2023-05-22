<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    View  (https://www.companee.it)
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
        <li><?= $this->Html->link(__('Edit Police Station Type'), ['action' => 'edit', $policeStationType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Police Station Type'), ['action' => 'delete', $policeStationType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policeStationType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Police Station Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Police Station Type'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Police Stations'), ['controller' => 'PoliceStations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Police Station'), ['controller' => 'PoliceStations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="policeStationTypes view large-9 medium-8 columns content">
    <h3><?= h($policeStationType->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($policeStationType->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Label In Letter') ?></th>
            <td><?= h($policeStationType->label_in_letter) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($policeStationType->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($policeStationType->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($policeStationType->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Police Stations') ?></h4>
        <?php if (!empty($policeStationType->police_stations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Police Station Type Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($policeStationType->police_stations as $policeStations): ?>
            <tr>
                <td><?= h($policeStations->id) ?></td>
                <td><?= h($policeStations->name) ?></td>
                <td><?= h($policeStations->description) ?></td>
                <td><?= h($policeStations->police_station_type_id) ?></td>
                <td><?= h($policeStations->created) ?></td>
                <td><?= h($policeStations->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PoliceStations', 'action' => 'view', $policeStations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PoliceStations', 'action' => 'edit', $policeStations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PoliceStations', 'action' => 'delete', $policeStations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $policeStations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
