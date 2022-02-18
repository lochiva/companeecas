<?php
use Cake\Routing\Router;
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
                    <hr>
                    <?php foreach($sedi as $sede) { ?>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="checkbox" name="sedi[<?=$sede['id']?>][active]" id="inputSedeCheck<?=$sede['id']?>" data-id="<?=$sede['id']?>" class="agreement-sede-check">
                                <label for="inputSedeCheck<?=$sede['id']?>"><?=$sede['indirizzo'].' '.$sede['num_civico'].' - '.$sede['comune']['des_luo']?></label>
                            </div>
                            <div class="col-sm-4">
                                <input disabled type="text" name="sedi[<?=$sede['id']?>][capacity]" id="inputSedeCapacity<?=$sede['id']?>" 
                                    class="form-control number-decimal agreement-sede-capacity" placeholder="Capienza da convenzione">
                            </div>
                        </div>
                    <?php } ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="saveAgreement" >Salva</button>
            </div>
        </div>
    </div>
</div>