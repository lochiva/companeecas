<?php
use Cake\Routing\Router;
################################################################################
#
# Companee :   add (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################
?>
<div>
    <h1><i class="glyphicon glyphicon-cog"></i> Gestione Configurazioni di sistema</h1>
    <h3>Da questa pagina è possibile gestire tutte le configurazioni di sistema.</h3>
</div>
<hr>
<div class="configurations form add-config">
<?= $this->Form->create($configuration) ?>
    <fieldset>
        <?= $this->Form->input('key_conf') ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('label') ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('tooltip') ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('value') ?>
        <div style="clear:both"></div>
        
   </fieldset>
   <a class="btn btn-warning" href="<?=Router::url('/admin/configurations')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class' => 'btn btn-success']); ?>
    <?= $this->Form->end() ?>
</div>