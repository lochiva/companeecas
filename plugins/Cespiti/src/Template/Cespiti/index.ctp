<?php
use Cake\Routing\Router;
?>

<?php echo $this->Element('Cespiti.include'); ?>

<section class="content-header">
    <h1>Cespiti</h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Cespiti</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-cespiti" class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-list-ul"></i>
                    <h3 class="box-title">Elenco cespiti</h3>
				    <div class="box-table-cespiti box-body">
                        <div id="pager-cespiti" class="pager col-sm-6">
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
							 <a class="btn btn-app" data-toggle="modal" data-target="#myModalCespite"><i class="fa fa-plus"></i>Nuovo</a>
						</div>

                        <div class="table-content">
                            <table id="table-cespiti" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="header-tabella">
										<th>ID azienda</th>
                                        <th>ID fattura passiva</th>
                                        <th>Numero</th>
                                        <th>Descrizione</th>
                                        <th>Stato</th>
										<th>Note</th>
										<th>Cancellato</th>
									</tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" style="text-align: center;">Non ci sono dati</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

						<div class="col-sm-6">
							<nav>
								<ul class="pagination">
									<strong id="num-pagine"></strong>
								<ul>
							</nav>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('modale_nuovo_cespite'); ?>
