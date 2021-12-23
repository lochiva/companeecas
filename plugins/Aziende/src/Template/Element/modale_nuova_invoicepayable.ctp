<?php
use Cake\Routing\Router;
?>

<?php echo $this->Html->script('Aziende.modale_nuova_fatturapassiva'); ?>

<div class="modal fade" id="myModalFatturaPassiva" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuova Fattura Passiva</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="myFormFatturaPassiva">
                    <div class="box-body">
                        <input type="hidden" name="id" value="" />
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="importXml">Importa da XML</label>
                            <div class="col-sm-9">
                                <input name="xml_file" type="file" id="importXml" class="form-control">
                                <span class="xml"></span>
                            </div>
                        </div>
                        <div class="form-group" id="idFornitoreParent">
                              <label class="col-sm-3 control-label required" >Ragione sociale Fornitore</label>
                              <div class="col-sm-9">
                                  <select name="id_issuer" id="idFornitore" class="form-control required">
                                    <?php if(!empty($fornitore)){ ?>
                                        <option value="<?= $fornitore->id ?>"><?= $fornitore->denominazione ?></option>
                                      <?php } ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label required" >Ragione sociale Destinatario</label>
                              <div class="col-sm-9">
                                  <select name="id_payer"  id="idPayer" class="form-control required">
                                    <?php foreach ($payers as $payer): ?>
                                      <option value="<?= $payer['id'] ?>"><?= h($payer['denominazione']) ?></option>
                                    <?php endforeach; ?>
                                  </select>
                              </div>
                          </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label required" for="inputDenominazione">Data di emissione</label>
                            <div class="col-sm-9">
                                <input name="emission_date"  type="text" placeholder="gg/mm/aaaa" class="form-control datepicker required" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label required" for="inputNome">Numero Fattura</label>
                            <div class="col-sm-9">
                                <input name="num"  type="text" placeholder="Numero della fattura" class="form-control required">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome" style="padding-bottom: 7px;">Split payament</label>
                            <div class="col-sm-9">
                                <div class="checkbox">
                                  <label>
                                    <input name="split_payment"  type="checkbox" value="1">
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Importo senza IVA</label>
                            <div class="col-sm-9">
                                <input name="amount_noiva"  type="text" placeholder="Importo senza iva" class="form-control inputNumber">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Importo IVA</label>
                            <div class="col-sm-9">
                                <input name="amount_iva"  type="text" placeholder="Importo dell'iva" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Bolli</label>
                            <div class="col-sm-9">
                                <input name="bolli"  type="text" placeholder="Inserisci bolli" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Ritenuta acconto</label>
                            <div class="col-sm-9">
                                <input name="ritenuta_acconto"  type="text" placeholder="Ritenuta acconto" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Importo totale</label>
                            <div class="col-sm-9">
                                <input name="amount"  type="text"  placeholder="Totale" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Importo da pagare</label>
                            <div class="col-sm-9">
                                <input name="amount_topay"  type="text" placeholder="Da pagare" class="form-control inputNumber">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                              <label class="col-sm-3 control-label required" >Condizioni di pagamento</label>
                              <div class="col-sm-9">
                                  <select name="id_payment_condition"  class=" form-control required">
                                    <?php foreach ($paymentConditions as $condition): ?>
                                        <option value="<?= $condition['id'] ?>"><?= h($condition['name']) ?></option>
                                    <?php endforeach; ?>
                                  </select>
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Descrizione</label>
                            <div class="col-sm-9">
                                <input name="description"  type="text" placeholder="Descrizione" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                              <label class="col-sm-3 control-label required" >Causale</label>
                              <div class="col-sm-9">
                                  <select name="id_purpose"  class=" form-control required">
                                    <?php foreach ($purposesPassive as $purpose): ?>
                                      <optgroup label="<?= h($purpose['name']) ?>">
                                        <?php foreach ($purpose['children'] as $child): ?>
                                          <option value="<?= $child['id'] ?>"><?= h($child['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                  </select>
                              </div>
                        </div>
                        <div class="form-group" id="idOrderParent">
                              <label class="col-sm-3 control-label" >Ordine</label>
                              <div class="col-sm-9">
                                  <select name="id_order"  id="idOrder" class="select2 form-control">
                                  </select>
                              </div>
                          </div>
                        <hr>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required" for="inputNome">Data da pagare</label>
                            <div class="col-sm-9">
                                <input name="due_date"  type="text" placeholder="gg/mm/aaaa" class="form-control datepicker required" readonly>
                            </div>
                        </div>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Saldato su conto</label>
                            <div class="col-sm-9">
    							<select name="metodo" class="form-control" >
    								<option value=""></option>
    								<?php foreach($lista_metodi as $metodo){ ?>
    								<option value="<?php echo $metodo['id']; ?>"><?php echo $metodo['nome_conto']; ?></option>
    								<?php } ?>
    							</select>
                            </div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Data Pagato</label>
                            <div class="col-sm-9">
                                <input name="paid_date"  type="text" placeholder="gg/mm/aaaa" class="form-control datepicker" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Note</label>
                            <div class="col-sm-9">
                                <textarea name="note" type="text" placeholder="Descrizione" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="inputNome">Allegato</label>
                            <div class="col-sm-9">
                                <input name="attachment_file" type="file" placeholder="sfoglia" class="form-control">
                                <span class="attachment"></span>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="salvaFatturaPassiva" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
