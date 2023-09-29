<?= $this->Html->script('Aziende.vue_payments', ['block' => 'script-vue']); ?>
<div class="col-xs-12" id=app-payments>
    <div class="box box-danger">

        <div class="box-header with-border">
            <i class="fa fa-euro"></i>
            <h3 class="box-title"><?= __c('Pagamenti') ?></h3>
            <a id="box-general-action" class="btn btn-info btn-xs pull-right" style="margin-left:10px" v-on:click="openModal(false)" :disabled="role !== 'admin' && role !== 'ragioneria'"><i class="fa fa-plus"></i> Nuovo</a>
        </div>

        <div class="box-body">
            <div class="container-fluid">
                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-sm-4 col-sm-offset-3">
                        <select class="form-control" v-model="docType" disabled>
                            <option seleted disabled>Seleziona</option>
                            <option v-for="dt in document_types" :value="dt.survey_id">{{dt.survey.title}}</option>
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <button class="btn btn-info" @click="generateDocs" :disabled="!!!docType || !documents.length">Seleziona</button>
                    </div>
                </div>
                <div class="table-responsive" v-if="payments.length">
                    <table class=table>
                        <thead>
                            <th>CIG</th>
                            <th>N° O.A. (netto)</th>
                            <th>N° O.S. (netto)</th>
                            <th>Data O.S. (netto)</th>
                            <th>Importo (netto)</th>
                            <th>N° O.A. (IVA)</th>
                            <th>N° O.S. (IVA)</th>
                            <th>Importo (IVA)</th>
                            <th>Protocollo</th>
                            <th>Note di commento</th>
                            <th>Azioni</th>
                        </thead>
                        <tbody>

                            <tr v-for="payment in payments">
                                <td>{{payment.cig}}</td>
                                <td>{{payment.oa_number_net}}</td>
                                <td>{{payment.os_number_net}}</td>
                                <td>{{payment.os_date_net}}</td>
                                <td>{{payment.net_amount}}</td>
                                <td>{{payment.oa_number_vat}}</td>
                                <td>{{payment.os_number_vat}}</td>
                                <td>{{payment.vat_amount}}</td>
                                <td>{{payment.protocol}}</td>
                                <td>
                                    <span class="badge btn-info" data-toggle="tooltip" data-placement="top" :title="payment.notes">
                                        <i class="fa fa-info"></i>
                                    </span>
                                </td>
                                <td v-if=payment?.documents?.length>
                                    <a id="box-general-action" class="btn btn-info btn-xs" v-on:click="downloadDocuments(payment.documents)" disabled> Scarica ({{payment.documents.length}})</a>
                                </td>
                                <td v-else>
                                    <input type="checkbox" class="checkbox" v-model="documents" :value="payment.id" disabled>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center" v-else>
                    Nessun pagamento trovato.
                </div>
            </div>
        </div>
    </div>

    <Transition>
        <div class="modal fade" :class=modalClass id="modal-payment" :style=modalStyle>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" v-on:click="closeModal">
                            <span>×</span></button>
                        <h4 class="modal-title">Nuovo pagamento</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <fieldset>
                                <legend>Dati fattura / nota di credito</legend>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.cig.valid}">
                                        <label class="control-label">{{paymentForm.cig.label}}</label>
                                        <input :type="paymentForm.cig.type" v-model="payment.cig" class="form-control" name="cig" :required="paymentForm.cig.required">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.billing_reference.valid}">
                                        <label class="control-label">{{paymentForm.billing_reference.label}}</label>
                                        <input :type="paymentForm.billing_reference.type" v-model="payment.billing_reference" class="form-control" name="billing_reference" :required="paymentForm.billing_reference.required">
                                    </div>
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.billing_date.valid}">
                                        <label class="control-label">{{paymentForm.billing_date.label}}</label>
                                        <input :type="paymentForm.billing_date.type" v-model="payment.billing_date" class="form-control" name="billing_date" :required="paymentForm.billing_date.required">
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset>
                                <legend>Netto</legend>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.oa_number_net.valid}">
                                        <label class="control-label">{{paymentForm.oa_number_net.label}}</label>
                                        <input :type="paymentForm.oa_number_net.type" v-model="payment.oa_number_net" class="form-control" name="oa_number_net" :required="paymentForm.oa_number_net.required">
                                    </div>
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.os_number_net.valid}">
                                        <label class="control-label">{{paymentForm.os_number_net.label}}</label>
                                        <input :type="paymentForm.os_number_net.type" v-model="payment.os_number_net" class="form-control" name="os_number_net" :required="paymentForm.os_number_net.required">
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.os_date_net.valid}">
                                        <label class="control-label">{{paymentForm.os_date_net.label}}</label>
                                        <input :type="paymentForm.os_date_net.type" v-model="payment.os_date_net" class="form-control" name="os_date_net" :required="paymentForm.os_date_net.required">
                                    </div>

                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.net_amount.valid}">
                                        <label class="control-label">{{paymentForm.net_amount.label}}</label>
                                        <input :type="paymentForm.net_amount.type" class="form-control" name="net_amount" v-model="payment.net_amount" :step="paymentForm.net_amount.step" :min="paymentForm.net_amount.min" :required="paymentForm.net_amount.required">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>IVA</legend>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.oa_number_vat.valid}">
                                        <label class="control-label">{{paymentForm.oa_number_vat.label}}</label>
                                        <input :type="paymentForm.oa_number_vat.type" v-model="payment.oa_number_vat" class="form-control" name="oa_number_vat" :required="paymentForm.oa_number_vat.required">
                                    </div>
                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.os_number_vat.valid}">
                                        <label class="control-label">{{paymentForm.os_number_vat.label}}</label>
                                        <input :type="paymentForm.os_number_vat.type" v-model="payment.os_number_vat" class="form-control" name="os_number_vat" :required="paymentForm.os_number_vat.required">
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.os_date_vat.valid}">
                                        <label class="control-label">{{paymentForm.os_date_vat.label}}</label>
                                        <input :type="paymentForm.os_date_vat.type" v-model="payment.os_date_vat" class="form-control" name="os_date_vat" :required="paymentForm.os_date_vat.required">
                                    </div>

                                    <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.vat_amount.valid}">
                                        <label class="control-label">{{paymentForm.vat_amount.label}}</label>
                                        <input :type="paymentForm.vat_amount.type" class="form-control" name="vat_amount" v-model="payment.vat_amount" :step="paymentForm.vat_amount.step" :min="paymentForm.vat_amount.min" :required="paymentForm.vat_amount.required">
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row">
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.protocol.valid}">
                                    <label class="control-label">{{paymentForm.protocol.label}}</label>
                                    <input :type="paymentForm.protocol.type" v-model="payment.protocol" class="form-control" name="protocol" :required="paymentForm.protocol.required">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12" :class="{'has-error': !paymentForm.notes.valid}">
                                    <label class="control-label">{{paymentForm.notes.label}}</label>
                                    <textarea v-model="payment.notes" class="form-control" name="notes" :required="paymentForm.notes.required"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" v-on:click="closeModal">Annulla</button>
                        <button type="button" class="btn btn-success" v-on:click="validateForm">Salva</button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>


</div>