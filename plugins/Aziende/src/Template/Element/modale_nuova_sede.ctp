<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Aziende.modale_nuova_sede'); ?>

<div class="modal fade" id="myModalSede" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Struttura</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="formSede">
                    <div class="box-body">

                        <input type="hidden" name="id" id="idSede" value="">
                        <input type="hidden" name="id_azienda" id="idAzienda" value="<?=$idAzienda?>">

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputCodeCentro">Codice centro</label>
                            <div class="col-sm-10">
                                <input type="text" maxlength="8" placeholder="Codice centro" name="code_centro" id="inputCodeCentro" class="form-control required">
                            </div>
                        </div>
                        
                        <div class="form-group ">
                            <label class="col-sm-2 control-label required" for="inputTipoMinistero">Tipologia struttura (per ministero)</label>
                            <div class="col-sm-10">
                                <select name="id_tipo_ministero" id="inputTipoMinistero" class="form-control required" >
                                    <option value="">-- Seleziona una tipologia struttura --</option>
                                    <?php foreach ($sediTipiMinistero as $key => $tipo) { ?>
                                        <option value="<?=$tipo->id?>"><?=$tipo->name?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-sm-2 control-label required" for="inputTipoCapitolato">Tipologia struttura (da capitolato)</label>
                            <div class="col-sm-10">
                                <select name="id_tipo_capitolato" id="inputTipoCapitolato" class="form-control required" >
                                    <option value="">-- Seleziona una tipologia struttura --</option>
                                    <?php foreach ($sediTipiCapitolato as $key => $tipo) { ?>
                                        <option value="<?=$tipo->id?>"><?=$tipo->name?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputTipologieCentro">Tipologia centro</label>
                            <div class="col-sm-10">
                                <select name="id_tipologia_centro" id="inputTipologieCentro" class="form-control required">
                                    <option value="">-- Seleziona una tipologia centro --</option>
                                    <?php foreach ($tipologieCentro as $tipologia): ?>
                                        <option value="<?= $tipologia->id ?>"><?= h($tipologia->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputTipologieOspiti">Tipologia ospiti</label>
                            <div class="col-sm-10">
                                <select name="id_tipologia_ospiti" id="inputTipologieOspiti" class="form-control required">
                                    <option value="">-- Seleziona una tipologia ospiti --</option>
                                    <?php foreach ($tipologieOspiti as $tipologia): ?>
                                        <option value="<?= $tipologia->id ?>"><?= h($tipologia->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputIndirizzo">Indirizzo</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Indirizzo" name="indirizzo" id="inputIndirizzo" class="form-control required">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputNumCivico">Numero Civico</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Numero Civico" name="num_civico" id="inputNumCivico" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputCap">Cap</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control required">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputProvincia">Provincia</label>
                            <div class="col-sm-10">
                                <select name="provincia" id="inputProvincia" class="select2 form-control required">
                                    <?php foreach ($province as $prv): ?>
                                        <option value="<?=$prv->id?>"><?=$prv->text?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <input hidden id="comuneValue">
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputComune">Comune</label>
                            <div class="col-sm-10">
                                <select name="comune" id="inputComune" class="select2 form-control required">
                                </select>
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
                            <label class="col-sm-2 control-label required" for="inputReferente">Nome referente</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Nome referente" name="referente" id="inputReferente" class="form-control required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Telefono" name="telefono" id="inputTelefono" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputCell">Cellulare</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control required">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputEmail">Email</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Email" name="email" id="inputEmail" class="form-control check-email required">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" >
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputCapienzaStruttura">Capienza (struttura)</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Capienza (struttura)" name="n_posti_struttura" id="inputCapienzaStruttura" class="form-control number-integer required" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputCapienzaEffettiva">Capienza (effettiva)</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Capienza (effettiva)" name="n_posti_effettivi" id="inputCapienzaEffettiva" class="form-control number-integer required" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputCapienzaConvenzione">Capienza (da convenzione)</label>
                            <div class="col-sm-10">
                                <input disabled type="text" name="n_posti_convenzione" id="inputCapienzaConvenzione" class="form-control number-integer" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputProceduraAffidamento">Procedura di affidamento</label>
                            <div class="col-sm-10">
                                <select disabled name="id_procedura_affidamento" id="inputProceduraAffidamento" class="form-control" >
                                    <option value=""></option>
                                    <?php foreach ($procedureAffidamento as $procedura): ?>
                                    <option value="<?= $procedura->id ?>"><?= h($procedura->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputOperativita">Operatività</label>
                            <div class="col-sm-10">
                                <select name="operativita" id="inputOperativita" class="form-control required" >
                                    <option value="1">Attivo</option>
                                    <option value="0">Chiuso</option>
                                </select>
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
                <button type="button" class="btn btn-primary" id="salvaNuovaSede" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->