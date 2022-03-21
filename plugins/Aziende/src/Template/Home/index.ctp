<?php
use Cake\Routing\Router;
?>
<?php $this->assign('title',$title) ?>
<?php echo $this->Element('Aziende.include'); ?>
<?= $this->Html->script( 'Aziende.aziende' ); ?>
<section class="content-header">
    <h1>
        <?=__c('Enti')?>
        <small>Gestione <?=__c('enti')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Gestione enti</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-aziende" class="box box-info">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title"><?=__c('Elenco degli Enti')?></h3>
                  <a id="box-general-action" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalAzienda" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-aziende box-body">

                    <div id="pager-aziende" class="pager col-sm-6">
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
                        <table id="table-aziende" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Denominazione</th>
                                    <!--<th width="15%">Nome e Cognome</th>-->
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Sito Web</th>
                                    <th>Posti</th>
                                    <!--<th width="10%">Partita IVA</th>-->
									<!--<th width="10%">Codice Destinatario</th>-->
                                    <th style="width: 130px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Remarks.modal_remarks_by_id'); ?>
<?= $this->element('AttachmentManager.modal_attachment'); ?>
<?php echo $this->Element('Aziende.modale_nuova_azienda'); ?>
