<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 

$this->assign('title',$title);
echo $this->Element('Aziende.include'); 
echo $this->Html->script( 'Aziende.statements.js' );

?>

<section class="content-header">
    <h1>
        <?=__c('Rendiconti')?>
        <small>Gestione <?=__c('rendicontinti')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Gestione rendiconti</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-statements" class="box box-info">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title"><?=__c('Elenco degli rendiconti')?></h3>
                  <?php if ($role == 'ente_contabile') { ?>
                    <a id="box-general-action" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#modalStatement" data-backdrop="false" data-keyboard="false" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                  <?php } ?>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-statements box-body">

                    <div id="pager-statements" class="pager col-sm-6">
                        <form>
                            <i class="first glyphicon glyphicon-step-backward"></i>
                            <i class="prev glyphicon glyphicon-backward"></i>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <i class="next glyphicon glyphicon-forward"></i>
                            <i class="last glyphicon glyphicon-step-forward"/></i>
                            <select class="pagesize">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                            </select>
                        </form>
                    </div>


                    <div class="table-content">
                        <table id="table-statements" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="20%">Ente</th>
                                    <th width="10%">CIG</th>
                                    <th width="10%">Periodo</th>
                                    <th width="10%">Stato</th>
                                    <th width="20%">Data approvazione</th>
                                    <th width="7%" class="filters-reset"></th>

                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->element('modale_nuovo_statement'); ?>
