<?php
use Cake\Routing\Router;
?>
<?php $this->assign('title',$title); ?>
<?php echo $this->Element('Aziende.include'); ?>
<?php //echo "<pre>"; print_r($azienda); echo "</pre>"; ?>
<?= $this->Html->script( 'Progest.orders' ); ?>
<?= $this->Html->css( 'Progest.progest' ); ?>
<section class="content-header">
    <h1>
        Buoni d'ordine
        <?php if(is_object($azienda)){ ?>
            <small>gestione ordini <?php echo $azienda->denominazione; ?></small>
        <?php }else{ ?>
            <small>gestione buoni d'ordine</small>
        <?php } ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/aziende/home');?>">Committenti</a></li>
        <li class="active">Gestione Buoni d'ordine</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-orders" class="box box-warning">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title">Elenco dei Buoni d'ordine</h3>
                  <div id="box-general-action"  class=" pull-right">
                    <a class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalOrder" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                    <a class="btn btn-default btn-xs pull-right inserimento-adi" data-toggle="modal" data-target="#myModalOrder" style="margin-left:10px"><i class="fa fa-plus"></i> Inserimento rapido ADI</a>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                  </div>
                </div>
                <div class="box-table-orders box-body">

                        <div id="pager-orders" class="pager col-sm-6">
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
                            <table id="table-orders" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <?php if($idAzienda === 'all'):?>
                                          <th width="10%">Committente</th>
                                        <?php endif ?>
                                        <th width="10%">Tipologia per fatture</th>
                                        <th width="10%">Persona</th>
                                        <th width="20%">Note</th>
                                        <th width="10%">Data inizio</th>
                                        <th width="10%">Data fine</th>
                                        <th width="10%">Data attivazione</th>
                                        <th width="10%">Stato</th>
                                        <th style="min-width: 84px;" data-sorter="false" data-filter="false" ></th>
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

<?php echo $this->Element('Progest.modal/order'); ?>
<?php echo $this->Element('Progest.modal/person'); ?>
