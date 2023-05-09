<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    box search guest  (https://www.companee.it)
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
<?= $this->Html->css('Aziende.guests'); ?>
<?= $this->Html->script('Aziende.guests', ['block']); ?>

<div id="box-search-guest" class="box box-warning">
    <div class="box-header with-border">
        <i class="fa fa-search"></i>
        <h3 class="box-title"><?=__c('Ricerca ospite')?></h3>
    </div>
    <div class="box-body">
        <div id="divSearchGuest" class="col-sm-4">
            <select id="searchGuest" class="select2 form-control"></select>
        </div>
        <div class="col-sm-2">
            <button id="viewGuest" class="btn btn-default">Visualizza ospite</button>
        </div>
    </div>
</div>

<?= $this->element('Aziende.modal_search_guest_selection') ?>