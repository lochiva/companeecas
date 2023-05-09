<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modale nuova azienda OLD  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/

use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Aziende.modale_nuova_azienda'); ?>

<div class="modal fade" id="myModalAzienda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuova Azienda</h4>
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
                            <label class="col-sm-2 control-label" for="sitoWeb">Sito Web</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Sito Web" name="sito_web" id="sitoWeb" class="form-control">
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
                            <label class="col-sm-2 control-label" for="inputEmailInfo">Pec</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Indirizzo pec" name="pec" id="inputPec" class="form-control">
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
                            <label class="col-sm-2 control-label" for="inputCF">Codice Fiscale</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Codice Fiscale" name="cf" id="inputCF" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="checkCliente" value="1"> Cliente
                                    </label>
                                    <label>
                                        <input type="checkbox" name="checkFornitore" value="1"> Fornitore
                                    </label>
                                    <label>
                                        <input type="checkbox" name="checkInterno" value="1"> Interno
                                    </label>
                                </div>
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
                <button type="button" class="btn btn-primary" id="salvaNuovaAzienda" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
