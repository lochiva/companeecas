<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Scadenzario.include'); ?>

<section class="content-header">
    <h1>
        Scadenzario
        <small>Preview Page</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Scadenzario</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-scadenzario" class="box">
                <div class="box-header">
                  <i class="fa fa-list-ul"></i>

                  <h3 class="box-title">Elenco scadenzario </h3>
                </div>
                <div class="box-table-scadenzario box-body">
                        <div id="pager-scadenzario" class="pager col-sm-6">
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

                        <div class="pager col-sm-6" id="box-general-action">
                            Filtra:
                            <select id="filtro-eventi" class="form-control" style="width:150px;display: inline;">
        											<option value="1" selected="selected">Solo eventi futuri</option>
        											<option value="0">Tutti gli eventi</option>
        										</select>
							<a class="btn btn-app" data-toggle="modal" data-target="#myModalScadenzario"><i class="fa fa-plus"></i>Nuovo</a>

						</div>
                        <div class="table-content">
                            <table id="table-scadenzario" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="header-tabella">
                                        <th>Descrizione</th>
                                        <th>Data</th>
                                        <th>Data Eseguito</th>
                                        <th>Note</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">Non ci sono dati</td>
                                    </tr>
                                </tbody>
    							<tr class="header-tabella">
    								<th>Descrizione</th>
    								<th>Data</th>
    								<th>Data Eseguito</th>
    								<th>Note</th>
    								<th></th>
    							</tr>
                            </table>
                        </div>
						<div class="col-sm-6">
							<nav>
								<ul class="pagination">
									<strong id="num-pagine"></strong>
								<ul>
							</nav>
						</div>
						<div class="col-sm-6 text-right">
						</div>


                </div>

            </div>


        </div>
    </div>
</section>

<?php echo $this->Element('Scadenzario.modale_nuova_scadenza'); ?>
