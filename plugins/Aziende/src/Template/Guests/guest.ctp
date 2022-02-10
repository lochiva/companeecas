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
            <?=__c('Ente '.$azienda['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['provincia']['s_prv'].')')?>
            <small v-if="guestData.id"><?=__c('Modifica ospite')?></small>
            <small v-else><?=__c('Nuovo ospite')?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
            <?php if ($role == 'admin') { ?>
            <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
            <?php } ?>
            <li><a href="<?=Router::url('/aziende/sedi/index/'.$azienda['id']);?>">Strutture</a></li>
            <li><a href="<?=Router::url('/aziende/guests/index/'.$sede['id']);?>">Ospiti</a></li>
            <li v-if="guestData.id" class="active">Modifica ospite</li>
            <li v-else class="active">Nuovo ospite</li>
        </ol>
    </section>

    <?= $this->Flash->render() ?>

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
