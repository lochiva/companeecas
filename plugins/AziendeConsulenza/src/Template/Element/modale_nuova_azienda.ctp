<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Aziende.modale_nuova_azienda'); ?>

<div class="modal fade" id="myModalAzienda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo cliente</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="box-body">
                        
                        <div class="form-group ">
                            <label class="col-sm-2 control-label required" for="inputDenominazione">Denominazione</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="idAzienda" id="idAzienda" >
                                <input type="text" placeholder="Denominazione" name="Denominazione" id="inputDenominazione" class="form-control required" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputNome">Nome</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Nome" name="Nome" id="inputNome" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCognome">Cognome</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Cognome" name="Cognome" id="inputCognome" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputFamiglia">Famiglia</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Famiglia" name="Famiglia" id="inputFamiglia" class="form-control">
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Telefono" name="Telefono" id="inputTelefono" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Fax" name="Fax" id="inputFax" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmailInfo">Email Info</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Email Info" name="emailInfo" id="inputEmailInfo" class="form-control check-email">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmailContabilita">Email Contabilità</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Email Contabilità" name="emailContabilita" id="inputEmailContabilita" class="form-control check-email">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmailSolleciti">Email Solleciti</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Email Solleciti" name="emailSolleciti" id="inputEmailSolleciti" class="form-control check-email">
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCodicePaese">Codice Paese</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Codice Paese" name="codicePaese" id="inputCodicePaese" class="form-control" value="IT" maxlength="2">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPiva">Partita IVA</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Partita IVA" name="piva" id="inputPiva" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label " for="inputCF">Codice Fiscale</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Codice Fiscale" name="cf" id="inputCF" class="form-control ">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputSispac">Codice Sispac</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Codice Sispac" name="codSispac" id="inputSispac" class="form-control required">
                            </div>
                        </div>                        
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-flat btn-warning" id="salvaNuovaAzienda" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->