<?php
/**
* Surveys is a plugin for manage attachment
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
        <li><?= $this->Html->link(__('Edit Surveys Status'), ['action' => 'edit', $surveysStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Surveys Status'), ['action' => 'delete', $surveysStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $surveysStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Surveys Statuses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Surveys Status'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="surveysStatuses view large-9 medium-8 columns content">
    <h3><?= h($surveysStatus->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($surveysStatus->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($surveysStatus->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($surveysStatus->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($surveysStatus->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($surveysStatus->modified) ?></td>
        </tr>
    </table>
</div>
