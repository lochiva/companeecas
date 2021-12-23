<?php
use Cake\Routing\Router;
?>
<?php $this->assign('title', 'Interviste') ?>
<?= $this->Html->css('Leads.leads'); ?>
<?= $this->Html->script( 'Leads.leads', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Gestione Interviste')?>
        <small>Da questa sezione è possibile le interviste</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><?=__c('Gestione interviste')?></li>
    </ol>
</section>

<div class="padding15">
    <?= $this->Flash->render() ?>
</div>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-interviews" class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-list-o"></i>
                  <h3 class="box-title"><?=__c('Elenco delle interviste')?></h3>
                  <a href="" id="addInterview" class="btn btn-info btn-xs pull-right" style="margin-left:10px" data-toggle="modal" data-target="#modalInterview"><i class="fa fa-plus"></i> Nuova</a>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-interviews box-body">

                    <div id="pager-interviews" class="pager col-sm-6">
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
                        <table id="table-interviews" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Azienda</th>
                                    <th>Contatto</th>
                                    <th>Ensemble</th>
                                    <th>Nome</th>
                                    <th>Creazione</th>
                                    <th width="100px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" style="text-align:center;">Caricamento...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Leads.modal_interview') ?>