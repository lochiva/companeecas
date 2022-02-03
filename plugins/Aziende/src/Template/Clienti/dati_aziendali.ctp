<?php
use Cake\Routing\Router;
?>
<?php $this->assign('title','Dati aziendali') ?>
<?php echo $this->Element('Aziende.include'); ?>
<?php echo $this->Html->script('Aziende.aziende'); ?>
<?php echo $this->Html->script('Aziende.dati_aziendali'); ?>
<?php echo $this->Html->script('Aziende.angular/select.min'); ?>
<?php echo $this->Html->script('Aziende.angular/angular-sanitize.min'); ?>
<section class="content-header">
    <h1>Dati aziendali</h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Dati aziendali</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-aziende" class="box box-dati-aziendali" ng-app="Aziende" ng-controller="aziende as vm">    
                <input hidden id="aziendaId" value="<?= $aziendaId ?>">       
                <div class="nav-tabs-custom" id="myModalAzienda">
                    <ul class="nav nav-tabs">
                        <li class="active"><a id="click_tab_1" href="#tab_1" data-toggle="tab"><b>Dati azienda</b></b></a></li>
                        <li><a id="click_tab_2" href="#tab_2" data-toggle="tab"><b>Strutture</b></a></li>
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
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="sitoWeb">Sito Web</label>
                                        <div class="col-sm-10">
                                            <input ng-model="vm.azienda.sito_web" type="text" placeholder="Sito Web" name="sito_web" id="sitoWeb" class="form-control">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                                        <div class="col-sm-10">
                                            <input ng-model="vm.azienda.telefono" type="text" placeholder="Telefono" name="Telefono" id="inputTelefono" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                                        <div class="col-sm-10">
                                            <input ng-model="vm.azienda.fax" type="text" placeholder="Fax" name="Fax" id="inputFax" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputEmailInfo">Pec</label>
                                        <div class="col-sm-10">
                                            <input ng-model="vm.azienda.pec" type="email" placeholder="Indirizzo pec" name="pec" id="inputPec" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="inputEmailInfo">Email Info</label>
                                        <div class="col-sm-10">
                                            <input ng-model="vm.azienda.email_info" type="email" placeholder="Email Info" name="emailInfo" id="inputEmailInfo" class="form-control check-email">
                                        </div>
                                    </div>

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

                                </div>
                            </form>

                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane tab-secondo-livello" id="tab_2">
                            <ul class="nav nav-tabs" >
                            <li ng-repeat="sede in vm.azienda.sedi track by $index"  ng-class="{'active': ($first && !vm.editing) }"  >
                                <a id="click_subtab_sede_{{sede.id}}" href="#subtab_sede_{{ $index }}" data-toggle="tab">
                                <i class="fa fa-circle-o sediTipiColor-{{sede.id_tipo}}"></i> {{ !sede.indirizzo ? 'nuova sede' : sede.comune+' - '+sede.indirizzo }}
                                </a>
                            </li>
                            <li><a ng-click="vm.addSede()" class="new-tab add-tab-sede"><span class=" btn btn-xs btn-info">Aggiungi struttura</span></a></li>
                            </ul>
                            <div class="tab-content">
                                <div repeat-push-form ng-repeat="sede in vm.azienda.sedi track by $index" class="tab-pane" id="subtab_sede_{{$index}}" ng-class="{'active': ($first && !vm.editing) }" >
                                    <div ng-form="angularForm"  class="form-horizontal">
                                        <div class="box-body">
                                            <input ng-model="parentTab" type="hidden" name="parentTab" value="#click_tab_2" />
                                            <input ng-model="childTab" type="hidden" name="childTab" value="#click_subtab_sede_{{sede.id}}" />
                                            <div class="form-group ">
                                                <label class="col-sm-2 control-label required" for="tipo">Tipo</label>
                                                <div class="col-sm-10">
                                                    <input ng-model="sede.id" type="hidden" name="id" id="idSede" value="">
                                                    <input type="hidden" name="id_azienda" id="idAzienda" value="">
                                                    <select required ng-model="sede.id_tipo" convert-to-number name="tipo" id="inputTipo" data-prova="ccc" class="form-control required" >
                                                        <option value="" >-- Seleziona un tipo --</option>
                                                        <?php foreach ($sediTipi as $key => $tipo): ?>
                                                            <option value="<?=$tipo->id?>"><?=$tipo->tipo?></option>
                                                        <?php endforeach ?>
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
                                                <label class="col-sm-2 control-label required" for="inputNumCivico">Numero Civico</label>
                                                <div class="col-sm-10">
                                                    <input required ng-model="sede.num_civico" type="text" placeholder="Numero Civico" name="numero civico" id="inputNumCivico" class="form-control required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label required" for="inputCap">Cap</label>
                                                <div class="col-sm-10">
                                                    <input required ng-model="sede.cap" type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label required" for="inputComune">Comune</label>
                                                <div class="col-sm-10">
                                                    <input required ng-model="sede.comune" type="text" placeholder="Comune" name="comune" id="inputComune" class="form-control required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label required" for="inputProvincia">Provincia</label>
                                                <div class="col-sm-10">
                                                    <input required ng-model="sede.provincia" type="text" placeholder="Provincia" name="provincia" id="inputProvincia" class="form-control required">
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
                                                <label class="col-sm-2 control-label" for="inputCell">Cellulare</label>
                                                <div class="col-sm-10">
                                                    <input ng-model="sede.cellulare" type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                                                <div class="col-sm-10">
                                                    <input ng-model="sede.fax" type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="inputEmail">Email</label>
                                                <div class="col-sm-10">
                                                    <input ng-model="sede.email" type="email" placeholder="Email" name="email" id="inputEmail" class="form-control check-email">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                                                <div class="col-sm-10">
                                                    <input ng-model="sede.skype" type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="modal-footer">
                            <button id="saveModalAziende" type="button" class="btn btn-primary" ng-click="vm.checkSubmit()"  >Salva</button>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>
</section>

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
