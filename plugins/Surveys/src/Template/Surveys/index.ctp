<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role');
?>
<?php $this->assign('title', 'Modelli') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->script( 'Surveys.surveys', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Modelli')?>
        <small>Gestione <?=__c('modelli')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><?=__c('Modelli')?></li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-surveys" class="box box-surveys">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista modelli')?></h3>
                  <a href="<?=Router::url('/surveys/surveys/add');?>" id="box-general-action" class="btn btn-info btn-xs pull-right" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-surveys box-body">

                    <div id="pager-surveys" class="pager col-sm-6">
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
                        <table id="table-surveys" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Titolo</th>
                                    <th>Sottotitolo</th>
                                    <th>Descrizione</th>
                                    <th class="filter-select filter-onlyAvail status-filter">Stato</th>
                                    <th width="130px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="text-align:center;">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
