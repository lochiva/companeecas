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