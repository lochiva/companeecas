<?php
use Cake\Routing\Router;
?>
<?php $this->assign('title', 'Ensemble') ?>
<?= $this->Html->css('Leads.leads'); ?>
<?= $this->Html->script( 'Leads.leads', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Gestione Ensemble')?>
        <small>Da questa sezione è possibile gli ensemble delle domande</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><?=__c('Gestione ensemble')?></li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-ensembles" class="box box-danger">
                <div class="box-header with-border">
                  <i class="fa fa-list-o"></i>
                  <h3 class="box-title"><?=__c('Elenco degli ensemble')?></h3>
                  <a href="" id="addEnsemble" class="btn btn-info btn-xs pull-right" style="margin-left:10px" data-toggle="modal" data-target="#modalEnsemble"><i class="fa fa-plus"></i> Nuovo</a>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-ensembles box-body">

                    <div id="pager-ensembles" class="pager col-sm-6">
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


                    <div class="table-content">
                        <table id="table-ensembles" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrizione</th>
                                    <th class="filter-select">Attivo</th>
                                    <th>N° domande</th> 
                                    <th width="100px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="text-align:center;">Caricamento...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Leads.modal_ensemble'); ?>
<?= $this->element('Leads.modal_questions'); ?>