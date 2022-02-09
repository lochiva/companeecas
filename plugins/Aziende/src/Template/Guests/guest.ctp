<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role');
?>
<script>
    var role = '<?= $role ?>';
    var sede_id = '<?= $sede['id'] ?>';
</script>
<?php $this->assign('title', 'Ospiti') ?>
<?= $this->Html->css('Aziende.guests'); ?>
<?= $this->Html->script('Aziende.guests', ['block']); ?>
<?= $this->Html->script('Aziende.vue-guest', ['block' => 'script-vue']); ?>

<div id='app-guest'>

    <section class="content-header">
        <h1>
            <?=__c('Diario '.$azienda['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune'].' ('.$sede['provincia'].')')?>
            <small v-if="guestData.id"><?=__c('Modifica ospite')?></small>
            <small v-else><?=__c('Nuovo ospite')?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
            <li><?=__c('Diario di Bordo')?></li>
            <li><a href="<?=Router::url('/diary/home/index');?>"> <?=__c('Partner')?></a></li>
            <li><a href="<?=Router::url('/diary/home/partnerSedi/'.$azienda['id'].($role == 'admin' ? '/'.$sede['id_progetto'] : ''));?>"> <?=__c('Sedi')?></a></li>
            <li><a href="<?=Router::url('/diary/guests/index/'.$sede['id']);?>"> <?=__c('Ospiti')?></a></li>
            <li v-if="guestData.id" class="active"><?=__c('Modifica ospite')?></li>
            <li v-else class="active"><?=__c('Nuovo ospite')?></li>
        </ol>
    </section>

    <?= $this->Flash->render() ?>

    <div v-clock>
        <div v-if="guestData.status.value == 1 && statusScadenza == 1" class="message-status-scadenza alert status-scadenza">Attenzione! L'ospite risulta in scadenza.</div>
        <div v-if="guestData.status.value == 1 && statusScadenza == 2" class="message-status-scadenza alert status-scaduto">Attenzione! L'ospite risulta scaduto.</div>
        <div v-if="guestData.status.value == 2" class="message-dimesso alert">L'ospite è stato dimesso in data {{guestData.exit_date}} con motivazione {{guestData.exit_type}}.</div>
        <div v-if="guestData.status.value == 3" class="message-authorize-transfer alert">
            <div>L'ospite è in stato "Trasferimento autorizzato" con destinazione {{transferDestination}}.</div>
            <div class="transfer-buttons">
                <button type="button" class="btn btn-confirm-transfer" @click="confirmTransfer">Conferma trasferimento</button>
                <button v-if="role == 'admin'" type="button" class="btn btn-default" @click="cancelTransfer">Annulla trasferimento</button>
            </div>
        </div>
        <div v-if="guestData.status.value == 4" class="message-confirm-transfer alert">L'ospite è in stato "Trasferimento in uscita" con destinazione {{guestData.exit_destination}}.</div>
        <div v-if="guestData.status.value == 5" class="message-accept-transfer alert">
            <div>L'ospite è in stato "Trasferimento in ingresso" con provenienza {{transferProvenance}}.</div>
            <div><b>Note trasferimento:</b> {{transferNote}}</div>
            <div class="transfer-buttons">
                <button type="button" class="btn btn-accept-transfer" @click="checkAcceptTransferFamily">Conferma ingresso</button>
            </div>
        </div>
        <div v-if="guestData.status.value == 6" class="message-transfered alert">L'ospite è stato trasferito in {{guestData.exit_destination}}.</div>
        <div v-if="expiredSurveys.length > 0" class="message-expired-surveys alert">
            Il periodo limite di compilazione dei diari
            <ul>
                <li v-for="expiredSurvey in expiredSurveys">{{expiredSurvey.title}}</li>
            </ul>
            è scaduto.
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-guests-diary" class="box box-diary">
                    <div class="box-header with-border">
                    <i class="fa fa-list-alt"></i>
                    <h3 class="box-title">Dati intestazione ospite {{guestData.code}}</h3>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" id="formGuest">
                            <div class="form-group">
                                <div class="col-md-5" :class="{'has-error': guestData.name.hasError}">
                                    <label class="required" for="guestName"><?= __('Nome') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="255" class="form-control" name="name" id="guestName" v-model="guestData.name.value" />
                                </div>
                                <div class="col-md-5" :class="{'has-error': guestData.surname.hasError}">
                                    <label class="required" for="guestSurname"><?= __('Cognome') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="255" class="form-control" name="surname" id="guestSurname" v-model="guestData.surname.value" />
                                </div>
                                <div class="col-md-2 div-pregnant-check">
                                    <label for="guestPregnant"><?= __('Incinta') ?></label>
                                    <input :disabled="guestData.locked" type="checkbox" class="pregnant-check" name="pregnant" id="guestPregnant" v-model="guestData.pregnant" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.cf.hasError}">
                                    <label for="guestCf"><?= __('Codice fiscale') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="16" class="form-control" name="cf" id="guestCf" v-model="guestData.cf.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.tel.hasError}">
                                    <label for="guestTel"><?= __('Telefono') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="16" class="form-control" name="tel" id="guestTel" v-model="guestData.tel.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.email.hasError}">
                                    <label for="guestEmail"><?= __('Email') ?></label>
                                    <input :disabled="guestData.locked" type="email" maxlength="64" class="form-control" name="email" id="guestEmail" v-model="guestData.email.value" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.birth_date.hasError}">
                                    <label for="guestBirthDate"><?= __('Data di nascita') ?></label>
                                    <datepicker :disabled="guestData.locked" :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="true" :monday-first="true" input-class="form-control" id="guestBirthDate" v-model="guestData.birth_date.value"></datepicker>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.citizenship.hasError}">
                                    <label for="guestCitizenship"><?= __('Cittadinanza') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="64" class="form-control" name="citizenship" id="guestCitizenship" v-model="guestData.citizenship.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.ethnicity.hasError}">
                                    <label for="guestEthnicity"><?= __('Etnia') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="64" class="form-control" name="ethnicity" id="guestEthnicity" v-model="guestData.ethnicity.value" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.native_language.hasError}">
                                    <label for="guestNativeLanguage"><?= __('Lingua madre') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="64" class="form-control" name="native_language" id="guestNativeLanguage" v-model="guestData.native_language.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.other_languages.hasError}">
                                    <label for="guestOtherLanguages"><?= __('Altre lingue conosciute') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="255" class="form-control" name="other_languages" id="guestOtherLanguages" v-model="guestData.other_languages.value" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" :class="{'has-error': guestData.legal_situation.hasError}">
                                    <label for="guestLegalSituation"><?= __('Situazione giuridica all\'ingresso') ?></label>
                                    <textarea :disabled="guestData.locked" class="form-control guest-text-area" name="legal_situation" id="guestLegalSituation" v-model="guestData.legal_situation.value"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.iban.hasError}">
                                    <label for="guestIban"><?= __('IBAN') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="64" class="form-control" name="iban" id="guestIban" v-model="guestData.iban.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.opening_date.hasError}">
                                    <label for="guestOpeningDate"><?= __('Data apertura') ?></label>
                                    <datepicker :disabled="guestData.locked" :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="true" :monday-first="true" input-class="form-control" id="guestOpeningDate" v-model="guestData.opening_date.value"></datepicker>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.reference_bank.hasError}">
                                    <label for="guestReferenceBank"><?= __('Banca di riferimento') ?></label>
                                    <input :disabled="guestData.locked" type="text" maxlength="255" class="form-control" name="reference_bank" id="guestReferenceBank" v-model="guestData.reference_bank.value" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12" :class="{'has-error': guestData.biography.hasError}">
                                    <label for="guestBiography"><?= __('Biografia sintetica') ?></label>
                                    <textarea :disabled="guestData.locked" class="form-control guest-text-area" name="biography" id="guestBiography" v-model="guestData.biography.value"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.guest_type_id.hasError}">
                                    <label class="required" for="guestType"><?= __('Tipologia ospite') ?></label>
                                    <select :disabled="guestData.locked" class="form-control" name="guest_type_id" id="guestType" v-model="guestData.guest_type_id.value">
                                        <option value=""></option>
                                        <option v-for="type in guestsTypes" :value="type.id">{{type.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.service_type_id.hasError}">
                                    <label class="required" for="serviceType"><?= __('Tipologia servizio') ?></label>
                                    <select disabled class="form-control" name="service_type_id" id="serviceType" v-model="guestData.service_type_id.value" 
                                        @change="newGuestData.service_type_id.value = guestData.service_type_id.value">
                                        <option value=""></option>
                                        <option v-for="service in services" :value="service.id">{{service.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.status.hasError}">
                                    <label class="required" for="status"><?= __('Status') ?></label>
                                    <select disabled class="form-control" name="status" id="status" v-model="guestData.status.value">
                                        <option v-for="status in statuses" :value="status.id">{{status.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6" :class="{'has-error': guestData.arrival_date.hasError}">
                                    <label class="required" for="arrivalDate"><?= __('Data di arrivo') ?></label>
                                    <datepicker :disabled="guestData.locked" :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" v-model="guestData.arrival_date.value"></datepicker>
                                </div>
                                <div class="col-md-6">
                                    <label for="guestExtension"><?= __('Proroga (gg)') ?></label>
                                    <input :disabled="role != 'admin' || guestData.locked" type="text" class="form-control number-integer" name="extension" id="guestExtension" v-model="guestData.extension" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="" for="dueDate"><?= __('Data di scadenza') ?></label>
                                    <datepicker disabled :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" v-model="guestData.due_date"></datepicker>
                                </div>
                                <div class="col-md-6">
                                    <label class="" for="noticeDate"><?= __('Data preavviso') ?></label>
                                    <datepicker disabled :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" v-model="guestData.notice_date"></datepicker>
                                </div>
                            </div>
                            <button :disabled="guestData.id == '' || guestData.locked || guestData.status.value == 3" type="button" class="btn btn-violet pull-right" data-target="#modalGuestExit" @click="openExitModal">Procedura di uscita</button>
                            <button :disabled="guestData.status.value != 2 && guestData.status.value != 6" type="button" class="btn btn-default pull-right" :data-id="guestData.id" id="downloadExitDocument">Documento di dimissione</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="clear-both"></div>

    <md-speed-dial class="fab-position md-fixed" md-direction="top" md-event="click">
        <md-speed-dial-target class="md-fab fab-main">
            <md-icon class="md-morph-initial">add</md-icon>
            <md-icon class="md-morph-final">close</md-icon>
        </md-speed-dial-target>

        <md-speed-dial-content>
            <a :href="'<?=Router::url('/diary/guests/index/');?>'+guestData.sede_id">
                <md-button class="md-fab fab-default" title="Annulla">
                    <md-icon>arrow_back</md-icon>
                </md-button>
            </a>

            <md-button class="md-fab fab-success save-guest-stay" @click="checkFormGuest()" title="Salva">
                <md-icon><i class="glyphicon glyphicon-floppy-disk fab-icon"></i></md-icon>
            </md-button>

            <md-button class="md-fab fab-primary save-guest-exit" @click="checkFormGuest(true)" title="Salva ed esci">
                <md-icon><i class="glyphicon glyphicon-floppy-remove fab-icon"></i></md-icon>
            </md-button>
        </md-speed-dial-content>
    </md-speed-dial>

</div>
