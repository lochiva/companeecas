<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Edit  (https://www.companee.it)
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

<script>$(document).ready(function(){ $('.color-picker').colorpicker(); });</script>
<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $period->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $period->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Periods'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="periods form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($period,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Period') ?></legend>
        <?php
            echo $this->Form->input('label');
            echo $this->Form->input('start_date');
            echo $this->Form->input('end_date');
            echo $this->Form->input('ordering');
            echo $this->Form->input('visible');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
