<?php
use Cake\Routing\Router;
?>
<?php // echo $this->Element('Aziende.include'); ?>
<?php //$this->Html->script( 'Aziende.aziende' );

echo $this->Html->css('Ficgtw.jquery.tablesorter.pager');
echo $this->Html->css('Ficgtw.ficgtw');

echo $this->Html->script( 'Ficgtw.jquery.tablesorter.js' );
echo $this->Html->script( 'Ficgtw.jquery.tablesorter.metadata.js' );
echo $this->Html->script( 'Ficgtw.jquery.tablesorter.pager.js' );
echo $this->Html->script( 'Ficgtw.jquery.tablesorter.widgets.js' );
echo $this->Html->script( 'Ficgtw.angular/modaleApp.js' );
echo $this->Html->script( 'Ficgtw.modaleApp.js' );
echo $this->Html->script( 'Ficgtw.function.js' );

?>
<section class="content-header">
    <h1>
        <?=__c('Fatture in Cloud')?>
        <small>Gestione <?=__c('fornitori')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/ficgtw/home');?>"><?=__c('Fatture in Cloud')?></a></li>
        <li class="active">Gestione <?=__c('Fornitori')?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-aziende" class="box box-info">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title"><?=__c('Aggiunta fornitori')?></h3>
                  <a id="box-general-action" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModalCliente" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                </div>

                <?php /*
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
                                    <th width="15%">Denominazione</th>
                                    <th width="20%">Nome e Cognome</th>
                                    <th width="10%">Telefono</th>
                                    <th width="17%">Email</th>
                                    <th width="17%">Sito Web</th>
                                    <th width="10%">Partita IVA</th>
                                    <th style="min-width: 110px;"></th>
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

                */ ?>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('Ficgtw.modale_nuovo_fornitore'); ?>
