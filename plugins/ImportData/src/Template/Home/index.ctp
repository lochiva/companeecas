<?php
use Cake\Routing\Router;
?>

<?php echo $this->Element('ImportData.include'); ?>

<section class="content-header">
    <h1>Importazione dati</h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Importazione dati</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-import-data" class="box box-warning">
				<div class="box-header with-border">
					<form id="formImportData">
						<div class="col-md-10 form-group">
							<div class="col-md-12 form-group margintop20">
								<div class="col-md-5">
									<label for="table-name">Seleziona Tabella:</label>
						            <select class="form-control" name="table-name" id="table-name">
										<option value=""></option>
						                <?php if(is_array($tables) && !empty($tables)){ ?>
						                    <?php foreach ($tables as $key => $table) { ?>
						                        <option value="<?php echo $key; ?>" ><?php echo $table['tableName']; ?></option>
						                    <?php } ?>
						                <?php }else{ ?>
						                    	<option value="">Nessuna tabella</option>
						                <?php } ?>
						            </select>
								</div>
								<div class="col-md-5 margintop30">
						            <input type="checkbox" id="overwrite" name="overwrite" />
									<label for="overwrite">Elimina vecchio contenuto</label>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<div class="col-md-5">
						            <label for="upload-data">Seleziona File:</label><span class="file-extensions">formati ammessi: .csv</span>
									<input class="form-control" id="upload-data" type="file" name="upload-data" value="" />
								</div>
								<div class="col-md-4 margintop30">
							        <input type="checkbox" id="heading" name="heading" />
									<label for="heading">Prima riga intestazione</label>
								</div>
								<div class="col-md-3">
									<label for="delimiter">Separatore valori file</label>
									<input type="text" class="form-control" id="delimiter" name="delimiter" />
								</div>
								<div class="col-md-12" id="res-file-type" ></div>
							</div>
							<div class="col-md-12 form-group">

							</div>
						</div>
						<div class="col-md-2 form-group">
				            <button class="btn btn-primary btn-pre-elaborazione" id="pre-elaborazione" type="button">PRE ELABORAZIONE</button>
						</div>
					</form>
					<div class="col-md-4 col-md-offset-4 text-center" id="div-configs" hidden>
						<label for="configurations">Seleziona Configurazione:</label>
						<select class="form-control" name="configurations" id="configurations">

						</select>
					</div>
					<div id="data-fields" class="col-md-12">

					</div>
				</div>
			</div>
		</div>
	</div>
</section>
