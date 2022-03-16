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
            <small v-if="guestData.id.value"><?=__c('Modifica ospite')?></small>
            <small v-else><?=__c('Nuovo ospite')?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
            <?php if ($role == 'admin') { ?>
            <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
            <?php } ?>
            <li><a href="<?=Router::url('/aziende/sedi/index/'.$azienda['id']);?>">Strutture</a></li>
            <li><a href="<?=Router::url('/aziende/guests/index/'.$sede['id']);?>">Ospiti</a></li>
            <li v-if="guestData.id.value" class="active">Modifica ospite</li>
            <li v-else class="active">Nuovo ospite</li>
        </ol>
    </section>

    <?= $this->Flash->render() ?>

    <div v-clock>
        <div v-if="guestStatus == 2" class="message-exiting alert">
            L'ospite è in stato "In uscita" con motivazione {{exitData.type}}.
            <div><b>Note uscita:</b> {{exitData.note}}</div>
            <div class="exit-buttons">
                <button type="button" class="btn btn-danger" @click="confirmExit()">Conferma uscita</button>
            </div>
        </div>
        <div v-if="guestStatus == 3" class="message-exited alert">L'ospite è stato dimesso in data {{exitData.date}} con motivazione {{exitData.type}}.</div>
        <div v-if="guestStatus == 4" class="message-transferring alert">L'ospite è in stato "Trasferimento in uscita" con destinazione {{transferData.destination}}.</div>
        <div v-if="guestStatus == 5" class="message-accept-transfer alert">
            <div>L'ospite è in stato "Trasferimento in ingresso" con provenienza {{transferData.provenance}}.</div>
            <div><b>Note trasferimento:</b> {{transferData.note}}</div>
            <div class="transfer-buttons">
                <button type="button" class="btn btn-accept-transfer" @click="acceptTransfer()">Conferma ingresso</button>
            </div>
        </div>
        <div v-if="guestStatus == 6" class="message-transferred alert">L'ospite è stato trasferito nella struttura {{transferData.destination}} in data {{transferData.date}}.</div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-guests-diary" class="box box-diary">
                    <div class="box-header with-border">
                        <i class="fa fa-list-alt"></i>
                        <h3 class="box-title">Dati ospite {{guestData.cui.value}}</h3>
                        <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" id="formGuest">
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.check_in_date.hasError}">
                                    <label :class="{'required': guestData.check_in_date.required}" for="guestCheckInDate"><?= __('Check-in') ?></label>
                                    <datepicker :disabled="guestStatus != 1" :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="true" :monday-first="true" input-class="form-control" 
                                        id="guestCheckInDate" v-model="guestData.check_in_date.value"></datepicker>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.cui.hasError}">
                                    <label :class="{'required': guestData.cui.required}" for="guestCui"><?= __('CUI') ?></label>
                                    <input :disabled="guestStatus != 1" type="text" maxlength="7" class="form-control" name="cui" id="guestCui" v-model="guestData.cui.value" @change="setDraft()" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.vestanet_id.hasError}">
                                    <label :class="{'required': guestData.vestanet_id.required}" for="guestVestanetId"><?= __('ID Vestanet') ?></label>
                                    <input :disabled="guestStatus != 1" type="text" maxlength="10" class="form-control" name="vestanet_id" id="guestVestanetId" v-model="guestData.vestanet_id.value" @change="setDraft()" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.name.hasError}">
                                    <label :class="{'required': guestData.name.required}" for="guestName"><?= __('Nome') ?></label>
                                    <input :disabled="guestStatus != 1" type="text" maxlength="255" class="form-control" name="name" id="guestName" v-model="guestData.name.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.surname.hasError}">
                                    <label :class="{'required': guestData.surname.required}" for="guestSurname"><?= __('Cognome') ?></label>
                                    <input :disabled="guestStatus != 1" type="text" maxlength="255" class="form-control" name="surname" id="guestSurname" v-model="guestData.surname.value" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 div-input-check" :class="{'has-error': guestData.minor.hasError}">
                                    <label :class="{'required': guestData.minor.required}" for="guestMinor"><?= __('Minore') ?></label>
                                    <input :disabled="guestStatus != 1" type="checkbox" class="input-check" name="minor" id="guestMinor" v-model="guestData.minor.value" @change="resetMinor()" />
                                </div>
                                <div class="col-md-4">
                                    <div v-show="guestData.minor.value" class="div-input-check" :class="{'has-error': guestData.minor_family.hasError}">
                                        <label :class="{'required': guestData.minor_family.required}" for="guestMinorFamily"><?= __('Con riferimento al nucleo familiare') ?></label>
                                        <input :disabled="guestStatus != 1" type="checkbox" class="input-check" name="minor_family" id="guestMinorFamily" v-model="guestData.minor_family.value" 
                                            @change="changeMinorFamily(true)" />
                                    </div>
                                    <div v-show="guestData.minor.value"  :class="{'has-error': guestData.family_guest.hasError}">
                                        <v-select :disabled="guestStatus != 1 || !guestData.minor_family.value" name="family_guest" id="guestFamilyGuest" :options="familyGuests" v-model="guestData.family_guest.value"
                                            @search="searchGuest" placeholder="Seleziona un ospite" :filterable="false">
                                            <template #no-options="{ search, searching }">
                                                <template v-if="searching">
                                                    Nessun ospite trovato per <em>{{ search }}</em>.
                                                </template>
                                                <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un ospite.</em>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                                <div v-show="guestData.minor.value"  class="col-md-3 div-input-check" :class="{'has-error': guestData.minor_alone.hasError}">
                                    <label :class="{'required': guestData.minor_alone.required}" for="guestMinorAlone"><?= __('Si dichiara minore solo') ?></label>
                                    <input :disabled="guestStatus != 1" type="checkbox" class="input-check" name="minor_alone" id="guestMinorAlone" v-model="guestData.minor_alone.value" 
                                        @change="changeMinorFamily(false)" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.birthdate.hasError}">
                                    <label :class="{'required': guestData.birthdate.required}" for="guestBirthdate"><?= __('Data di nascita') ?></label>
                                    <datepicker :disabled="guestStatus != 1" :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="true" :monday-first="true" input-class="form-control" 
                                        id="guestBirthdate" v-model="guestData.birthdate.value"></datepicker>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.country_birth.hasError}">
                                    <label :class="{'required': guestData.country_birth.required}" for="guestCountryBirth"><?= __('Nazionalità') ?></label>
                                    <v-select :disabled="guestStatus != 1" name="country_birth" id="guestCountryBirth" :options="countries" v-model="guestData.country_birth.value"
                                        @search="searchCountry" placeholder="Seleziona una nazione" :filterable="false">
                                        <template #no-options="{ search, searching }">
                                            <template v-if="searching">
                                                Nessuna nazione trovata per <em>{{ search }}</em>.
                                            </template>
                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una nazione.</em>
                                        </template>
                                    </v-select>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.sex.hasError}">
                                    <label :class="{'required': guestData.sex.required}" for="guestSex"><?= __('Sesso') ?></label>
                                    <select :disabled="guestStatus != 1" class="form-control" name="sex" id="guestSex" v-model="guestData.sex.value">
                                        <option value=""></option>
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 div-input-check" :class="{'has-error': guestData.draft.hasError}">
                                    <label :class="{'required': guestData.draft.required}" for="guestDraft"><?= __('Stato anagrafica in bozza') ?></label>
                                    <input :disabled="guestStatus != 1" disabled type="checkbox" class="input-check" name="draft" id="guestDraft" v-model="guestData.draft.value" />
                                </div>
                                <div v-show="guestData.draft_expiration.value" class="col-md-4" :class="{'has-error': guestData.draft_expiration.hasError}">
                                    <label :class="{'required': guestData.draft_expiration.required}" for="guestDraftExpiration"><?= __('Scadenza stato bozza') ?></label>
                                    <datepicker disabled :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="false" :monday-first="true" input-class="form-control" 
                                        id="guestDraftExpiration" v-model="guestData.draft_expiration.value"></datepicker>
                                </div>
                                <div class="col-md-4 div-input-check" :class="{'has-error': guestData.suspended.hasError}">
                                    <label :class="{'required': guestData.suspended.required}" for="guestSuspended"><?= __('Sospeso') ?></label>
                                    <input :disabled="guestStatus != 1" type="checkbox" class="input-check" name="suspended" id="guestSuspended" v-model="guestData.suspended.value" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <button :disabled="guestData.id.value == '' || guestStatus != 1" type="button" class="btn btn-violet pull-right btn-transfer" @click="openTransferModal()">Trasferimento</button>
                        <button v-if="role == 'admin'" :disabled="guestData.id.value == '' || guestStatus != 1" type="button" class="btn btn-danger pull-right" @click="openExitModal()">Uscita</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content no-min-height">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-guests-history" class="box collapsed-box">
                    <div class="box-header with-border">
                        <i class="fa fa-history"></i>
                        <h3 class="box-title"><?=__c('Storico')?></h3>
                        <button type="button" class="btn btn-box-tool pull-right" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <th>Ente</th>
                                <th>Struttura</th>
                                <th>Stato</th>
                                <th>Data</th>
                                <th>Tipo uscita</th>
                                <th>Destinazione</th>
                                <th>Provenienza</th>
                                <th>Note</th>
                                <th>Operatore</th>
                            </thead>
                            <tbody>
                                <tr v-for="history in guestHistory">
                                    <td>{{ history.azienda }}</td>
                                    <td>{{ history.sede }}</td>
                                    <td>{{ history.status }}</td>
                                    <td>{{ history.operation_date }}</td>
                                    <td>{{ history.exit_type }}</td>
                                    <td>{{ history.destination }}</td>
                                    <td>{{ history.provenance }}</td>
                                    <td>{{ history.note }}</td>
                                    <td>{{ history.operator }}</td>
                                </tr>
                            </tbody>
                        </table>
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
            <a :href="'<?=Router::url('/aziende/guests/index/');?>'+guestData.sede_id.value">
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

    <?= $this->element('Aziende.modal_guest_exit') ?>
    <?= $this->element('Aziende.modal_guest_transfer') ?>

</div>
