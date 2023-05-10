<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Modale Nuovo Order  (https://www.companee.it)
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

<?php echo $this->Html->script('Aziende.modale_nuovo_order'); ?>

<div class="modal fade" id="myModalOrder" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo Ordine</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="box-body">
                        <?php if($idAzienda == 'all'): ?>
                          <div class="form-group" id="idAziendaParent">
                              <label class="col-sm-2 control-label required" for="idAzienda">Azienda</label>
                              <div class="col-sm-10">
                                  <select name="id_azienda" id="idAzienda" class="select2 form-control required"></select>
                              </div>
                          </div>
                        <?php else: ?>
                          <input type="hidden" name="id_azienda" id="idAzienda" value="<?=$idAzienda?>">
                        <?php endif ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputNome">Nome</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Nome" name="nome" id="inputNome" class="form-control required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputNote">Note</label>
                            <div class="col-sm-10">
                                <input type="text" placeholder="Note" name="note" id="inputNote" class="form-control">
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-sm-2 control-label" for="idContatto">Contatto di riferimento</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="id" id="idOrder" value="">
                                <select name="id_contatto" id="idContatto" class="form-control" >
                                        <option style="color: graytext;" value="0">Nessuno</option>
                                    <?php foreach ($contatti as $key => $contatto): ?>
                                        <option value="<?=$contatto->id?>"><?=h($contatto->nome.' '.$contatto->cognome)?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-2 control-label required" for="idContatto">Stato Ordine</label>
                            <div class="col-sm-10">
                                <select name="id_status" id="idStatus" class="form-control required"  >
                                    <?php foreach ($ordersStatus as $key => $val): ?>
                                        <option value="<?=$val->id?>"><?=h($val->name)?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div id="div-remarks" style="float: left;">
                    <span hidden id="reference_for_remarks"></span>
                    <span hidden id="reference_id_for_remarks"></span>
                    <span hidden id="label_notification"></span>
                    <?= $this->element('Remarks.button_remarks'); ?>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="salvaNuovoOrder" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
