<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Modale Comunicazione  (https://www.companee.it)
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

<?php echo $this->Html->script('Crediti.modale_comunicazione'); ?>
<div class="modal fade" id="myModalComunicazione" role="dialog" aria-labelledby="myModalLabel" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
          <input type="hidden" id="idAzienda" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gestione Crediti</h4>
            </div>

            <div class="modal-body row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="tab-modal-credits active" data-id="dati-credits"><a href="#">Correnti</a></li>
                        <li role="presentation" class="tab-modal-credits" data-id="dati-evoluzione"><a href="#">Storico</a></li>
                        <li role="presentation" class="tab-modal-credits" data-id="dati-comunicazione"><a href="#">Operazioni</a></li>
                    </ul>
                    <div id="dati-credits" class="form-attuale">
                      <div class="form-horizontal">
                          <div class="box-body">
                              <table id="table-credits-azienda" class="table table-bordered table-striped table-hover">
                                <thead class="head-credits">
                                  <tr>
                                    <th>Data emissione</th>
                                    <th>Data scadenza</th>
                                    <th>Numero documento</th>
                                    <th>Importo</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                          </div>
                      </div>
                    </div>
                    <!-- FASI -->
                    <div id="dati-evoluzione" class="form-attuale" hidden>
                        <div class="form-horizontal">
                            <div class="box-body">
                                <table id="table-notifiche-azienda" class="table table-bordered table-striped table-hover">
                                  <thead class="">
                                    <tr>
                                      <th>Data</th>
                                      <th>Rating</th>
                                      <th>Totale emesso</th>
                                      <th>Totale scaduto</th>
                                    </tr>
                                  </thead>
                                  <tbody id="table-notifiche-azienda-tbody">
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="dati-comunicazione" class="form-attuale" hidden>

                    <div class="box-body">
                      <input checked type="radio" name="azione-notifica" value="email-notifica" /><label class="label-radio" >Email</label>
                      <input type="radio" name="azione-notifica" value="phone-notifica" /><label class="label-radio" >Telefonata</label>
                      <input type="radio" name="azione-notifica" value="rate-notifica" /><label class="label-radio" >Rateazione</label>
                      <input type="radio" name="azione-notifica" value="sospensione-notifica" /><label class="label-radio" >Sospensione</label>
                      <input type="radio" name="azione-notifica" value="legali-notifica" /><label class="label-radio" >Legali</label>
                      <input type="radio" name="azione-notifica" value="none-notifica" /><label class="label-radio" >Nessuna azione</label>
                    </div>
                        <form id="email-notifica" class="form-horizontal operazioni-notifiche">
                            <div class="box-body">
                              <div class="row">
                                <label class="col-sm-1 control-label" for="inputSubject">Oggetto</label>
                                <div class="col-sm-5">
                                    <input type="text" placeholder="Oggetto" name="subject" class="form-control">
                                    <input type="hidden" name="partnerId" value="" />
                                </div>
                              </div>
                              <div class="row">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="inputSubject">Testo Email:</label>
                              </div>
                                <div id="email-content" contenteditable="true" class="email-box">
                                  <p>Gentile cliente,</p>
                                  <p>relativamente a <span id="customerName"></span>, ci risultano scadute e non pagate le seguenti competenze:</p>
                                  <table id="email-table" class="email-table">
                                    <thead>
                                      <tr>
                                        <th>Data</th>
                                        <th>Numero</th>
                                        <th>Importo</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                  </table><br />
                                </div>
                                <div class="notifica-tab-footer">
                                    <button  class="btn btn-flat btn-warning inviaNotifica" value="email" >Invia</button>
                                </div>
                            </div>
                        </form>
                        <form hidden id="phone-notifica" class="form-horizontal operazioni-notifiche">
                          <div class="box-body">
                            <div class="row">
                              <label class="col-sm-12 control-label" style="text-align:left;" for="inputSubject">Testo telefonata: </label>
                            </div>
                            <div class="col-sm-12">
                                <textarea rows="4" class="form-control notifica-testo" id="testo-telefonata"></textarea>
                            </div>
                            <div class="notifica-tab-footer">
                                <button  class="btn btn-flat btn-warning inviaNotifica" value="telefonata" >Invia</button>
                            </div>
                          </div>
                        </form>
                        <form hidden id="rate-notifica" class="form-horizontal operazioni-notifiche">
                          <div class="box-body">
                            <div class="row">
                              <label class="col-sm-12 control-label" style="text-align:left;" for="inputSubject">Testo rateazione: </label>
                            </div>
                            <div class="col-sm-12">
                                <textarea rows="4" class="form-control notifica-testo" id="testo-rateazione"></textarea>
                            </div>
                            <div class="notifica-tab-footer">
                                <button  class="btn btn-flat btn-warning inviaNotifica" value="rateazione" >Invia</button>
                            </div>
                          </div>
                        </form>
                        <form hidden id="sospensione-notifica" class="form-horizontal operazioni-notifiche">
                          <div class="box-body">
                            <div class="row">
                              <label class="col-sm-12 control-label" style="text-align:left;" for="inputSubject">Testo sospensione: </label>
                            </div>
                            <div class="col-sm-12">
                                <textarea rows="4" class="form-control notifica-testo" id="testo-sospensione"></textarea>
                            </div>
                            <div class="notifica-tab-footer">
                                <button  class="btn btn-flat btn-warning inviaNotifica" value="sospensione" >Invia</button>
                            </div>
                          </div>
                        </form>
                        <form hidden id="legali-notifica" class="form-horizontal operazioni-notifiche">
                          <div class="box-body">
                            <div class="row">
                              <label class="col-sm-12 control-label" style="text-align:left;" for="inputSubject">Testo legali: </label>
                            </div>
                            <div class="col-sm-12">
                                <textarea rows="4" class="form-control notifica-testo" id="testo-legali"></textarea>
                            </div>
                            <div class="notifica-tab-footer">
                                <button  class="btn btn-flat btn-warning inviaNotifica" value="legali" >Invia</button>
                            </div>
                          </div>
                        </form>
                        <form hidden id="none-notifica" class="form-horizontal operazioni-notifiche">
                          <div class="box-body">
                            <div class="row">
                              <label class="col-sm-12 control-label" style="text-align:left;" for="inputSubject">Testo: </label>
                            </div>
                            <div class="col-sm-12">
                                <textarea rows="4" class="form-control notifica-testo" id="testo-nessuna"></textarea>
                            </div>
                            <div class="notifica-tab-footer">
                                <button  class="btn btn-flat btn-warning inviaNotifica" value="nessuna" >Invia</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Chiudi</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
