<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Aziende.modale_nuova_azienda'); ?>
<?php echo $this->Html->script('Aziende.angular/select.min'); ?>
<?php echo $this->Html->script('Aziende.angular/angular-sanitize.min'); ?>
<?php echo $this->Html->css('Aziende.select.min'); ?>
<div class="modal fade" id="myModalAzienda" role="dialog" aria-labelledby="myModalLabel" ng-app="Aziende" ng-controller="aziende as vm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a id="click_tab_1" href="#tab_1" data-toggle="tab">{{ vm.azienda.id ? '' : '<?=__c('Nuovo')?>'}} <b><?=__c('Ente')?></b></a></li>
                  <li><a id="click_tab_2" href="#tab_2" data-toggle="tab">{{ vm.azienda.id ? '' : 'Nuove'}} <b>Strutture</b></a></li>
                  <li><a id="click_tab_3" href="#tab_3" data-toggle="tab">{{ vm.azienda.id ? '' : 'Nuovi'}} <b>Contatti</b></a></li>
				  <li ng-if="vm.azienda.id && (vm.azienda.id_cliente_fattureincloud != 0 || vm.azienda.id_fornitore_fattureincloud != 0)"><a id="click_tab_4" href="#tab_4" data-toggle="tab"><b>Verifica dati</b></a></li>
				  <li class="pull-right"><button type="button" class="close" style="padding: 10px 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <form name="angularForm" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group ">
                                <label class="col-sm-2 control-label required" for="inputDenominazione">Denominazione</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.id" type="hidden" name="idAzienda" id="idAzienda" >
                                    <input required ng-model="vm.azienda.denominazione" type="text" placeholder="Denominazione" name="Denominazione" id="inputDenominazione" class="form-control required" >
                                </div>
                            </div>

                            <!--
                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputCognome">Cognome</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.azienda.cognome" type="text" placeholder="Cognome" name="Cognome" id="inputCognome" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputNome">Nome</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.azienda.nome" type="text" placeholder="Nome" name="Nome" id="inputNome" class="form-control">
                                </div>
                              </div>
                            </div>
                            -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="sitoWeb">Sito Web</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.sito_web" type="text" placeholder="Sito Web" name="sito_web" id="sitoWeb" class="form-control">
                                </div>
                            </div>
                            <!--
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Gruppo</label>
                                <div class="col-sm-10">
                                    <select name="gruppi" ng-model="vm.azienda.gruppi" multiple="multiple" class="form-control" >
                                      <?php foreach ($gruppi as $gruppo): ?>
                                        <option value="<?= $gruppo->id ?>"><?= h($gruppo->text) ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            -->

                            <hr>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.telefono" type="text" placeholder="Telefono" name="Telefono" id="inputTelefono" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required" for="inputCellulare">Cellulare</label>
                                <div class="col-sm-10">
                                    <input required ng-model="vm.azienda.fax" type="text" placeholder="Cellulare" name="Cellulare" id="inputCellulare" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required" for="inputPec">Pec amministrativa</label>
                                <div class="col-sm-10">
                                    <input required ng-model="vm.azienda.pec" type="email" placeholder="Indirizzo pec amministrativa" name="Pec amministrativa" id="inputPec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required" for="inputPecCommissione">Pec atti commissione</label>
                                <div class="col-sm-10">
                                    <input required ng-model="vm.azienda.pec_commissione" type="email" placeholder="Indirizzo pec atti commissione" name="Pec commissione" id="inputPecCommissione" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required" for="inputReferente1">Referente 1</label>
                                <div class="col-sm-10">
                                    <input required ng-model="vm.azienda.referente_1" type="text" placeholder="Referente 1" name="Referente 1" id="inputReferente1" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputReferente2">Referente 2</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.referente_2" type="text" placeholder="Referente 2" name="Referente 2" id="inputReferente2" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label required" for="inputEmailInfo">Email Info</label>
                                <div class="col-sm-10">
                                    <input required ng-model="vm.azienda.email_info" type="email" placeholder="Email Info" name="Email info" id="inputEmailInfo" class="form-control check-email">
                                </div>
                            </div>
                            
                            <!--
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmailContabilita">Email Contabilità</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.email_contabilita" type="email" placeholder="Email Contabilità" name="emailContabilita" id="inputEmailContabilita" class="form-control check-email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmailSolleciti">Email Solleciti</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.email_solleciti" type="email" placeholder="Email Solleciti" name="emailSolleciti" id="inputEmailSolleciti" class="form-control check-email">
                                </div>
                            </div>
                            -->

                            <hr>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputCodicePaese">Codice Paese</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.cod_paese" type="text" placeholder="Codice Paese" name="codicePaese" id="inputCodicePaese" class="form-control"  maxlength="2">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputPiva">Partita IVA</label>
                                <div class="col-sm-6">
                                    <input piva ng-model="vm.azienda.piva" type="text" placeholder="Partita IVA" name="piva" id="inputPiva" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <button class="btn btn-default verify-piva">Verifica dati sul sistema VIES</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputCF">Codice Fiscale</label>
                                <div class="col-sm-10">
                                    <input cf ng-model="vm.azienda.cf" type="text" placeholder="Codice Fiscale" name="cf" id="inputCF" class="form-control">
                                </div>
                            </div>

							<div class="form-group">
                                <label class="col-sm-2 control-label" for="inputPA">Cod. Destinatario</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.azienda.pa_codice" type="text" placeholder="Codice Destinatario" name="pa_codice" id="inputPA" class="form-control">
                                </div>
                            </div>

                            <!--
                            <hr>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <div class="checkbox">
                                        <label>
                                            <input ng-model="vm.azienda.cliente" type="checkbox" name="checkCliente" value="1"> Cliente
                                        </label>
                                        <label>
                                            <input ng-model="vm.azienda.fornitore" type="checkbox" name="checkFornitore" value="1"> Fornitore
                                        </label>
                                        <label>
                                            <input ng-model="vm.azienda.interno" type="checkbox" name="checkInterno" value="1"> Interno
                                        </label>
                                    </div>
                                </div>
                            </div>
                            -->

                            <hr>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputTelefono">Logo</label>
                                <div ng-show="vm.azienda.logo" class="col-sm-10">
                                    <img src="{{vm.azienda.logo}}" class="preview-nodo-logo" />
                                    <button class="btn btn-danger btn-remove-logo" title="Rimuovi logo" ng-click="vm.azienda.logo = ''"><i class=" fa fa-trash"></i></button>
                                </div>
                                <div ng-show="!vm.azienda.logo" class="col-sm-10">
                                    <input type="file" name="Logo" id="inputLogo" file-upload ng-model="vm.azienda.logo_to_save" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>

                  </div>

                  <!-- /.tab-pane -->
                  <div class="tab-pane tab-secondo-livello" id="tab_2">
                    <a ng-click="vm.addSede()" class="new-tab add-tab-sede"><span class=" btn btn-xs btn-info">Aggiungi struttura</span></a>
                    <ul class="nav nav-tabs tabs-sedi" >
                      <li ng-repeat="sede in vm.azienda.sedi track by $index"  ng-class="{'active': ($first && !vm.editing) }" id="subtabsede_{{sede.id}}" >
                        <a id="click_subtab_sede_{{sede.id}}" href="#subtab_sede_{{ $index }}" data-toggle="tab">
                          <i class="fa fa-circle-o sediTipiMinisteroColor-{{sede.id_tipo_ministero}}"></i> {{ !sede.indirizzo ? 'nuova struttura' : sede.comune_des+' - '+sede.indirizzo }} 
                          <i ng-if="sede.indirizzo" class="fa fa-times-circle text-red delete-sede" data-id="{{ sede.id }}" title="Cancella struttura"></i>
                        </a>
                      </li>
                    </ul>
                    <div class="tab-content">
                        <div repeat-push-form ng-repeat="sede in vm.azienda.sedi track by $index" class="tab-pane" id="subtab_sede_{{$index}}" ng-class="{'active': ($first && !vm.editing) }" >
                            <div ng-form="angularForm"  class="form-sede form-horizontal">
                                <div class="box-body">
                                    <input ng-model="parentTab" type="hidden" name="parentTab" value="#click_tab_2" />
                                    <input ng-model="childTab" type="hidden" name="childTab" value="#click_subtab_sede_{{sede.id}}" />
                                    <input ng-model="sede.id" type="hidden" name="id" id="idSede" value="">
                                    <input type="hidden" name="id_azienda" id="idAzienda" value="">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputCodeCentro">Codice centro</label>
                                        <div class="col-sm-10">
                                            <input required ng-model="sede.code_centro" type="text" maxlength="8" placeholder="Codice centro" name="codice centro" id="inputCodeCentro" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label required" for="inputTipoStrutturaMinistero">Tipologia struttura (per ministero)</label>
                                        <div class="col-sm-10">
                                            <select required ng-model="sede.id_tipo_ministero" convert-to-number name="tipo" id="inputTipoStrutturaMinistero" data-prova="ccc" class="form-control required" >
                                                <option value="">-- Seleziona una tipologia struttura --</option>
                                                <?php foreach ($sediTipiMinistero as $key => $tipo): ?>
                                                    <option value="<?=$tipo->id?>"><?=$tipo->name?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label required" for="inputTipoStrutturaMinistero">Tipologia struttura (per ministero)</label>
                                        <div class="col-sm-10">
                                            <select required ng-model="sede.id_tipo_capitolato" convert-to-number name="tipo" id="inputTipoStrutturaMinistero" data-prova="ccc" class="form-control required" >
                                                <option value="">-- Seleziona una tipologia struttura --</option>
                                                <?php foreach ($sediTipiCapitolato as $key => $tipo): ?>
                                                    <option value="<?=$tipo->id?>"><?=$tipo->name?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label required" for="inputTipoCentro">Tipologia centro</label>
                                        <div class="col-sm-10">
                                            <select required ng-model="sede.id_tipologia_centro" convert-to-number name="tipologia centro" id="inputTipoCentro" class="form-control" >
                                                <option value="">-- Seleziona una tipologia centro --</option>
                                                <?php foreach ($tipologieCentro as $key => $tipologia): ?>
                                                    <option value="<?= $tipologia->id ?>"><?= h($tipologia->name) ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputTipoOspite">Tipologia ospiti</label>
                                        <div class="col-sm-10">
                                            <select required ng-model="sede.id_tipologia_ospiti" convert-to-number name="tipologia ospiti" id="inputTipoOspite" class="form-control" >
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
                                            <input required ng-model="sede.indirizzo" type="text" placeholder="Indirizzo" name="indirizzo" id="inputIndirizzo" class="form-control required" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputNumCivico">Numero Civico</label>
                                        <div class="col-sm-10">
                                            <input ng-model="sede.num_civico" type="text" placeholder="Numero Civico" name="numero civico" id="inputNumCivico" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputCap">Cap</label>
                                        <div class="col-sm-10">
                                            <input required ng-model="sede.cap" type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputProvincia">Provincia</label>
                                        <div class="select-provincia-parent col-sm-10">
                                            <select required ng-model="sede.provincia" name="provincia" id="inputProvincia" class="select2 select-provincia form-control required">
                                                <?php foreach ($province as $prv): ?>
                                                    <option value="<?=$prv->id?>"><?=$prv->text?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputComune">Comune</label>
                                        <div class="col-sm-10">
                                            <input hidden required class="comune-value required" name="comune" ng-model="sede.comune">
                                            <input hidden class="comune-des-value" name="comune_des" ng-model="sede.comune_des">
                                            <select id="inputComune" class="select2 select-comune form-control">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputNazione">Nazione</label>
                                        <div class="col-sm-10">
                                            <input ng-model="sede.nazione" type="text" placeholder="Nazione" name="nazione" id="inputNazione" class="form-control">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                                        <div class="col-sm-10">
                                            <input ng-model="sede.telefono" type="text" placeholder="Telefono" name="telefono" id="inputTelefono" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputCell">Cellulare</label>
                                        <div class="col-sm-10">
                                            <input required ng-model="sede.cellulare" type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                                        <div class="col-sm-10">
                                            <input ng-model="sede.fax" type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputEmail">Email</label>
                                        <div class="col-sm-10">
                                            <input required ng-model="sede.email" type="email" placeholder="Email" name="email" id="inputEmail" class="form-control check-email">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                                        <div class="col-sm-10">
                                            <input ng-model="sede.skype" type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" >
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputCapienzaConvenzione">Capienza (struttura)</label>
                                        <div class="col-sm-10">
                                            <input required ng-model="sede.n_posti_struttura" type="text" placeholder="Capienza (struttura)" name="capienza (da convenzione)" id="inputCapienzaConvenzione" class="form-control number-integer" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label required" for="inputCapienzaEffettiva">Capienza (effettiva)</label>
                                        <div class="col-sm-10">
                                            <input required ng-model="sede.n_posti_effettivi" type="text" placeholder="Capienza (effettiva)" name="capienza (effettiva)" id="inputCapienzaEffettiva" class="form-control number-integer" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputCapienzaEffettiva">Capienza (da convenzione)</label>
                                        <div class="col-sm-10">
                                            <input disabled ng-model="sede.n_posti_convenzione" type="text" name="capienza (da convenzione)" id="inputCapienzaEffettiva" class="form-control number-integer" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputProceduraAffidamento">Procedura di affidamento</label>
                                        <div class="col-sm-10">
                                            <select disabled ng-model="sede.id_procedura_affidamento" convert-to-number name="procedura di affidamento" id="inputProceduraAffidamento" class="form-control" >
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
                                            <select required ng-model="sede.operativita" convert-to-number name="operatività" id="inputOperativita" class="form-control" >
                                              <option value="1">Attivo</option>
                                              <option value="0">Chiuso</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <!-- CONTATTI -->
                  <div class="tab-pane tab-secondo-livello" id="tab_3">
                    <a ng-click="vm.addContatto()" class="new-tab add-tab-contatto"><span class=" btn btn-xs btn-info">Aggiungi Contatto</span></a>
                    <ul class="nav nav-tabs tabs-contatti">
                      <li ng-repeat="contatto in vm.azienda.contatti track by $index" ng-class="{'active': ($first && !vm.editing) }" id="subtabcontatto_{{contatto.id}}">
                        <a id="click_subtab_contatto_{{contatto.id}}" href="#subtab_contatto{{ $index }}" data-toggle="tab">
                          <i class="fa fa-circle-o ruoliColor-{{contatto.id_ruolo}}"></i> {{ !contatto.nome ? 'nuovo contatto' : contatto.nome+' '+contatto.cognome }}
                          <i ng-if="contatto.id" class="fa fa-times-circle text-red delete-contatto" data-id="{{ contatto.id }}" title="Cancella contatto"></i>
                        </a>
                      </li>
                    </ul>
                    <div class="tab-content">
                        <div repeat-push-form  ng-repeat="contatto in vm.azienda.contatti track by $index" class="tab-pane" ng-class="{'active': ($first && !vm.editing) }"  id="subtab_contatto{{ $index }}">
                            <div ng-form="angularForm" class="form-contatto form-horizontal">
                              <input ng-model="parentTab" type="hidden" name="parentTab" value="#click_tab_3" />
                              <input ng-model="childTab" type="hidden" name="childTab" value="#click_subtab_contatto_{{contatto.id}}" />
                                <div class="box-body">
                                  <input ng-model="contatto.id" type="hidden" name="id" id="idContatto" value="">
                                  <input ng-model="contatto.id_azienda" type="hidden" name="id_azienda" id="idAzienda">
                                    <div class="form-group">
                                        <div class="input">
                                          <label class="col-sm-2 control-label required" for="inputCognome">Cognome</label>
                                          <div class="col-sm-4">
                                              <input required ng-model="contatto.cognome" type="text" placeholder="Cognome" name="cognome" id="inputCognome" class="form-control required">
                                          </div>
                                        </div>
                                        <div class="input">
                                          <label class="col-sm-2 control-label required" for="inputNome">Nome</label>
                                          <div class="col-sm-4">
                                              <input required ng-model="contatto.nome" type="text" placeholder="Nome" name="nome" id="inputNome" class="form-control required">
                                          </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Utente</label>
                                        <div class="col-sm-10">
                                            <ui-select ng-model="contatto.id_user" theme="bootstrap" >
                                                <ui-select-match placeholder="Seleziona un utente (inserire almeno 3 caratteri)">
                                                    <div ng-if="$select.selected.text === undefined">
                                                        {{ contatto.user.username }}
                                                    </div>
                                                    <div ng-if="$select.selected.text != ''">
                                                        {{ $select.selected.text }}
                                                    </div>
                                                </ui-select-match>
                                                <ui-select-choices refresh="getUsers($select)" refresh-delay="300" repeat="user.id as user in (users | filter: $select.search | limitTo: ($select.search.length < 3) ? 0 : undefined)">
                                                    <div ng-bind-html="user.text"></div>
                                                </ui-select-choices>
                                            </ui-select>
                                        </div>
                                    </div>


                                    <div class="form-group ">
                                      <label class="col-sm-2 control-label" for="idSede">Struttura</label>
                                      <div class="col-sm-10">
                                        <select name="sede" class="form-control" ng-options="sede.id as sede.comune_des+' - '+sede.indirizzo for sede in vm.azienda.sedi" ng-model="contatto.id_sede"  >
                                        </select>
                                      </div>
                                    </div>

                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label" for="inputTipo">Ruolo</label>
                                        <div class="col-sm-10">
                                            <select ng-model="contatto.id_ruolo" convert-to-number name="ruolo" id="inputRuolo" class="form-control" >
                                                <?php foreach ($ruoli as $key => $ruolo): ?>
                                                    <option value="<?=$ruolo->id?>"><?=$ruolo->ruolo?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Compenteze</label>
                                        <div class="col-sm-10">
                                            <select name="skills" ng-model="contatto.skills" multiple="multiple" class="form-control" >
                                              <?php foreach ($skills as $skill): ?>
                                                <option value="<?= $skill->id ?>"><?= h($skill->text) ?></option>
                                              <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputCF">Codice Fiscale</label>
                                        <div class="col-sm-10">
                                            <input cf ng-model="contatto.cf" type="text" placeholder="Codice Fiscale" name="cf" id="inputCF" class="form-control">
                                        </div>
                                    </div>
                                    -->

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label " for="inputIndirizzo">Indirizzo</label>
                                        <div class="col-sm-6">
                                            <input ng-model="contatto.indirizzo" type="text" placeholder="Indirizzo" name="indirizzo" id="inputIndirizzo" class="form-control">
                                        </div>
                                        <label class="col-sm-2 control-label " for="inputNumCivico">Numero Civico</label>
                                        <div class="col-sm-2">
                                            <input ng-model="contatto.num_civico" type="text" placeholder="Numero Civico" name="num_civico" id="inputNumCivico" class="form-control ">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label " for="inputCap">Cap</label>
                                        <div class="col-sm-10">
                                            <input ng-model="contatto.cap" type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control ">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputProvincia">Provincia</label>
                                        <div class="select-provincia-parent col-sm-10">
                                            <select ng-model="contatto.provincia" name="provincia" id="inputProvincia" class="select2 select-provincia-contatto form-control">
                                                <?php foreach ($province as $prv): ?>
                                                    <option value="<?=$prv->id?>"><?=$prv->text?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputComune">Comune</label>
                                        <div class="col-sm-10">
                                            <input hidden class="comune-value-contatto" name="comune" ng-model="contatto.comune">
                                            <input hidden class="comune-des-value-contatto" name="comune_des" ng-model="contatto.comune_des">
                                            <select id="inputComune" class="select2 select-comune-contatto form-control">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputNazione">Nazione</label>
                                        <div class="col-sm-10">
                                            <input ng-model="contatto.nazione" type="text" placeholder="Nazione" name="nazione" id="inputNazione" class="form-control">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                                        <div class="col-sm-4">
                                            <input ng-model="contatto.telefono" type="text" placeholder="Telefono" name="telefono" id="inputTelefono" class="form-control">
                                        </div>
                                        <label class="col-sm-2 control-label" for="inputCell">Cellulare</label>
                                        <div class="col-sm-4">
                                            <input ng-model="contatto.cellulare" type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                                        <div class="col-sm-4">
                                            <input ng-model="contatto.fax" type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control">
                                        </div>
                                        <div class="input">
                                          <label class="col-sm-2 control-label" for="inputEmail">Email</label>
                                          <div class="col-sm-4">
                                              <input ng-model="contatto.email" type="email" placeholder="Email" name="email" id="inputEmail" class="form-control check-email">
                                          </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                                        <div class="col-sm-10">
                                            <input ng-model="contatto.skype" type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                  </div>
                  <!-- /.tab-pane -->

				  <!-- VERIFICA DATI -->
                  <div class="tab-pane tab-secondo-livello" id="tab_4">
					  <span hidden id="azienda-id">{{vm.azienda.id}}</span>
					  <div ng-if="(vm.azienda.id_cliente_fattureincloud != 0 && vm.azienda.id_fornitore_fattureincloud == 0) || (vm.azienda.id_fornitore_fattureincloud != 0 && vm.azienda.id_cliente_fattureincloud == 0)">
						  <h3 ng-if="vm.azienda.id_cliente_fattureincloud != 0">Dati cliente</h3>
						  <h3 ng-if="vm.azienda.id_fornitore_fattureincloud != 0">Dati fornitore</h3>
                          <table class="table table-hover table-fic-data">
							  <tr>
								<th>Campo</th>
								<th>Valore remoto</th>
								<th>Valore locale <span hidden id="sedeTipoId">{{vm.sede.id_tipo}}</span></th>
								<th></th>
							  </tr>
							  <tr data-field="nome">
								<td><b>Denominazione</b></td>
								<td data-type="unico">{{vm.tipo.nome}}</td>
								<td data-type="locale">{{vm.azienda.denominazione}}</td>
								<td><button ng-if="vm.azienda.denominazione != vm.tipo.nome && vm.tipo.nome != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="referente">
								<td><b>Referente</b></td>
								<td data-type="unico">{{vm.tipo.referente}}</td>
								<td data-type="locale">{{vm.azienda.cognome + ' ' + vm.azienda.nome}}</td>
								<td><button ng-if="vm.azienda.cognome + ' ' + vm.azienda.nome != vm.tipo.referente && (vm.azienda.cognome != '' || vm.azienda.nome != '') && vm.tipo.referente != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="piva">
								<td><b>Partita Iva</b></td>
								<td data-type="unico">{{vm.tipo.piva}}</td>
								<td data-type="locale">{{vm.azienda.piva}}</td>
								<td><button ng-if="vm.azienda.piva != vm.tipo.piva && vm.tipo.piva != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="cf">
								<td><b>Codice Fiscale</b></td>
								<td data-type="unico">{{vm.tipo.cf}}</td>
								<td data-type="locale">{{vm.azienda.cf}}</td>
								<td><button ng-if="vm.azienda.cf != vm.tipo.cf && vm.tipo.cf != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="paese">
								<td><b>Paese</b></td>
								<td data-type="unico">{{vm.tipo.paese}}</td>
								<td data-type="locale">{{vm.sede.nazione}}</td>
								<td><button ng-if="vm.sede.nazione != vm.tipo.paese && vm.tipo.paese != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="indirizzo">
								<td><b>Indirizzo</b></td>
								<td data-type="unico">{{vm.tipo.indirizzo_via}}</td>
								<td data-type="locale">{{vm.sede.indirizzo + ', ' + vm.sede.num_civico}}</td>
								<td><button ng-if="vm.sede.indirizzo + ', ' + vm.sede.num_civico != vm.tipo.indirizzo_via && (vm.sede.indirizzo != '' || vm.sede.num_civico != '') && vm.tipo.indirizzo_via != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="citta">
								<td><b>Città</b></td>
								<td data-type="unico">{{vm.tipo.indirizzo_citta}}</td>
								<td data-type="locale">{{vm.sede.c.des_luo}}</td>
								<td><button ng-if="vm.sede.c.des_luo != vm.tipo.indirizzo_citta && vm.tipo.indirizzo_citta != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="cap">
								<td><b>CAP</b></td>
								<td data-type="unico">{{vm.tipo.indirizzo_cap}}</td>
								<td data-type="locale">{{vm.sede.cap}}</td>
								<td><button ng-if="vm.sede.cap != vm.tipo.indirizzo_cap && vm.tipo.indirizzo_cap != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="provincia">
								<td><b>Provincia</b></td>
								<td data-type="unico">{{vm.tipo.indirizzo_provincia}}</td>
								<td data-type="locale">{{vm.sede.p.des_luo}}</td>
								<td><button ng-if="vm.sede.p.des_luo != vm.tipo.indirizzo_provincia && vm.tipo.indirizzo_provincia != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="telefono">
								<td><b>Telefono</b></td>
								<td data-type="unico">{{vm.tipo.tel}}</td>
								<td data-type="locale">{{vm.azienda.telefono}}</td>
								<td><button ng-if="vm.azienda.telefono != vm.tipo.tel && vm.tipo.tel != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="fax">
								<td><b>Fax</b></td>
								<td data-type="unico">{{vm.tipo.fax}}</td>
								<td data-type="locale">{{vm.azienda.fax}}</td>
								<td><button ng-if="vm.azienda.fax != vm.tipo.fax && vm.tipo.fax != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
							  <tr data-field="email">
								<td><b>Email Info</b></td>
								<td data-type="unico">{{vm.tipo.mail}}</td>
								<td data-type="locale">{{vm.azienda.email_info}}</td>
								<td><button ng-if="vm.azienda.email_info != vm.tipo.mail && vm.tipo.mail != ''" type="button" class="btn btn-default btn-save-local" data-type="unico">Salva in locale</button></td>
							  </tr>
	                      </table>
					  </div>
					  <div ng-if="vm.azienda.id_cliente_fattureincloud != 0 && vm.azienda.id_fornitore_fattureincloud != 0">
						  <h3>Dati cliente/fornitore</h3>
						  <table class="table table-hover table-fic-data">
							  <tr>
								<th>Campo</th>
								<th colspan="2">Valore remoto (cliente)</th>
								<th colspan="2">Valore remoto (fornitore)</th>
								<th>Valore locale <span hidden id="sedeTipoId">{{vm.sede.id_tipo}}</span></th>
							  </tr>
							  <tr data-field="nome">
								<td><b>Denominazione</b></td>
								<td data-type="cliente">{{vm.cliente.nome}}</td>
								<td><button ng-if="vm.azienda.denominazione != vm.cliente.nome && vm.cliente.nome != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.nome}}</td>
								<td><button ng-if="vm.azienda.denominazione != vm.fornitore.nome && vm.fornitore.nome != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.denominazione}}</td>
							  </tr>
							  <tr data-field="referente">
								<td><b>Referente</b></td>
								<td data-type="cliente">{{vm.cliente.referente}}</td>
								<td><button ng-if="vm.azienda.cognome + ' ' + vm.azienda.nome != vm.cliente.referente && (vm.azienda.cognome != '' || vm.azienda.nome != '') && vm.cliente.referente != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.referente}}</td>
								<td><button ng-if="vm.azienda.cognome + ' ' + vm.azienda.nome != vm.fornitore.referente && (vm.azienda.cognome != '' || vm.azienda.nome != '') && vm.fornitore.referente != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.cognome + ' ' + vm.azienda.nome}}</td>
							  </tr>
							  <tr data-field="piva">
								<td><b>Partita Iva</b></td>
								<td data-type="cliente">{{vm.cliente.piva}}</td>
								<td><button ng-if="vm.azienda.piva != vm.cliente.piva && vm.cliente.piva != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.piva}}</td>
								<td><button ng-if="vm.azienda.piva != vm.fornitore.piva && vm.fornitore.piva != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.piva}}</td>
							  </tr>
							  <tr data-field="cf">
								<td><b>Codice Fiscale</b></td>
								<td data-type="cliente">{{vm.cliente.cf}}</td>
								<td><button ng-if="vm.azienda.cf != vm.cliente.cf && vm.cliente.cf != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.cf}}</td>
								<td><button ng-if="vm.azienda.cf != vm.fornitore.cf && vm.fornitore.cf != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.cf}}</td>
							  </tr>
							  <tr data-field="paese">
								<td><b>Paese</b></td>
								<td data-type="cliente">{{vm.cliente.paese}}</td>
								<td><button ng-if="vm.sede.nazione != vm.cliente.paese && vm.cliente.paese != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.paese}}</td>
								<td><button ng-if="vm.sede.nazione != vm.fornitore.paese && vm.fornitore.paese != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.sede.nazione}}</td>
							  </tr>
							  <tr data-field="indirizzo">
								<td><b>Indirizzo</b></td>
								<td data-type="cliente">{{vm.cliente.indirizzo_via}}</td>
								<td><button ng-if="vm.sede.indirizzo + ', ' + vm.sede.num_civico != vm.cliente.indirizzo_via && (vm.sede.indirizzo != '' || vm.sede.num_civico != '') && vm.cliente.indirizzo_via != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.indirizzo_via}}</td>
								<td><button ng-if="vm.sede.indirizzo + ', ' + vm.sede.num_civico != vm.fornitore.indirizzo_via && (vm.sede.indirizzo != '' || vm.sede.num_civico != '') && vm.fornitore.indirizzo_via != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.sede.indirizzo + ', ' + vm.sede.num_civico}}</td>
							  </tr>
							  <tr data-field="citta">
								<td><b>Città</b></td>
								<td data-type="cliente">{{vm.cliente.indirizzo_citta}}</td>
								<td><button ng-if="vm.sede.c.des_luo != vm.cliente.indirizzo_citta && vm.cliente.indirizzo_citta != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.indirizzo_citta}}</td>
								<td><button ng-if="vm.sede.c.des_luo != vm.fornitore.indirizzo_citta && vm.fornitore.indirizzo_citta != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.sede.c.des_luo}}</td>
							  </tr>
							  <tr data-field="cap">
								<td><b>CAP</b></td>
								<td data-type="cliente">{{vm.cliente.indirizzo_cap}}</td>
								<td><button ng-if="vm.sede.cap != vm.cliente.indirizzo_cap && vm.cliente.indirizzo_cap != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.indirizzo_cap}}</td>
								<td><button ng-if="vm.sede.cap != vm.fornitore.indirizzo_cap && vm.fornitore.indirizzo_cap != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.sede.cap}}</td>
							  </tr>
							  <tr data-field="provincia">
								<td><b>Provincia</b></td>
								<td data-type="cliente">{{vm.cliente.indirizzo_provincia}}</td>
								<td><button ng-if="vm.sede.p.des_luo != vm.cliente.indirizzo_provincia && vm.cliente.indirizzo_provincia != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.indirizzo_provincia}}</td>
								<td><button ng-if="vm.sede.p.des_luo != vm.fornitore.indirizzo_provincia && vm.fornitore.indirizzo_provincia != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.sede.p.des_luo}}</td>
							  </tr>
							  <tr data-field="telefono">
								<td><b>Telefono</b></td>
								<td data-type="cliente">{{vm.cliente.tel}}</td>
								<td><button ng-if="vm.azienda.telefono != vm.cliente.tel && vm.cliente.tel != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente" >Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.tel}}</td>
								<td><button ng-if="vm.azienda.telefono != vm.fornitore.tel && vm.fornitore.tel != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.telefono}}</td>
							  </tr>
							  <tr data-field="fax">
								<td><b>Fax</b></td>
								<td data-type="cliente">{{vm.cliente.fax}}</td>
								<td><button ng-if="vm.azienda.fax != vm.cliente.fax && vm.cliente.fax != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.fax}}</td>
								<td><button ng-if="vm.azienda.fax != vm.fornitore.fax && vm.fornitore.fax != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.fax}}</td>
							  </tr>
							  <tr data-field="email">
								<td><b>Email Info</b></td>
								<td data-type="cliente">{{vm.cliente.mail}}</td>
								<td><button ng-if="vm.azienda.email_info != vm.cliente.mail && vm.cliente.mail != ''" type="button" class="btn btn-default btn-save-local" data-type="cliente">Salva in locale</button></td>
								<td data-type="fornitore">{{vm.fornitore.mail}}</td>
								<td><button ng-if="vm.azienda.email_info != vm.fornitore.mail && vm.fornitore.mailnome != ''" type="button" class="btn btn-default btn-save-local" data-type="fornitore">Salva in locale</button></td>
								<td data-type="locale">{{vm.azienda.email_info}}</td>
							  </tr>
	                      </table>
					  </div>
					  <div class="col-md-12">
						  <p class="save-warning col-md-8">
	  						<b>Attenzione!</b><br />
	  						Il salvataggio dei dati copierà i dati definiti o assegnati come locali anche sui profili cliente/fornitore di fatture in cloud.
	  					  </p>
					  </div>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <div class="modal-footer">
                    <div style="float: left;">
                        <div id="div-remarks">
                            <span hidden id="reference_for_remarks"></span>
                            <span hidden id="reference_id_for_remarks"></span>
                            <span hidden id="label_notification"></span>
                            <?= $this->element('Remarks.button_remarks'); ?>
                        </div>
                        <div id="div-attachments">
                            <span hidden id="contextForAttachment">aziende</span>
                            <span hidden id="idItemForAttachment">{{ vm.azienda.id }}</span>
                            <?= $this->element('AttachmentManager.button_attachment', ['id' => 'button_attachment']); ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                    <button id="saveModalAziende" type="button" class="btn btn-primary" ng-click="vm.checkSubmit()"  >Salva</button>
				</div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>
</div>

<!-- overlay piva dati -->
<div class="overlay" id="overlayDatiPiva" >
    <div class="overlay-header draggable">
        <h4 style="display: inline;">Dati partita IVA</h4> <button class="pull-rigth close close-overlay" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="overlay-body">
        <label>Indirizzo</label>: <span id="address_vat"></span><br />
        <label>Codice paese</label>: <span id="country_code_vat"></span><br />
        <label>Denominazione</label>: <span id="name_vat"></span><br />
        <label>Partita IVA</label>: <span id="number_vat"></span><br />
    </div>
</div>
