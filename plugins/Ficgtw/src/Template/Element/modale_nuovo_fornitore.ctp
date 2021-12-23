<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Ficgtw.modale_nuovo_cliente'); ?>
<div class="modal fade" id="myModalCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" ng-app="Clienti" ng-controller="cliente as vm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="nav-tabs-custom">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <form name="angularForm" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group ">
                                <label class="col-sm-2 control-label required" for="inputDenominazione">Nome</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.cliente.id" type="hidden" name="id" id="id" >
                                    <input required ng-model="vm.cliente.nome" type="text" placeholder="nome" name="nome" id="inputNome" class="form-control required" >
                                </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputReferente">Referente</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.cliente.referente" type="text" placeholder="Referente" name="referente" id="inputReferente" class="form-control">
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputVia">VIA</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.cliente.via" type="text" placeholder="via" name="indirizzo_via" id="inputVia" class="form-control">
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputCitta">CITTA</label>
                                <div class="col-sm-5">
                                    <input ng-model="vm.cliente.indirizzo_citta" type="text" placeholder="citta" name="indirizzo_citta" id="inputCitta" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-1 control-label" for="inputCap">CAP</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.indirizzo_cap" type="text" placeholder="cap" name="indirizzo_cap" id="inputCap" class="form-control">
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputProvincia">PROVINCIA</label>
                                <div class="col-sm-5">
                                    <input ng-model="vm.cliente.indirizzo_provincia" type="text" placeholder="provincia" name="indirizzo_provincia" id="inputProvincia" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-1 control-label" for="inputPaese">PAESE</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.paese" type="text" placeholder="paese" name="paese" id="inputPaese" class="form-control">
                                    <input ng-model="vm.cliente.paese_iso" type="hidden" name="paese_iso" >
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputTelefono">NOTE INDIRIZZO</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.cliente.telefono" type="text" placeholder="Note indirizzo" name="tel" id="inputTelefono" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="inputEmail">EMAIL</label>
                                <div class="col-sm-10">
                                    <input ng-model="vm.cliente.mail" type="email" placeholder="Email" name="mail" id="inputEmail" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputTelefono">TELEFONO</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.tel" type="text" placeholder="telefono" name="tel" id="inputTelefono" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputFax">FAX</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.fax" type="text" placeholder="fax" name="fax" id="inputFax" class="form-control">
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputPiva">PARTITA IVA</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.piva" type="text" placeholder="partita iva" name="piva" id="inputPiva" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputCF">CODICE FISCALE</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.cf" type="text" placeholder="codice fiscale" name="cf" id="inputCF" class="form-control">
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputTermini">TERMINI DI PAGAMENTO (GIORNI)</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.termini_pagamento" type="text" placeholder="termini di pagamento (giorni)" name="termini_pagamento" id="inputTermini" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputPagamento">PAGAMENTO A FINE MESE</label>
                                <div class="col-sm-4">
                                  <input type="radio" name="pagamento_fine_mese" id="inputPagamento" value="1"/> Sì
                      		  			<input type="radio" name="pagamento_fine_mese" id="inputPagamento" value="0"/> No
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputCodiva">CODICE IVA PREDEFINITO</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.cod_iva_default" type="text" placeholder="codice iva predefinito" name="cod_iva_default" id="inputCodiva" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputExtra">NOTE EXTRA</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.extra" type="text" placeholder="note extra" name="extra" id="inputExtra" class="form-control">
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputPa">CLIENTE PUBBLICA AMMINISTRAZIONE</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.PA" type="text" placeholder="codice iva predefinito" name="PA" id="inputPa" class="form-control">
                                </div>
                              </div>
                              <div class="input">
                                <label class="col-sm-2 control-label" for="inputPacod">CODICE PUBBLICA AMMINISTRAZIONE</label>
                                <div class="col-sm-4">
                                    <input ng-model="vm.cliente.PA_codice" type="text" placeholder="codice pubblica amministrazione" name="PA_codice" id="inputPacod" class="form-control">
                                </div>
                              </div>
                            </div>

                            <hr>

                        </div>
                    </form>

                  </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Chiudi</button>
                    <button id="salvaNuovoCliente" rel="fornitore" type="button" class="btn btn-primary" ng-click="vm.checkSubmit()">Salva</button>
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>
</div>
