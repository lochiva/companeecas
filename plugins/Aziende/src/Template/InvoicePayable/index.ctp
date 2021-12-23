<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>
<?= $this->Html->script( 'Aziende.fornitori' ); ?>
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
	              <a class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalFatturaPassiva"><i class="fa fa-plus"></i> Nuovo</a>
	            </div>
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
                <!--
	            <div id="filter-all" class="col-sm-12">

            		<div id="pager-invoice" class="pager col-sm-6">
                        <form>

							<i class="btn btn-default first glyphicon glyphicon-step-backward"></i>
							<i class="btn btn-default prev glyphicon glyphicon-backward"></i>
							<span class="btn pagedisplay"></span>
							<i class="btn btn-default next glyphicon glyphicon-forward"></i>
							<i class="btn btn-default last glyphicon glyphicon-step-forward"/></i>

							<span class="btn">Visualizza</span>
								<select id="num-risultati" class="btn btn-default pagesize form-control">
									<option selected="selected" value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
								</select>
							<span class="btn">risultati</span>

                        </form>
                    </div>
            	</div>-->
            	<div class="clear-both"></div>
                <div class="box-table-invoice box-body">



                        <table id="table-invoicepayable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Fornitore</th>
                                    <th>Destinatario</th>
                                    <th>Scadenza</th>
                                    <th>Stato</th>
                                    <th style="min-width: 60px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#">Alex</a></td>
                                    <td><a href="#">Lochiva</a></td>
                                    <td>18/07/17</td>
                                    <td><span class="badge bg-green">Pagato</span></td>
                                    <td>
                                    	<div class="btn-group"><a class="btn btn-xs btn-default edit" href="#" data-id="5" data-toggle="modal" data-target="#myModalOrder"><i class="fa  fa-pencil"></i></a><a class="btn btn-xs btn-danger delete" href="#" data-id="5"><i class="fa  fa-trash-o"></i></a></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="#">Alex</a></td>
                                    <td><a href="#">Lochiva</a></td>
                                    <td>18/07/17</td>
                                    <td><span class="badge bg-red">Da pagare</span></td>
                                    <td>
                                        <div class="btn-group"><a class="btn btn-xs btn-default edit" href="#" data-id="5" data-toggle="modal" data-target="#myModalOrder"><i class="fa  fa-pencil"></i></a><a class="btn btn-xs btn-danger delete" href="#" data-id="5"><i class="fa  fa-trash-o"></i></a></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('Aziende.modale_nuova_invoicepayable'); ?>
