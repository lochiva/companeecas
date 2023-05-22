<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Edit  (https://www.companee.it)
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

<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $aziendeGruppi->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $aziendeGruppi->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Aziende Gruppi'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="aziendeGruppi form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($aziendeGruppi,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Enti Gruppi') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('ordering');
                    echo '<div class="input input-group color-picker colorpicker-component">'.
                    $this->Form->input('color').
                    '<span class="input-group-addon"><i></i></span></div>';
            ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
