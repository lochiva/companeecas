<?= $this->Html->script('Aziende.vue_payments', ['block' => 'script-vue']); ?>
<div class="col-xs-12" id=app-payments>
    <div class="box box-danger">

        <div class="box-header with-border">
            <i class="fa fa-euro"></i>
            <h3 class="box-title"><?= __c('Pagamenti') ?></h3>
            <a v-if="canWrite" id="box-general-action" class="btn btn-info btn-xs pull-right" style="margin-left:10px" v-on:click="loadModal(false)"><i class="fa fa-plus"></i> Nuovo</a>
        </div>

        <div class="box-body">
            <div class="container-fluid">
                <div class="row" v-if="payments.length" style="margin-bottom: 15px;">
                    <div class="form-group" style="margin-bottom: 30px;">
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
                </div>
                <div class="row">
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
                                <th>Note</th>
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
                                    <td>
                                        <div class="btn-group">
                                            <a v-if="canWrite" class="btn btn-xs btn-default edit" v-on:click="loadModal(payment.id)">
                                                <i data-toggle="tooltip" class="fa  fa-pencil" data-original-title="Modifica"></i>
                                            </a>
                                            <a v-if="canWrite" class="btn btn-xs btn-default" v-on:click="deletePayment(payment.id)">
                                                <i data-toggle="tooltip" class="fa fa-trash" data-original-title="Elimina"></i>
                                            </a>
                                            <template v-if=payment?.documents?.length>
                                                <a id="box-general-action" class="btn btn-info btn-xs" v-on:click="downloadDocuments(payment.documents)" disabled> Scarica ({{payment.documents.length}})</a>
                                            </template>
                                            <template v-else>
                                                <a class="btn btn-xs btn-default" disabled>
                                                    <i data-toggle="tooltip" class="fa fa-square-o" data-original-title="Seleziona"></i>
                                                </a>
                                            </template>
                                        </div>
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
    </div>

    <Transition name="fadeVue" :duration="300">
        <div class="modal fade in" id="modal-payment" style="display:block;" v-if="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" v-on:click="closeModal">
                            <span>×</span></button>
                        <h4 class="modal-title">Nuovo pagamento</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">

                            <h5>Dati fattura / nota di credito</h5>

                            <div class="form-group">

                                <div class="input" :class="{'has-error': !paymentForm.cig.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.cig.attrs.required}" :for="paymentForm.cig.attrs.id">{{paymentForm.cig.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model.trim="payment.cig" class="form-control" v-bind="paymentForm.cig.attrs">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.billing_reference.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.billing_reference.attrs.required}" :for="paymentForm.billing_reference.attrs.id">{{paymentForm.billing_reference.label}}</label>
                                    <div class="col-sm-4"><input v-model.trim="payment.billing_reference" class="form-control" v-bind="paymentForm.billing_reference.attrs"></div>
                                </div>
                                <div class="input" :class="{'has-error': !paymentForm.billing_date.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.billing_date.attrs.required}" :for="paymentForm.billing_date.attrs.id">{{paymentForm.billing_date.label}}</label>
                                    <div class="col-sm-4"><input v-model="payment.billing_date" class="form-control" v-bind="paymentForm.billing_date.attrs"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.billing_net_amount.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.billing_net_amount.attrs.required}" :for="paymentForm.billing_net_amount.attrs.id">{{paymentForm.billing_net_amount.label}}</label>
                                    <div class="col-sm-4"><input v-model.number="payment.billing_net_amount" class="form-control" v-bind="paymentForm.billing_net_amount.attrs"></div>
                                </div>
                                <div class="input" :class="{'has-error': !paymentForm.billing_vat_amount.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.billing_vat_amount.attrs.required}" :for="paymentForm.billing_vat_amount.attrs.id">{{paymentForm.billing_vat_amount.label}}</label>
                                    <div class="col-sm-4"><input v-model.number="payment.billing_vat_amount" class="form-control" v-bind="paymentForm.billing_vat_amount.attrs"></div>
                                </div>
                            </div>

                            <hr>


                            <h5>Netto</h5>
                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.oa_number_net.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.oa_number_net.attrs.required}" :for="paymentForm.oa_number_net.attrs.id">{{paymentForm.oa_number_net.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model.trim="payment.oa_number_net" class="form-control" v-bind="paymentForm.oa_number_net.attrs">
                                    </div>
                                </div>
                                <div class="input" :class="{'has-error': !paymentForm.os_number_net.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.os_number_net.attrs.required}" :for="paymentForm.os_number_net.attrs.id">{{paymentForm.os_number_net.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model.trim="payment.os_number_net" class="form-control" v-bind="paymentForm.os_number_net.attrs">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.os_date_net.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.os_date_net.attrs.required}" :for="paymentForm.os_date_net.attrs.id">{{paymentForm.os_date_net.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model="payment.os_date_net" class="form-control" v-bind="paymentForm.os_date_net.attrs">
                                    </div>
                                </div>

                                <div class="input" :class="{'has-error': !paymentForm.net_amount.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.net_amount.attrs.required}" :for="paymentForm.net_amount.attrs.id">{{paymentForm.net_amount.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-bind="paymentForm.net_amount.attrs" class="form-control" name="net_amount" v-model.number="payment.net_amount">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h5>IVA</h5>
                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.oa_number_vat.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.oa_number_vat.attrs.required}" :for="paymentForm.oa_number_vat.attrs.id">{{paymentForm.oa_number_vat.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model.trim="payment.oa_number_vat" class="form-control" v-bind="paymentForm.oa_number_vat.attrs">
                                    </div>
                                </div>
                                <div class="input" :class="{'has-error': !paymentForm.os_number_vat.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.os_number_vat.attrs.required}" :for="paymentForm.os_number_vat.attrs.id">{{paymentForm.os_number_vat.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model.trim="payment.os_number_vat" class="form-control" v-bind="paymentForm.os_number_vat.attrs">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="input" :class="{'has-error': !paymentForm.os_date_vat.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.os_date_vat.attrs.required}" :for="paymentForm.os_date_vat.attrs.id">{{paymentForm.os_date_vat.label}}</label>
                                    <div class="col-sm-4">
                                        <input v-model="payment.os_date_vat" class="form-control" v-bind="paymentForm.os_date_vat.attrs">
                                    </div>
                                </div>

                                <div class="input" :class="{'has-error': !paymentForm.vat_amount.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.vat_amount.attrs.required}" :for="paymentForm.vat_amount.attrs.id">{{paymentForm.vat_amount.label}}</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" v-model.number="payment.vat_amount" v-bind="paymentForm.vat_amount.attrs">
                                    </div>
                                </div>
                            </div>

                            <hr>


                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.protocol.valid}">
                                    <label class="control-label col-sm-2" :class="{'required': paymentForm.protocol.attrs.required}" :for="paymentForm.protocol.attrs.id">{{paymentForm.protocol.label}}</label>
                                    <div class="col-sm-4">
                                        <input :type="paymentForm.protocol.type" v-model.trim="payment.protocol" class="form-control" name="protocol" v-bind="paymentForm.protocol.attrs">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input" :class="{'has-error': !paymentForm.notes.valid}">
                                    <label class="control-label col-sm-2" :for="paymentForm.notes.attrs.id" :class="{'required': paymentForm.notes.attrs.required}">{{paymentForm.notes.label}}</label>
                                    <div class="col-sm-10">
                                        <textarea v-model.trim="payment.notes" class="form-control" name="notes" v-bind="paymentForm.notes.attrs" style="resize: none;"></textarea>
                                    </div>
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