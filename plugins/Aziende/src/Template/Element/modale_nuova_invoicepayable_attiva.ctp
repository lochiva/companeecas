<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modale nuova invoicepayable attiva  (https://www.companee.it)
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

<?php echo $this->Html->script('Aziende.modale_nuova_fatturaattiva'); ?>

<div class="modal fade" id="myModalFatturaAttiva" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuova Fattura Attiva</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="myFormFatturaAttiva">
                    <div class="box-body">
                        <input type="hidden" name="id" value="" />
                        <div class="form-group">
                              <label class="col-sm-3 control-label required">Emesso da</label>
                              <div class="col-sm-9">
                                  <select name="id_issuer"  id="idIssuer" class="form-control required">
                                  <?php foreach ($issuers as $issuer): ?>
                                      <option value="<?= $issuer['id'] ?>"><?= h($issuer['denominazione']) ?></option>
                                    <?php endforeach; ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group" id="idPayerParent">
                              <label class="col-sm-3 control-label required">Cliente</label>
                              <div class="col-sm-9">
                                  <select name="id_payer"  id="idPayer" class="form-control required">
                                      <?php if(!empty($cliente)){ ?>
                                        <option value="<?= $cliente->id ?>"><?= $cliente->denominazione ?></option>
                                      <?php } ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group" id="idOrderParent">
                              <label class="col-sm-3 control-label">Ordine</label>
                              <div class="col-sm-9">
                                  <select name="id_order"  id="idOrder" class="select2 form-control">
                                  </select>
                              </div>
                          </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label required">Data di emissione</label>
                            <div class="col-sm-9">
                                <input name="emission_date"  type="text" placeholder="gg/mm/aaaa" class="form-control datepicker required" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Numero Fattura</label>
                            <div class="col-sm-9">
                                <input name="num"  type="text" placeholder="Numero della fattura" class="form-control required">
                            </div>
                        </div>
                        <hr>
                        <div class="invoice-articles">
                            <div class="invoice-article" data-counter="1">
                                <button class="article-accordion"><span class="article-title">Articolo 1</span></button><a href="#" data-counter="1" title="Elimina articolo" class="delete-article"><i class="fa fa-trash"></i></a>
                                <div class="article-accordion-panel">
                                    <div class="clear-both"></div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Nome articolo</label>
                                        <div class="col-sm-9">
                                            <input name="articoli[1][name]" type="text" data-input="name" placeholder="Nome articolo" class="form-control article-name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label required">Importo senza IVA</label>
                                        <div class="col-sm-9">
                                            <input name="articoli[1][amount_noiva]"  type="text" data-input="amount_noiva" placeholder="Importo senza iva" class="form-control inputNumber required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label required">IVA</label>
                                        <div class="col-sm-9">
                                            <select name="articoli[1][cod_iva]"  data-input="cod_iva" class=" form-control required">
                                                <?php foreach ($lista_iva as $iva): ?>
                                                <option value="<?= $iva['cod_iva'] ?>"><?= $iva['valore_iva'].' '.$iva['descrizione_iva'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>    
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Importo IVA</label>
                                        <div class="col-sm-9">
                                            <input name="articoli[1][amount_iva]"  type="text" data-input="amount_iva" placeholder="Importo dell'iva" class="form-control inputNumber">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Importo totale</label>
                                        <div class="col-sm-9">
                                            <input name="articoli[1][amount_tot]"  type="text" data-input="amount_tot" placeholder="Importo dell'iva" class="form-control inputNumber">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Quantità</label>
                                        <div class="col-sm-9">
                                            <input name="articoli[1][quantity]"  type="text" data-input="quantity" placeholder="Quantità dell'articolo" class="form-control inputNumber">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Descrizione</label>
                                        <div class="col-sm-9">
                                            <input name="articoli[1][description]"  type="text" data-input="description" placeholder="Descrizione" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label required">Causale</label>
                                        <div class="col-sm-9">
                                            <select name="articoli[1][id_purpose]"  data-input="id_purpose" class=" form-control required">
                                                <?php foreach ($purposesActive as $purpose): ?>
                                                <optgroup label="<?= h($purpose['name']) ?>">
                                                    <?php foreach ($purpose['children'] as $child): ?>
                                                    <option value="<?= $child['id'] ?>"><?= h($child['name']) ?></option>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>    
                                </div>
                            </div>    
                        </div>   
                        <button class="btn btn-primary add-article pull-right" title="Aggiungi articolo">Aggiungi articolo</i></button>
                        <hr style="display:inline-block;width:100%;">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" style="padding-bottom: 7px;">Split payament</label>
                            <div class="col-sm-9">
                                <div class="checkbox">
                                  <label>
                                    <input name="split_payment"  type="checkbox" value="1">
                                  </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Importo senza IVA</label>
                            <div class="col-sm-9">
                                <input name="amount_noiva"  type="text" placeholder="Importo senza iva" class="form-control inputNumber">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Importo IVA</label>
                            <div class="col-sm-9">
                                <input name="amount_iva"  type="text" placeholder="Importo dell'iva" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bolli</label>
                            <div class="col-sm-9">
                                <input name="bolli"  type="text" placeholder="Inserisci bolli" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ritenuta acconto</label>
                            <div class="col-sm-9">
                                <input name="ritenuta_acconto"  type="text" placeholder="Ritenuta acconto" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Importo totale</label>
                            <div class="col-sm-9">
                                <input name="amount"  type="text"  placeholder="Totale" class="form-control inputNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Importo da pagare</label>
                            <div class="col-sm-9">
                                <input name="amount_topay"  type="text" placeholder="Da pagare" class="form-control inputNumber">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                              <label class="col-sm-3 control-label required">Condizioni di pagamento</label>
                              <div class="col-sm-9">
                                  <select name="id_payment_condition"  class=" form-control required">
                                    <?php foreach ($paymentConditions as $condition): ?>
                                        <option value="<?= $condition['id'] ?>"><?= h($condition['name']) ?></option>
                                    <?php endforeach; ?>
                                  </select>
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required">Data da pagare</label>
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
                            <label class="col-sm-3 control-label">Data Pagato</label>
                            <div class="col-sm-9">
                                <input name="paid_date"  type="text" placeholder="gg/mm/aaaa" class="form-control datepicker" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Note</label>
                            <div class="col-sm-9">
                                <textarea name="note" type="text" placeholder="Descrizione" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Allegato</label>
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
                <button type="button" class="btn btn-primary" id="salvaFatturaAttiva" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
