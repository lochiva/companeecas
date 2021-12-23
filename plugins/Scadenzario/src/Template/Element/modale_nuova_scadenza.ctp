<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Scadenzario.modale_nuova_scadenza'); ?>

<div class="modal fade" id="myModalScadenzario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuova Scadenza</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="box-body">

                        <div class="form-group ">
                            <label class="col-sm-2 control-label required" for="inputDescrizione">Descrizione</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="idScadenzario" id="idScadenzario" >
                                <input type="hidden" name="idEvent" id="idEvent" >
                                <input type="text" placeholder="Descrizione" name="Descrizione" id="inputDescrizione" class="form-control required" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputData">Data</label>
                            <div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" name="Data" id="inputData" class="form-control focus.inputmask" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="">
								</div>
							</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputDataEseguito">Data Eseguito</label>
                            <div class="col-sm-10">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" name="DataEseguito" id="inputDataEseguito" class="form-control focus.inputmask" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="">
								</div>
							</div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputNote">Note</label>
                            <div class="col-sm-10">
                                <textarea name="Note" id="inputNote" class="form-control"></textarea>
                            </div>
                        </div>

                    </div><!-- /.box-body
                    <div class="box-footer">
                        <button class="btn btn-default" type="submit">Cancel</button>
                        <button class="btn btn-info pull-right" type="submit">Sign in</button>
                    </div><!-- /.box-footer -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="salvaNuovaScadenza" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
