<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 
?>

<div class="modal fade" id="modalAgreement" tabindex="-1" role="dialog" aria-labelledby="modalAgreement">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Convenzione</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="formAgreement">
                    <input type="hidden" name="id" id="agreementId" value="">
                    <input type="hidden" name="azienda_id" id="aziendaId" value="<?=$azienda['id']?>">
                    <input type="hidden" id="approved">

                    <?php if ($role == 'admin') { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="inputApproved">Approvato</label>
                            <div class="col-sm-8">
                                <input hidden name="approved" value="0">
                                <input type="checkbox" name="approved" id="inputApproved" value="1">
                            </div>
                        </div>
                    <?php } else { ?>
                        <div hidden class="approved-message col-sm-12">
                            <span>
                                La convenzione è stata approvata pertanto non è più modificabile.
                                <!--La convenzione è in stato approvato. Eseguendo una modifica si sottomette la convenzione ad un nuovo processo di approvazione.-->
                            </span>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label class="col-sm-4 control-label required" for="inputProceduraAffidamento">Procedura di affidamento</label>
                        <div class="col-sm-8">
                            <select name="procedure_id" id="inputProceduraAffidamento" class="form-control required" >
                                <option value="">-- Seleziona una procedura di affidamento --</option>
                                <?php foreach ($procedureAffidamento as $procedura): ?>
                                <option value="<?= $procedura->id ?>"><?= h($procedura->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label required" for="inputDateAgreement">Data di stipula della convenzione</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_agreement" id="inputDateAgreement" class="form-control required datepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label required" for="inputDateAgreementExpiration">Data di scadenza della convenzione</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_agreement_expiration" id="inputDateAgreementExpiration" class="form-control required datepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputDateExtensionExpiration">Data di scadenza della eventuale proroga</label>
                        <div class="col-sm-8">
                            <input type="text" name="date_extension_expiration" id="inputDateExtensionExpiration" class="form-control datepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label required" for="inputGuestDailyPrice">Prezzo giornaliero per ospite</label>
                        <div class="col-sm-8">
                            <input type="text" name="guest_daily_price" id="inputGuestDailyPrice" class="form-control number-decimal required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="inputCig">CIG</label>
                        <div class="col-sm-8">
                            <input type="text" name="cig" id="inputCig" minlength="10" maxlength="10" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="inputCapacityIncrement0">Nessun incremento</label>
                        <div class="col-sm-1">
                            <input type="radio" name="capacity_increment" id="inputCapacityIncrement0" value="0" class="radio-agreement">
                        </div>
                        <label class="col-sm-3 control-label" for="inputCapacityIncrement20">Incremento posti +20%</label>
                        <div class="col-sm-1">
                            <input type="radio" name="capacity_increment" id="inputCapacityIncrement20" value="20" class="radio-agreement">
                        </div>
                        <label class="col-sm-3 control-label" for="inputCapacityIncrement50">Incremento posti +50%</label>
                        <div class="col-sm-1">
                            <input type="radio" name="capacity_increment" id="inputCapacityIncrement50" value="50" class="radio-agreement">
                        </div>
                        <div class="col-sm-12" id="incrementMessageContainer">
                            <span hidden id="incrementCorrectMessage" class="success-capacity-increment">L'incremento da convenzione è assegnato correttamente.</span>
                            <span hidden id="incrementErrorExcessMessage" class="warning-capacity-increment">L'incremento da convenzione non è assegnato correttamente, sono stati assegnati <span class="number"></span> posti in eccesso.</span>
                            <span hidden id="incrementErrorDeficitMessage" class="warning-capacity-increment">L'incremento da convenzione non è assegnato correttamente, ci sono ancora <span class="number"></span> posti da assegnare.</span>
                        </div>
                    </div>
                    <hr>
                    <div id="div-attachments">
                        <span hidden id="contextForAttachment">agreements</span>
                        <span hidden id="idItemForAttachment"></span>
                        <span hidden id="attachmentReadOnly">0</span>
                        <?= $this->element('AttachmentManager.button_attachment', ['id' => 'button_attachment', 'buttonLabel' => 'Allegati convenzione']); ?>
                    </div>
                    <hr>
                    <table class="table-agreement-sedi">
                        <tr>
                            <td width="6%"><label>Operativa</label></td>
                            <td width="8%"><label>Associata</label></td>
                            <td width="40%"><label>Indirizzo Struttura</label></td>
                            <td width="23%">
                                <label>Posti da convenzione</label><br>
                                Totale: <span id="totalCapacity"></span>
                            </td>
                            <td width="23%">
                                <label>Posti da incremento</label><br>
                                Totale: <span id="totalCapacityIncrement"></span>/<span id="maxCapacityIncrement"></span>
                            </td>
                        </tr>
                        <?php foreach($sedi as $sede) { ?>
                        <tr>
                            <td class="text-center">
                                <input disabled type="checkbox" name="sedi[<?=$sede['id']?>][active]" id="inputSedeActive<?=$sede['id']?>" data-id="<?=$sede['id']?>" class="agreement-sede-active">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="sedi[<?=$sede['id']?>][checked]" id="inputSedeCheck<?=$sede['id']?>" data-id="<?=$sede['id']?>" class="agreement-sede-checked">
                            </td>
                            <td>
                                <label for="inputSedeCheck<?=$sede['id']?>"><?=$sede['indirizzo'].' '.$sede['num_civico'].' - '.$sede['comune']['des_luo']?></label>
                            </td>
                            <td class="input">
                                <input disabled type="text" name="sedi[<?=$sede['id']?>][capacity]" id="inputSedeCapacity<?=$sede['id']?>" 
                                    class="form-control number-integer agreement-sede-capacity" placeholder="Capienza da convenzione">
                            </td>
                            <td class="input">
                                <input disabled type="text" name="sedi[<?=$sede['id']?>][capacity_increment]" id="inputSedeCapacityIncrement<?=$sede['id']?>" 
                                    class="form-control number-integer agreement-sede-capacity-increment" placeholder="Capienza da incremento">
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" id="deleteAgreement">Cancella</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="saveAgreement" >Salva</button>
            </div>
        </div>
    </div>
</div>

<?= $this->element('AttachmentManager.modal_attachment'); ?>