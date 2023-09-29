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
                            <th>Importo</th>
                            <th>N° O.A.</th>
                            <th>N° O.S.</th>
                            <th>Data O.S.</th>
                            <th>N° fattura</th>
                            <th>Data fattura</th>
                            <th>N° protocollo</th>
                            <th>CIG</th>
                            <th>Note di commento</th>
                            <th>Lettera</th>
                        </thead>
                        <tbody>

                            <tr v-for="payment in payments">
                                <td>€ {{payment.net_amount}}</td>
                                <td>{{payment.oa_number}}</td>
                                <td>{{payment.os_number}}</td>
                                <td>{{payment.os_date}}</td>
                                <td>{{payment.billing_reference}}</td>
                                <td>{{payment.billing_date}}</td>
                                <td>{{payment.protocol}}</td>
                                <td>{{payment.cig}}</td>
                                <td>{{payment.notes}}</td>
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
                            <div class="row">
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.net_amount.valid}">
                                    <label class="control-label">{{paymentForm.net_amount.label}}</label>
                                    <input :type="paymentForm.net_amount.type" class="form-control" name="net_amount" v-model="payment.net_amount" :step="paymentForm.net_amount.step" :min="paymentForm.net_amount.min" :required="paymentForm.net_amount.required">
                                </div>
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.oa_number.valid}">
                                    <label class="control-label">{{paymentForm.oa_number.label}}</label>
                                    <input :type="paymentForm.oa_number.type" v-model="payment.oa_number" class="form-control" name="oa_number" :required="paymentForm.oa_number.required">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.os_number.valid}">
                                    <label class="control-label">{{paymentForm.os_number.label}}</label>
                                    <input :type="paymentForm.os_number.type" v-model="payment.os_number" class="form-control" name="os_number" :required="paymentForm.os_number.required">
                                </div>
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.os_date.valid}">
                                    <label class="control-label">{{paymentForm.os_date.label}}</label>
                                    <input :type="paymentForm.os_date.type" v-model="payment.os_date" class="form-control" name="os_date" :required="paymentForm.os_date.required">
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

                            <div class="row">
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.protocol.valid}">
                                    <label class="control-label">{{paymentForm.protocol.label}}</label>
                                    <input :type="paymentForm.protocol.type" v-model="payment.protocol" class="form-control" name="protocol" :required="paymentForm.protocol.required">
                                </div>
                                <div class="form-group col-sm-6" :class="{'has-error': !paymentForm.cig.valid}">
                                    <label class="control-label">{{paymentForm.cig.label}}</label>
                                    <input :type="paymentForm.cig.type" v-model="payment.cig" class="form-control" name="cig" :required="paymentForm.cig.required">
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