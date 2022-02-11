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
                                <div class="col-md-4" :class="{'has-error': guestData.cui.hasError}">
                                    <label :class="{'required': guestData.cui.required}" for="guestCui"><?= __('CUI') ?></label>
                                    <input type="text" maxlength="7" class="form-control" name="cui" id="guestCui" v-model="guestData.cui.value" @change="setDraft()" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.vestanet_id.hasError}">
                                    <label :class="{'required': guestData.vestanet_id.required}" for="guestVestanetId"><?= __('ID Vestanet') ?></label>
                                    <input type="text" maxlength="10" class="form-control" name="vestanet_id" id="guestVestanetId" v-model="guestData.vestanet_id.value" @change="setDraft()" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.name.hasError}">
                                    <label :class="{'required': guestData.name.required}" for="guestName"><?= __('Nome') ?></label>
                                    <input type="text" maxlength="255" class="form-control" name="name" id="guestName" v-model="guestData.name.value" />
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.surname.hasError}">
                                    <label :class="{'required': guestData.surname.required}" for="guestSurname"><?= __('Cognome') ?></label>
                                    <input type="text" maxlength="255" class="form-control" name="surname" id="guestSurname" v-model="guestData.surname.value" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 div-input-check" :class="{'has-error': guestData.minor.hasError}">
                                    <label :class="{'required': guestData.minor.required}" for="guestMinor"><?= __('Minore') ?></label>
                                    <input type="checkbox" class="input-check" name="minor" id="guestMinor" v-model="guestData.minor.value" />
                                </div>
                                <div class="col-md-4">
                                    <div v-show="guestData.minor.value" class="div-input-check" :class="{'has-error': guestData.minor_family.hasError}">
                                        <label :class="{'required': guestData.minor_family.required}" for="guestMinorFamily"><?= __('Con riferimento al nucleo familiare') ?></label>
                                        <input type="checkbox" class="input-check" name="minor_family" id="guestMinorFamily" v-model="guestData.minor_family.value" 
                                            @change="guestData.family_guest.value = ''; familyGuests = []; guestData.minor_alone.value = '';" />
                                    </div>
                                    <div v-show="guestData.minor.value"  :class="{'has-error': guestData.family_guest.hasError}">
                                        <v-select :disabled="!guestData.minor_family.value" name="family_guest" id="guestFamilyGuest" :options="familyGuests" v-model="guestData.family_guest.value"
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
                                    <input type="checkbox" class="input-check" name="minor_alone" id="guestMinorAlone" v-model="guestData.minor_alone.value" 
                                        @change="guestData.family_guest.value = ''; familyGuests = []; guestData.minor_family.value = '';" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4" :class="{'has-error': guestData.birthdate.hasError}">
                                    <label :class="{'required': guestData.birthdate.required}" for="guestBirthdate"><?= __('Data di nascita') ?></label>
                                    <datepicker :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="true" :monday-first="true" input-class="form-control" 
                                        id="guestBirthdate" v-model="guestData.birthdate.value"></datepicker>
                                </div>
                                <div class="col-md-4" :class="{'has-error': guestData.country_birth.hasError}">
                                    <label :class="{'required': guestData.country_birth.required}" for="guestCountryBirth"><?= __('Paese di nascita') ?></label>
                                    <v-select name="country_birth" id="guestCountryBirth" :options="countries" v-model="guestData.country_birth.value"
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
                                    <select class="form-control" name="sex" id="guestSex" v-model="guestData.sex.value">
                                        <option value=""></option>
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 div-input-check" :class="{'has-error': guestData.draft.hasError}">
                                    <label :class="{'required': guestData.draft.required}" for="guestDraft"><?= __('Stato anagrafica in bozza') ?></label>
                                    <input disabled type="checkbox" class="input-check" name="draft" id="guestDraft" v-model="guestData.draft.value" />
                                </div>
                                <div v-show="guestData.draft_expiration.value" class="col-md-4" :class="{'has-error': guestData.draft_expiration.hasError}">
                                    <label :class="{'required': guestData.draft_expiration.required}" for="guestDraftExpiration"><?= __('Scadenza stato bozza') ?></label>
                                    <datepicker disabled :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="false" :monday-first="true" input-class="form-control" 
                                        id="guestDraftExpiration" v-model="guestData.draft_expiration.value"></datepicker>
                                </div>
                                <div class="col-md-4 div-input-check" :class="{'has-error': guestData.suspended.hasError}">
                                    <label :class="{'required': guestData.suspended.required}" for="guestSuspended"><?= __('Sospeso') ?></label>
                                    <input type="checkbox" class="input-check" name="suspended" id="guestSuspended" v-model="guestData.suspended.value" />
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

</div>
