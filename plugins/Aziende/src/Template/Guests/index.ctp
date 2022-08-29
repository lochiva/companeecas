<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 
?>
<script>
    var sede_id = '<?= $sede['id'] ?>';
    var statuses = JSON.parse('<?= json_encode($statuses) ?>');
    var exitRequestStatuses = JSON.parse('<?= json_encode($exitRequestStatuses) ?>');
    var statusesList = [];
    Object.keys(statuses).forEach(function(key) {
        if (statuses[key].id == 1) {
            statusesList.push(statuses[key].name);
            Object.keys(exitRequestStatuses).forEach(function(k) {
                var label = statuses[key].name + ' - ' + exitRequestStatuses[k].name;
                statusesList.push(label);
            });
        } else {
            statusesList.push(statuses[key].name);
        }
    }); 
</script>
<?php $this->assign('title', 'Ospiti') ?>
<?= $this->Html->css('Aziende.guests'); ?>
<?= $this->Html->script('Aziende.guests', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Ente '.$azienda['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['provincia']['s_prv'].')')?>
        <small>Gestione <?=__c('ospiti')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <?php if ($role == 'admin') { ?>
        <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
        <?php } ?>
        <li><a href="<?=Router::url('/aziende/sedi/index/'.$azienda['id']);?>">Strutture</a></li>
        <li class="active">Gestione ospiti</li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-guests" class="box box-guests">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista ospiti per la struttura '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['provincia']['s_prv'].') dell\'ente '.$azienda['denominazione'])?></h3>
                  <span hidden class="warning-out-of-spots">Posti esauriti</span>
                  <?php if ($azienda['id_tipo'] == 1) { ?>
                    <a href="<?=Router::url('/aziende/sedi/presenze?sede='.$sede['id']);?>" id="sediPresenze" class="btn btn-primary btn-xs pull-right" style="margin-left:10px"><i class="fa fa-calendar"></i> Presenze</a>
                  <?php } ?>
                  <a href="<?=Router::url('/aziende/guests/guest?sede='.$sede['id'].'&guest=');?>" id="newGuest" style="margin-left:10px"
                    class="btn btn-info btn-xs pull-right <?= $sede['operativita'] == 0 ? 'disabled-anchor' : '' ?>"
                    <?= $sede['operativita'] == 0 ? 'data-toggle="tooltip" data-placement="top" title="La sede è chiusa pertanto non è possibile aggiungere ospiti"' : '' ?>>
                    <i class="fa fa-plus"></i> Nuovo
                  </a>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-guests box-body">

                    <div id="pager-guests" class="pager col-sm-6">
                        <form>
                            <i class="first glyphicon glyphicon-step-backward"></i>
                            <i class="prev glyphicon glyphicon-backward"></i>
                            <span class="pagedisplay"></span> 
                            <i class="next glyphicon glyphicon-forward"></i>
                            <i class="last glyphicon glyphicon-step-forward"></i>
                            <select class="pagesize">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </form>
                    </div>

                    <div class="table-actions col-sm-6">
                        <input id="showOld" type="checkbox"> Mostra anche ospiti non più presenti in struttura
                    </div>

                    <div class="table-content">
                        <table id="table-guests" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="8%">Check-in</th>
                                    <?php if ($azienda['id_tipo'] == 1) { ?>
                                        <th width="8%">CUI</th>
                                        <th width="8%">ID Vestanet</th>
                                    <?php } ?>
                                    <th width="8%">Nome</th>
                                    <th width="8%">Cognome</th>
                                    <th width="8%">Data di nascita</th>
                                    <th width="5%" class="filter-select filter-sex">Sesso</th>
                                    <th width="8%">Nazionalità</th>
                                    <?php if ($azienda['id_tipo'] == 1) { ?>
                                        <th width="8%" class="filter-select filter-draft">Stato bozza</th>
                                        <th width="8%">Scadenza stato bozza</th>
                                        <th width="5%" class="filter-select filter-suspended">Sospeso</th>
                                    <?php } ?>
                                    <th width="8%" class="filter-select filter-status">Stato</th>
                                    <th width="70px" class="filters-reset" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
