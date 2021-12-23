<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>

<section class="content-header">
    <h1>
        Fatture passive
        <small>Elenco gestione fatture passive dei fornitori</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/aziende/home');?>">Aziende</a></li>
        <li class="active">Gestione Fatture passive</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-invoice" class="box box-danger">
            	<div class="box-header with-border">
	              <i class="fa fa-list-ul"></i>
	              <h3 class="box-title">Elenco Fatture Passive</h3>
	              <a class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalFatturaPassiva"  style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
	            </div>

                <div class="box-table-invoice box-body">
    	            <div id="pager-invoice" class="pager col-sm-6">
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
                        <table id="table-invoicepayable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <?php if($idFornitore === 'all'):?>
                                      <th>Fornitore</th>
                                    <?php endif ?>
                                    <th>Destinatario</th>
                                    <th>Numero</th>
                                    <th>Data</th>
                                    <th>Descrizione</th>
                                    <th>Causale</th>
                                    <th>Importo da pagare</th>
                                    <th>Scadenza</th>
                                    <th data-filter="false">Allegato</th>
                                    <th>Pagato</th>
                                    <th style="min-width: 110px" data-sorter="false" data-filter="false" ></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('Aziende.modale_nuova_invoicepayable'); ?>
