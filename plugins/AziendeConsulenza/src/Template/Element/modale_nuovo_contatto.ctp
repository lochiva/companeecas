<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Aziende.modale_nuovo_contatto'); ?>

<div class="modal fade" id="myModalContatto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo Contatto</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputCognome">Cognome</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Cognome" name="cognome" id="inputCognome" class="form-control required">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputNome">Nome</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Nome" name="nome" id="inputNome" class="form-control required">
                            </div>
                        </div>
                        
                        <?php if($tipo == "azienda"){ ?>
                            
                            <div class="form-group ">
                                <label class="col-sm-2 control-label required" for="idSede">Sede</label>
                                <div class="col-sm-10">
                                    <select name="id_sede" id="idSede" class="form-control required" >
                                        <?php foreach ($sedi as $key => $sede) { ?>
                                            <option value="<?=$sede->id?>"><?=$sede->indirizzo . " " . $sede->num_civico?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                        <?php } ?>
                        
                        <?php if($tipo == "all"){ ?>
                            
                            <div class="form-group ">
                                <label class="col-sm-2 control-label" for="idAzienda">Azienda</label>
                                <div class="col-sm-10">
                                    <select name="id_azienda" id="idAzienda" class="form-control" >
                                        <option></option>
                                        <?php foreach ($aziende as $key => $azienda) { ?>
                                            <option value="<?=$azienda->id?>"><?=$azienda->denominazione?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group ">
                                <label class="col-sm-2 control-label" for="idSede">Sede</label>
                                <div class="col-sm-10">
                                    <select name="id_sede" id="idSede" class="form-control" >
                                        <option></option>
                                        <?php foreach ($sedi as $key => $sede) { ?>
                                            <option value="<?=$sede->id?>"><?=$sede->indirizzo . " " . $sede->num_civico?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                        <?php } ?>
                        
                        <div class="form-group ">
                            <label class="col-sm-2 control-label required" for="inputTipo">Ruolo</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="id" id="idContatto" value="">
                                
                                <?php if($tipo != "all"){ ?>
                                    <input type="hidden" name="id_azienda" id="idAzienda" value="<?=$idAzienda?>">
                                    <?php if($tipo == "sede"){ ?>
                                        <input type="hidden" name="id_sede" id="idSede" value="<?=$id?>">
                                    <?php } ?>
                                <?php } ?>
                                
                                <select name="id_ruolo" id="inputRuolo" class="form-control required" >
                                    <?php foreach ($ruoli as $key => $ruolo) { ?>
                                        <option value="<?=$ruolo->id?>"><?=$ruolo->ruolo?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCF">Codice Fiscale</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Codice Fiscale" name="cf" id="inputCF" class="form-control">
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label " for="inputIndirizzo">Indirizzo</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Indirizzo" name="indirizzo" id="inputIndirizzo" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label " for="inputNumCivico">Numero Civico</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Numero Civico" name="num_civico" id="inputNumCivico" class="form-control ">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label " for="inputCap">Cap</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control ">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label " for="inputComune">Comune</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Comune" name="comune" id="inputComune" class="form-control ">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label " for="inputProvincia">Provincia</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Provincia" name="provincia" id="inputProvincia" class="form-control ">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputNazione">Nazione</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Nazione" name="nazione" id="inputNazione" class="form-control">
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Telefono" name="telefono" id="inputTelefono" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCell">Cellulare</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmail">Email</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Email" name="email" id="inputEmail" class="form-control check-email">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" >
                            </div>
                        </div>
                        
                        <!--
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        -->
                    </div><!-- /.box-body 
                    <div class="box-footer">
                        <button class="btn btn-default" type="submit">Cancel</button>
                        <button class="btn btn-info pull-right" type="submit">Sign in</button>
                    </div><!-- /.box-footer -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="salvaNuovoContatto" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->