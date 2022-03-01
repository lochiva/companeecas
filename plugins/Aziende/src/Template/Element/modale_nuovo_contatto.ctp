<?php
use Cake\Routing\Router;
?>

<?= $this->Html->script('Aziende.modale_nuovo_contatto.js'); ?>

<div class="modal fade" id="myModalContatto" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Contatto</h4>
            </div>
            <form class="form-horizontal" id="myFormContatto">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a id="click_tab_1" href="#tab_1" data-toggle="tab">Profilo</a></li>
                        <li><a id="click_tab_2" href="#tab_2" data-toggle="tab">Privacy</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="input">
                                    <label class="col-sm-2 control-label required" for="inputCognome">Cognome</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Cognome" name="cognome" id="inputCognome" class="form-control required">
                                    </div>
                                    </div>
                                    <div class="input">
                                    <label class="col-sm-2 control-label required" for="inputNome">Nome</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Nome" name="nome" id="inputNome" class="form-control required">
                                    </div>
                                    </div>
                                </div>

                                <div class="form-group" id="idUserSelectParent">
                                    <label class="col-sm-2 control-label" for="idUserSelect">User</label>
                                    <div class="col-sm-10">
                                        <select name="id_user" id="idUserSelect" class="select2 form-control"></select>
                                    </div>
                                </div>

                                <?php if($tipo == "azienda"){ ?>

                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label" for="idSede">Struttura</label>
                                        <div class="col-sm-10">
                                            <select name="id_sede" id="idSede" class="form-control" >
                                                <option value="0">-- Seleziona una struttura --</option>
                                                <?php foreach ($sedi as $key => $sede) { ?>
                                                    <option value="<?=$sede->id?>"><?=$sede->indirizzo . " " . $sede->num_civico?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                <?php } ?>

                                <?php if($tipo == "all"){ ?>

                                <!--
                                <div class="form-group" id="idAziendaSelectParent">
                                    <label class="col-sm-2 control-label required" for="idAziendaSelect">Ente</label>
                                    <div class="col-sm-10">
                                        <select name="id_azienda" id="idAziendaSelect" class="select2 form-control required"></select>
                                    </div>
                                </div>
                                -->

                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label" for="idSede">Struttura</label>
                                        <div class="col-sm-10">
                                            <select name="id_sede" id="idSede" class="form-control" >
                                                <option value="0">-- Seleziona una struttura --</option>
                                                <?php foreach ($sedi as $key => $sede) { ?>
                                                    <option value="<?=$sede->id?>"><?=$sede->indirizzo . " " . $sede->num_civico?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                <?php } ?>

                                <div class="form-group ">
                                    <label class="col-sm-2 control-label" for="inputTipo">Ruolo</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="id" id="idContatto" value="">

                                        <?php if($tipo != "all"){ ?>
                                            <input type="hidden" name="id_azienda" id="idAzienda" value="<?=$idAzienda?>">
                                            <?php if($tipo == "sede"){ ?>
                                                <input type="hidden" name="id_sede" id="idSede" value="<?=$id?>">
                                            <?php } ?>
                                        <?php } ?>

                                        <select name="id_ruolo" id="inputRuolo" class="form-control" >
                                            <?php foreach ($ruoli as $key => $ruolo) { ?>
                                                <option value="<?=$ruolo->id?>"><?=$ruolo->ruolo?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Compenteze</label>
                                    <div class="col-sm-10">
                                        <select name="skills" id="idSkills" multiple="multiple">
                                        <?php foreach ($skills as $skill): ?>
                                            <option value="<?= $skill->id ?>"><?= h($skill->text) ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputCF">Codice Fiscale</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Codice Fiscale" name="cf" id="inputCF" class="form-control">
                                    </div>
                                </div>
                                -->

                                <hr>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputIndirizzo">Indirizzo</label>
                                    <div class="col-sm-6">
                                        <input type="text" placeholder="Indirizzo" name="indirizzo" id="inputIndirizzo" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label" for="inputNumCivico">Numero Civico</label>
                                    <div class="col-sm-2">
                                        <input type="text" placeholder="Numero Civico" name="num_civico" id="inputNumCivico" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputCap">Cap</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputProvincia">Provincia</label>
                                    <div class="col-sm-10">
                                        <select type="text" placeholder="Provincia" name="provincia" id="inputProvincia" class="form-control">
                                        <?php foreach ($province as $prv): ?>
                                            <option value="<?=$prv->id?>"><?=$prv->text?></option>
                                        <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <input hidden id="comuneValue">
                                <div class="form-group" id="comuneSelectParent">
                                    <label class="col-sm-2 control-label" for="inputComune">Comune</label>
                                    <div class="col-sm-10">
                                        <select type="text" placeholder="Comune" name="comune" id="inputComune" class="form-control">
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
                                    <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Telefono" name="telefono" id="inputTelefono" class="form-control">
                                    </div>
                                    <label class="col-sm-2 control-label" for="inputCell">Cellulare</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control">
                                    </div>
                                    <div class="input">
                                    <label class="col-sm-2 control-label" for="inputEmail">Email</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="Email" name="email" id="inputEmail" class="form-control check-email">
                                    </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" >
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class="box-body">
                                <h3 class="text-center">Informativa privacy</h3>

                                <input type="checkbox" name="read_privacy" id="checkReadPrivacy" /> Compilando la presente dichiaro di aver letto attentamente l'<b>Informativa Privacy</b> ai sensi del combinato disposto di cui al D.lgs.n.196/2003 e del Regolamento UE nr. 679/2016, e di aver ricevuto copia della predetta informativa. <a href="#" data-toggle="modal" data-target="#modalPrivacyPolicy">Clicca qui per visualizzarla</a><br /><br />
                                <input type="checkbox" name="accepted_privacy" id="checkAcceptedPrivacy" /> Consento il trattamento dei miei dati personali con le modalità e per le finalità indicati nell'informativa.<br /><br />
                                <input type="checkbox" name="marketing_privacy" id="checkMarketingPrivacy" /> Consento il trattamento dei miei dati personali per le FINALITÀ DI MARKETING.<br /><br />
                                <input type="checkbox" name="third_party_privacy" id="checkThirdPartyPrivacy" /> Consento, sempre per FINALITÀ DI MARKETING, la comunicazione dei miei dati personali a terzi partner commerciali del Titolare del trattamento.<br /><br />
                                <input type="checkbox" name="profiling_privacy" id="checkprofilingPrivacy" /> Consento il trattamento dei miei dati personali per le FINALITÀ DI PROFILAZIONE.<br /><br />
                                <input type="checkbox" name="spread_privacy" id="checkSpreadPrivacy" /> Consento la comunicazione dei miei dati limitatamente agli ambiti ed agli organi specificati nell'informativa.<br /><br />
                                <!-- <input type="checkbox" name="notify_privacy" id="checkNotifyPrivacy" /> Avvisami in caso di modifica dei dati appartenenti all'indirizzo <span class="emailForPrivacy" ></span><br /><br /> -->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <div style="float: left;">
                    <div id="div-remarks">
                        <span hidden id="reference_for_remarks"></span>
                        <span hidden id="reference_id_for_remarks"></span>
                        <span hidden id="label_notification"></span>
                        <?= $this->element('Remarks.button_remarks'); ?>
                    </div>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="salvaNuovoContatto" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?= $this->element('Gdpr.modal_privacy_policy') ?>