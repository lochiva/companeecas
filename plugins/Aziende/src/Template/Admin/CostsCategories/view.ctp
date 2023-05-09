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
        <li><?= $this->Html->link(__('Edit Costs Category'), ['action' => 'edit', $costsCategory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Costs Category'), ['action' => 'delete', $costsCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $costsCategory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Costs Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Costs Category'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="costsCategories view large-9 medium-8 columns content">
    <h3><?= h($costsCategory->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($costsCategory->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($costsCategory->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ordering') ?></th>
            <td><?= $this->Number->format($costsCategory->ordering) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($costsCategory->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($costsCategory->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($costsCategory->modified) ?></td>
        </tr>
    </table>
</div>
