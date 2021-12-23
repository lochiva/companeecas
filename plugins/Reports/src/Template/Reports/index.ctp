<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role');
?>
<?php $this->assign('title', 'Segnalazioni') ?>
<?= $this->Html->css('Reports.reports'); ?>
<?= $this->Html->script( 'Reports.reports', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Segnalazioni')?>
        <small>Gestione <?=__c('segnalazioni')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><?=__c('Segnalazioni')?></li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-reports" class="box box-reports">
                <div class="box-header with-border">
                    <i class="fa fa-list-alt"></i>
                    <h3 class="box-title"><?=__c('Lista segnalazioni')?></h3>
                    <a href="<?=Router::url('/reports/reports/add');?>" class="btn btn-info btn-xs pull-right" style="margin-left:10px"><i class="fa fa-plus"></i> Nuova</a>
                    <a href="<?=Router::url('/');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-reports box-body">
                    <div id="pager-reports" class="pager col-sm-6">
                        <form>
                            <i class="first glyphicon glyphicon-step-backward"></i>
                            <i class="prev glyphicon glyphicon-backward"></i>
                            <span class="pagedisplay"></span> 
                            <i class="next glyphicon glyphicon-forward"></i>
                            <i class="last glyphicon glyphicon-step-forward"></i>
                            <select class="pagesize">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-sm-6 table-actions text-right">
                        <?php if($role == 'centro') { ?>
                            Mostra trasferiti <input type="checkbox" id="showTransferAccepted" />
                        <?php } ?>
                        <button id="export-reports" type="button" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Esporta segnalazioni</button>
                    </div>
                    <div class="table-content">
                        <table id="table-reports" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Codice caso</th>
                                    <?php if($role == 'admin' || $role == 'centro') { ?>
                                    <th>Intestatario</th>
                                    <?php } ?>
                                    <th>Segnalante</th>
                                    <th class="filter-select status-filter">Stato</th> 
                                    <th>N. giorni apertura</th> 
                                    <?php foreach($questions as $question){ ?>
                                        <th width="10%"><?= $question['label_table'] ?></th>
                                    <?php } ?>
                                    <th style="min-width: 80px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" style="text-align:center;">Non ci sono segnalazioni</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
