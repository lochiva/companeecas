<?php

use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role');
?>

<!-- VUE -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.2/axios.js"></script>

<!-- SELECT VUE -->
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

<!-- DATEPICKER -->
<script src="https://unpkg.com/vuejs-datepicker"></script>
<script src="https://unpkg.com/vuejs-datepicker/dist/locale/translations/it.js"></script>

<?php $this->assign('title', 'Segnalazioni') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->css('Surveys.vue-interviews'); ?>
<?= $this->Html->css('Reports.reports'); ?>
<?= $this->Html->script('Reports.reports', ['block']); ?>
<?= $this->Html->script('Reports.vue-reports', ['block' => 'script-vue']); ?>

<div id='app-report'>

    <section class="content-header">
        <h1 v-if="reportId"><?= __c('Modifica caso') ?></h1>
        <h1 v-else><?= __c('Nuova segnalazione') ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= Router::url('/'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?= Router::url('/reports/reports/index'); ?>"> <?= __c('Segnalazioni') ?></a></li>
            <li v-if="reportId" class="active"><?= __c('Modifica caso') ?></li>
            <li v-else class="active"><?= __c('Nuova segnalazione') ?></li>
        </ol>
    </section>

    <?= $this->Flash->render() ?>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-report" class="box box-report">
                    <div class="box-header with-border">
                        <i class="fa fa-list-alt"></i>
                        <h3 v-if="reportId" class="box-title">Modifica caso {{reportCode}}</h3>
                        <h3 v-else class="box-title">Nuova segnalazione</h3>
                        <a href="<?= Router::url('/reports/reports/index'); ?>" class="pull-right"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                    </div>
                    <div class="box-body">
                        <div v-if="reportStatus"
                            :class="{
                                'status-indicator-open': reportStatus == 'open', 
                                'status-indicator-closed': reportStatus == 'closed', 
                                'status-indicator-transfer': reportStatus == 'transfer', 
                                'status-indicator-transfer-accepted': reportStatus == 'transfer_accepted'
                            }">Caso in stato {{ reportStatusLabel }}</div>
                        <input hidden id="reportId" v-model="reportId">
                        <div class="row">
                            <div v-show="userRole == 'admin' || (reportStatus == 'transfer' && userRole == 'centro')" class="col-md-6 report-holder">
                                <h4>Intestatario</h4>
                                <div class="col-md-12">
                                    <label class="col-md-2" for="reportHolderCentro">Centro</label>
                                    <div class="col-md-1">
                                        <input :disabled="disabledReportHolder" type="radio" id="reportHolderCentro" value="centro" @click="reportHolder.node = 0" v-model="reportHolder.holder">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="col-md-2" for="reportHolderNodo">Nodo</label>
                                    <div class="col-md-1">
                                        <input :disabled="disabledReportHolder" type="radio" id="reportHolderNodo" value="nodo" v-model="reportHolder.holder">
                                    </div>
                                    <div v-show="reportHolder.holder == 'nodo'" class="col-md-8">
                                        <v-select :disabled="disabledReportHolder" class="search-country-select" id="reportNode" :options="nodes" v-model="reportHolder.node" 
                                            @search="searchNode" placeholder="Cerca un nodo e selezionalo">
                                            <template #no-options="{ search, searching }">
                                                <template v-if="searching">
                                                    Nessun nodo trovato per <em>{{ search }}</em>.
                                                </template>
                                                <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un nodo.</em>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pull-right text-right">
                                <button v-show="reportId && reportStatus == 'transfer' && (userRole == 'admin' || userRole == 'centro')" class="btn btn-primary" title="Conferma trasferimento caso" id="confirmTransferReport" @click="confirmTransferReport()">Conferma trasferimento</button>
                                <button v-show="reportId && reportStatus == 'open'" class="btn btn-info" title="Trasferisci caso" id="transferReport" @click="openModalTransferReport()">Trasferisci</button>
                                <button v-show="reportId && reportStatus == 'open'" class="btn btn-danger" title="Chiudi caso" id="closeReport" @click="openModalCloseReport()">Chiudi</button>
                                <button v-show="reportId && reportStatus == 'closed' && (userRole == 'admin' || userRole == 'centro')" class="btn btn-success" title="Riapri caso" id="reopenReport" @click="openModalReopenReport()">Riapri</button>
                            </div>
                        </div>
                        <hr>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <!--<li><a id="click_tab_1" href="#tab_1" data-toggle="tab">Anagrafica vittima <span v-show="victimChanged" class="to-save">*</span><br /><span v-if="victim.victim_id" class="tab-info">{{victim.date_update}} - {{victim.user_update}}</span></a></li>-->
                                <li :class="{active: !preview}"><a id="click_tab_2" href="#tab_2" data-toggle="tab">Anagrafica segnalante <span v-show="witnessChanged" class="to-save">*</span><br /><span v-if="witness.witness_id" class="tab-info">{{witness.date_update}} - {{witness.user_update}}</span></a></li>
                                <li v-show="idSurvey" :class="{active: preview}"><a id="click_tab_3" href="#tab_3" data-toggle="tab">Scheda caso <span v-show="interviewChanged" class="to-save">*</span><br /><span v-if="interviewData.idInterview" class="tab-info">{{interviewData.date_update}} - {{interviewData.user_update}}</span></a></li>
                                <li><a :disabled="!reportId" id="click_tab_4" href="#tab_4" data-toggle="tab">Allegati</a></li>
                                <li><a :disabled="!reportId" id="click_tab_5" href="#tab_5" data-toggle="tab">Storico caso</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="tab_1">
                                    <form id="formVictim" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="required" for="victimLastname">Cognome</label>
                                                <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="victim_lastname" id="victimLastname" v-model="victim.lastname" @blur="checkAnagrafica($event, 'victim', 'lastname')" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="required" for="victimFirstname">Nome</label>
                                                <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="victim_firstname" id="victimFirstname" v-model="victim.firstname" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="required" for="victimGender">Sesso</label>
                                                <select :disabled="disabledReport" class="form-control select-with-user-text" name="victim_gender_id" id="victimGender" @change="checkUserTextGender('victim')" v-model="victim.gender_id">
                                                    <option value="">-- Seleziona --</option>
                                                    <?php foreach ($genders as $gender) { ?>
                                                        <option value="<?= $gender['id'] ?>" data-user-text="<?= $gender['user_text'] ?>"><?= $gender['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input :disabled="disabledReport" id="victimGenderUserText" name="victim_gender_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.gender_user_text" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="required" for="victimBirthYear">Anno di nascita</label>
                                                <select :disabled="disabledReport" class="form-control" name="victim_birth_year" id="victimBirthYear" v-model="victim.birth_year">
                                                    <option value="">--- Seleziona ---</option>
                                                    <?php foreach (range(date("Y"), 1911) as $year) { ?>
                                                        <option value="<?= $year ?>"><?= $year ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="required" for="victimCountry">Nazione di nascita</label>
                                                <v-select :disabled="disabledReport" class="search-country-select" id="victimCountry" :options="countries" v-model="victim.country" 
                                                    @search="searchCountry" placeholder="Cerca una nazione e selezionala">
                                                    <template #no-options="{ search, searching }">
                                                        <template v-if="searching">
                                                            Nessuna nazione trovata per <em>{{ search }}</em>.
                                                        </template>
                                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una nazione.</em>
                                                    </template>
                                                </v-select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="required" for="victimCitizenship">Cittadinanza</label>
                                                <v-select :disabled="disabledReport" class="search-citizenship-select" id="victimCitizenship" :options="citizenships" v-model="victim.citizenship" 
                                                    @search="searchCitizenship" placeholder="Cerca una cittadinanza e selezionala" @input="checkUserTextCitizenship('victim')">
                                                    <template #no-options="{ search, searching }">
                                                        <template v-if="searching">
                                                            Nessuna cittadinanza trovata per <em>{{ search }}</em>.
                                                        </template>
                                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una cittadinanza.</em>
                                                    </template>
                                                </v-select>
                                                <input :disabled="disabledReport" id="victimCitizenshipUserText" name="victim_citizenship_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.citizenship_user_text" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimInItalyFromYear">In Italia dall'anno</label>
                                                <select :disabled="disabledReport" class="form-control" name="victim_in_italy_from_year" id="victimInItalyFromYear" v-model="victim.in_italy_from_year">
                                                    <option value="">--- Seleziona ---</option>
                                                    <?php foreach (range(date("Y"), 1911) as $year) { ?>
                                                        <option value="<?= $year ?>"><?= $year ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimResidencyPermit">Permesso di soggiorno</label>
                                                <select :disabled="disabledReport" class="form-control select-with-user-text" name="victim_residency_permit_id" id="victimResidencyPermit" @change="checkUserTextResidencyPermit('victim')" v-model="victim.residency_permit_id">
                                                    <option value="">-- Seleziona --</option>
                                                    <?php foreach ($residencyPermits as $permit) { ?>
                                                        <option value="<?= $permit['id'] ?>" data-user-text="<?= $permit['user_text'] ?>"><?= $permit['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input :disabled="disabledReport" id="victimResidencyPermitUserText" name="victim_residency_permit_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.residency_permit_user_text" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label for="victimMaritalStatus">Stato civile</label>
                                                <select :disabled="disabledReport" class="form-control select-with-user-text" name="victim_marital_status_id" id="victimMaritalStatus" @change="checkUserTextMaritalStatus('victim')" v-model="victim.marital_status_id">
                                                    <option value="">-- Seleziona --</option>
                                                    <?php foreach ($maritalStatuses as $status) { ?>
                                                        <option value="<?= $status['id'] ?>" data-user-text="<?= $status['user_text'] ?>"><?= $status['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input :disabled="disabledReport" id="victimMaritalStatusUserText" name="victim_marital_status_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.marital_status_user_text" />
                                            </div>
                                            <div class="col-md-3">
                                                <label>Vive in italia con</label>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.mother" /> Madre                                          
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.father" /> Padre                                           
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.partner" /> Moglie/Marito/Partner                                         
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.son" /> Figlio/i                                           
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.brother" /> Fratello/i                                           
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.other_relatives" /> Altri parenti                                           
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.none" /> Nessuno (vive da sola/o)                                          
                                                </div>
                                                <div class="td-question-check">
                                                    <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="victim.lives_with.other_non_relatives" /> Altri non parenti                                           
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimReligion">Religione</label>
                                                <select :disabled="disabledReport" class="form-control select-with-user-text" name="victim_religion_id" id="victimReligion" @change="checkUserTextReligion('victim')" v-model="victim.religion_id">
                                                    <option value="">-- Seleziona --</option>
                                                    <?php foreach ($religions as $religion) { ?>
                                                        <option value="<?= $religion['id'] ?>" data-user-text="<?= $religion['user_text'] ?>"><?= $religion['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input :disabled="disabledReport" id="victimReligionUserText" name="victim_religion_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.religion_user_text" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimEducationalQualification">Titolo di studio</label>
                                                <select :disabled="disabledReport" class="form-control select-with-user-text" name="victim_educational_qualification_id" id="victimEducationalQualification" @change="checkUserTextEducationalQualification('victim')" v-model="victim.educational_qualification_id">
                                                    <option value="">-- Seleziona --</option>
                                                    <?php foreach ($educationalQualifications as $qualification) { ?>
                                                        <option value="<?= $qualification['id'] ?>" data-user-text="<?= $qualification['user_text'] ?>"><?= $qualification['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input :disabled="disabledReport" id="victimEducationalQualificationUserText" name="victim_educational_qualification_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.educational_qualification_user_text" />
                                            </div>                                        
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label for="victimTypeOccupation">Tipologia occupazione</label>
                                                <select :disabled="disabledReport" class="form-control select-with-user-text" name="victim_type_occupation_id" id="victimTypeOccupation" @change="checkUserTextTypeOccupation('victim')" v-model="victim.type_occupation_id">
                                                    <option value="">-- Seleziona --</option>
                                                    <?php foreach ($occupationTypes as $type) { ?>
                                                        <option value="<?= $type['id'] ?>" data-user-text="<?= $type['user_text'] ?>"><?= $type['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input :disabled="disabledReport" id="victimTypeOccupationUserText" name="victim_type_occupation_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="victim.type_occupation_user_text" />
                                            </div>    
                                            <div class="col-md-3">
                                                <label for="victimTelephone">Tel. fisso</label>
                                                <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="victim_telephone" id="victimTelephone" v-model="victim.telephone" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimMobile">Cellulare</label>
                                                <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="victim_mobile" id="victimMobile" v-model="victim.mobile" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimEmail">E-mail</label>
                                                <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="victim_email" id="victimEmail" v-model="victim.email" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label for="victimRegion">Regione</label>
                                                <v-select :disabled="disabledReport" class="search-region-select" id="victimRegion" :options="regions" v-model="victim.region" 
                                                    @search="searchRegion" @input="victim.province = 0; victim.city = 0" placeholder="Cerca una regione e selezionala">
                                                    <template #no-options="{ search, searching }">
                                                        <template v-if="searching">
                                                            Nessuna regione trovata per <em>{{ search }}</em>.
                                                        </template>
                                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                                    </template>
                                                </v-select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimProvince">Provincia</label>
                                                <v-select :disabled="(disabledReport) || !victim.region" class="search-province-select" id="victimProvince" :options="provinces" v-model="victim.province" 
                                                    @search="(search, loading) => searchProvince(search, loading, 'victim')" @input="victim.city = 0" placeholder="Cerca una provincia e selezionala">
                                                    <template #no-options="{ search, searching }">
                                                        <template v-if="searching">
                                                            Nessuna provincia trovata per <em>{{ search }}</em>.
                                                        </template>
                                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                                    </template>
                                                </v-select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="victimCity">Comune</label>
                                                <v-select :disabled="(disabledReport) || !victim.province" class="search-city-select" id="victimCity" :options="cities" v-model="victim.city" 
                                                    @search="(search, loading) => searchCity(search, loading, 'victim')" placeholder="Cerca un comune e selezionalo">
                                                    <template #no-options="{ search, searching }">
                                                        <template v-if="searching">
                                                            Nessun comune trovato per <em>{{ search }}</em>.
                                                        </template>
                                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un comune.</em>
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button :disabled="disabledReport" type="button" class="btn btn-success save-victim" @click="checkFormVictim()">Salva anagrafica vittima</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab_2" :class="{active: !preview}">
                                    <form id="formWitness" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label class="required" for="witnessType">Tipologia segnalante</label>
                                                <select :disabled="disabledReport" class="form-control" name="witness_type_reporter" id="witnessTypeReporter" v-model="witness.type_reporter" >
                                                    <option value="">-- Seleziona tipologia segnalante --</option>
                                                    <option value="victim">Vittima</option>
                                                    <option value="witness">Testimone</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="required" for="witnessType">Tipologia anagrafica</label>
                                                <select :disabled="disabledReport" class="form-control" name="witness_type" id="witnessType" @change="changeWitnessType" v-model="witness.type" >
                                                    <option value="">-- Seleziona tipologia anagrafica--</option>
                                                    <option value="person">Persona</option>
                                                    <option value="business">Ente/Associazione</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div v-show="witness.type == 'person'">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label :class="{'required': witness.type == 'person'}" for="witnessLastname">Cognome</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="witness_lastname" id="witnessLastname" v-model="witness.lastname" @blur="checkAnagrafica($event, 'witness', 'lastname')" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label :class="{'required': witness.type == 'person'}" for="witnessFirstname">Nome</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="witness_firstname" id="witnessFirstname" v-model="witness.firstname" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label :class="{'required': witness.type == 'person'}" for="witnessGender">Sesso</label>
                                                    <select :disabled="disabledReport" class="form-control select-with-user-text" name="witness_gender_id" id="witnessGender" @change="checkUserTextGender('witness')" v-model="witness.gender_id">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($genders as $gender) { ?>
                                                            <option value="<?= $gender['id'] ?>" data-user-text="<?= $gender['user_text'] ?>"><?= $gender['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input :disabled="disabledReport" id="witnessGenderUserText" name="witness_gender_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.gender_user_text" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="required" for="witnessBirthYear">Anno di nascita</label>
                                                    <select :disabled="disabledReport" class="form-control" name="witness_birth_year" id="witnessBirthYear" v-model="witness.birth_year">
                                                        <option value="">--- Seleziona ---</option>
                                                        <?php foreach (range(date("Y"), 1911) as $year) { ?>
                                                            <option value="<?= $year ?>"><?= $year ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label class="required" for="witnessCountry">Nazione di nascita</label>
                                                    <v-select :disabled="disabledReport" class="search-country-select" id="witnessCountry" :options="countries" v-model="witness.country" 
                                                        @search="searchCountry" placeholder="Cerca una nazione e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna nazione trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una nazione.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="required" for="witnessCitizenship">Cittadinanza</label>
                                                    <v-select :disabled="disabledReport" class="search-citizenship-select" id="witnessCitizenship" :options="citizenships" v-model="witness.citizenship" 
                                                        @search="searchCitizenship" placeholder="Cerca una cittadinanza e selezionala" @input="checkUserTextCitizenship('witness')">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna cittadinanza trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una cittadinanza.</em>
                                                        </template>
                                                    </v-select>
                                                    <input :disabled="disabledReport" id="witnessCitizenshipUserText" name="witness_citizenship_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.citizenship_user_text" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessInItalyFromYear">In Italia dall'anno</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="4" class="form-control number-integer" name="witness_in_italy_from_year" id="witnessInItalyFromYear" v-model="witness.in_italy_from_year" />
                                                </div>   
                                                <div class="col-md-3">
                                                    <label for="witnessResidencyPermit">Permesso di soggiorno</label>
                                                    <select :disabled="disabledReport" class="form-control select-with-user-text" name="witness_residency_permit_id" id="witnessResidencyPermit" @change="checkUserTextResidencyPermit('witness')" v-model="witness.residency_permit_id">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($residencyPermits as $permit) { ?>
                                                            <option value="<?= $permit['id'] ?>" data-user-text="<?= $permit['user_text'] ?>"><?= $permit['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input :disabled="disabledReport" id="witnessResidencyPermitUserText" name="witness_residency_permit_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.residency_permit_user_text" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessMaritalStatus">Stato civile</label>
                                                    <select :disabled="disabledReport" class="form-control select-with-user-text" name="witness_marital_status_id" id="witnessMaritalStatus" @change="checkUserTextMaritalStatus('witness')" v-model="witness.marital_status_id">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($maritalStatuses as $status) { ?>
                                                            <option value="<?= $status['id'] ?>" data-user-text="<?= $status['user_text'] ?>"><?= $status['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input :disabled="disabledReport" id="witnessMaritalStatusUserText" name="witness_marital_status_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.marital_status_user_text" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Vive in italia con</label>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.mother" /> Madre                                          
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.father" /> Padre                                           
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.partner" /> Moglie/Marito/Partner                                         
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.son" /> Figlio/i                                           
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.brother" /> Fratello/i                                           
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.other_relatives" /> Altri parenti                                           
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.none" /> Nessuno (vive da sola/o)                                          
                                                    </div>
                                                    <div class="td-question-check">
                                                        <input :disabled="disabledReport" type="checkbox" class="check-option" v-model="witness.lives_with.other_non_relatives" /> Altri non parenti                                           
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                <label for="witnessReligion">Religione</label>
                                                    <select :disabled="disabledReport" class="form-control select-with-user-text" name="witness_religion_id" id="witnessReligion" @change="checkUserTextReligion('witness')" v-model="witness.religion_id">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($religions as $religion) { ?>
                                                            <option value="<?= $religion['id'] ?>" data-user-text="<?= $religion['user_text'] ?>"><?= $religion['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input :disabled="disabledReport" id="witnessReligionUserText" name="witness_religion_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.religion_user_text" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessEducationalQualification">Titolo di studio</label>
                                                    <select :disabled="disabledReport" class="form-control select-with-user-text" name="witness_educational_qualification_id" id="witnessEducationalQualification" @change="checkUserTextEducationalQualification('witness')" v-model="witness.educational_qualification_id">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($educationalQualifications as $qualification) { ?>
                                                            <option value="<?= $qualification['id'] ?>" data-user-text="<?= $qualification['user_text'] ?>"><?= $qualification['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input :disabled="disabledReport" id="witnessEducationalQualificationUserText" name="witness_educational_qualification_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.educational_qualification_user_text" />
                                                </div>                                        
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessTypeOccupation">Tipologia occupazione</label>
                                                    <select :disabled="disabledReport" class="form-control select-with-user-text" name="witness_type_occupation_id" id="witnessTypeOccupation" @change="checkUserTextTypeOccupation('witness')" v-model="witness.type_occupation_id">
                                                        <option value="">-- Seleziona --</option>
                                                        <?php foreach ($occupationTypes as $type) { ?>
                                                            <option value="<?= $type['id'] ?>" data-user-text="<?= $type['user_text'] ?>"><?= $type['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input :disabled="disabledReport" id="witnessTypeOccupationUserText" name="witness_type_occupation_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="witness.type_occupation_user_text" />
                                                </div>    
                                                <div class="col-md-3">
                                                    <label for="witnessTelephone">Tel. fisso</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_telephone" id="witnessTelephone" v-model="witness.telephone" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessMobile">Cellulare</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_mobile" id="witnessMobile" v-model="witness.mobile" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessEmail">E-mail</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="witness_email" id="witnessEmail" v-model="witness.email" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessRegion">Regione</label>
                                                    <v-select :disabled="disabledReport" class="search-region-select" id="witnessRegion" :options="regions" v-model="witness.region" 
                                                        @search="searchRegion" @input="witness.province = 0; witness.city = 0" placeholder="Cerca una regione e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna regione trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessProvince">Provincia</label>
                                                    <v-select :disabled="(disabledReport) || !witness.region" class="search-province-select" id="witnessProvince" :options="provinces" v-model="witness.province" 
                                                        @search="(search, loading) => searchProvince(search, loading, 'witness_person')" @input="witness.city = 0" placeholder="Cerca una provincia e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna provincia trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessCity">Comune</label>
                                                    <v-select :disabled="(disabledReport) || !witness.province" class="search-city-select" id="witnessCity" :options="cities" v-model="witness.city" 
                                                        @search="(search, loading) => searchCity(search, loading, 'witness_person')" placeholder="Cerca un comune e selezionalo">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessun comune trovato per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un comune.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="witness.type == 'business'">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label :class="{'required': witness.type == 'business'}" for="witnessBusinessName">Ragione sociale</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="witness_business_name" id="witnessBusinessName" v-model="witness.business_name" @blur="checkAnagrafica($event, 'witness', 'business_name')" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessPiva">Partita IVA</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_piva" id="witnessPiva" v-model="witness.piva" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessRegionLegal">Regione della sede legale</label>
                                                    <v-select :disabled="disabledReport" class="search-region-select" id="witnessRegionLegal" :options="regions" v-model="witness.region_legal" 
                                                        @search="searchRegion" @input="witness.province_legal = 0" placeholder="Cerca una regione e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna regione trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessProvinceLegal">Provincia della sede legale</label>
                                                    <v-select :disabled="(disabledReport) || !witness.region_legal" class="search-province-select" id="witnessProvinceLegal" :options="provinces" v-model="witness.province_legal" 
                                                        @search="(search, loading) => searchProvince(search, loading, 'witness_legal')" @input="witness.city_legal = 0" placeholder="Cerca una provincia e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna provincia trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessCityLegal">Comune della sede legale</label>
                                                    <v-select :disabled="(disabledReport) || !witness.province_legal" class="search-city-select" id="witnessCityLegal" :options="cities" v-model="witness.city_legal" 
                                                        @search="(search, loading) => searchCity(search, loading, 'witness_legal')" placeholder="Cerca un comune e selezionalo">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessun comune trovato per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un comune.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="witnessAddressLegal">Indirizzo della sede legale</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="255" class="form-control" name="witness_address_legal" id="witnessAddressLegal" v-model="witness.address_legal" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessRegionOperational">Regione della sede operativa</label>
                                                    <v-select :disabled="disabledReport" class="search-region-select" id="witnessRegionOperational" :options="regions" v-model="witness.region_operational" 
                                                        @search="searchRegion" @input="witness.province_operational = 0" placeholder="Cerca una regione e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna regione trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessProvinceOperational">Provincia della sede operativa</label>
                                                    <v-select :disabled="(disabledReport) || !witness.region_operational" class="search-province-select" id="witnessProvinceOperational" :options="provinces" v-model="witness.province_operational" 
                                                        @search="(search, loading) => searchProvince(search, loading, 'witness_operational')" @input="witness.city_operational = 0" placeholder="Cerca una provincia e selezionala">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessuna provincia trovata per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessCityOperational">Comune della sede operativa</label>
                                                    <v-select :disabled="(disabledReport) || !witness.province_operational" class="search-city-select" id="witnessCityOperational" :options="cities" v-model="witness.city_operational" 
                                                        @search="(search, loading) => searchCity(search, loading, 'witness_operational')" placeholder="Cerca un comune e selezionalo">
                                                        <template #no-options="{ search, searching }">
                                                            <template v-if="searching">
                                                                Nessun comune trovato per <em>{{ search }}</em>.
                                                            </template>
                                                            <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un comune.</em>
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="witnessAddressOperational">Indirizzo della sede operativa</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="255" class="form-control" name="witness_address_operational" id="witnessAddressOperational" v-model="witness.address_operational" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessLegalRepresentative">Legale rappresentante</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="255" class="form-control" name="witness_legal_representative" id="witnessLegalRepresentative" v-model="witness.legal_representative" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessTelephoneLegal">Tel. fisso (legale rappresentante)</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_telephone_legal" id="witnessTelephoneLegal" v-model="witness.telephone_legal" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessMobileLegal">Cellulare (legale rappresentante)</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_mobile_legal" id="witnessMobileLegal" v-model="witness.mobile_legal" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessEmailLegal">E-mail (legale rappresentante)</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="witness_email_legal" id="witnessEmailLegal" v-model="witness.email_legal" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessOperationalContact">Referente operativo</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="255" class="form-control" name="witness_operational_contact" id="witnessOperationalContact" v-model="witness.operational_contact" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="witnessTelephoneOperational">Tel. fisso (referente operativo)</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_telephone_operational" id="witnessTelephoneOperational" v-model="witness.telephone_operational" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessMobileOperational">Cellulare (referente operativo)</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="32" class="form-control" name="witness_mobile_operational" id="witnessMobileOperational" v-model="witness.mobile_operational" />
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="witnessEmailOperational">E-mail (referente operativo)</label>
                                                    <input :disabled="disabledReport" type="text" maxlength="64" class="form-control" name="witness_email_operational" id="witnessEmailOperational" v-model="witness.email_operational" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button :disabled="disabledReport" type="button" class="btn btn-success save-witness" @click="checkFormWitness()">Salva anagrafica segnalante</button>
                                        </div>
                                    </form>
                                </div>
                                <div v-show="idSurvey" class="tab-pane" :class="{active: preview}" id="tab_3">
                                    <div v-show="surveyVersion != interviewData.version" class="alert alert-warning warning-interview-version">
                                        <p>ATTENZIONE: è stato rilevato che esiste una nuova struttura della scheda di compilazione (v{{surveyVersion}}).<br />
                                            Per ovviare a problemi nelle estrazioni di dati, si consiglia di aggiornare la struttura attualmente in uso per questo caso. Al termine dell'aggiornamento la nuova scheda vi sarà mostrata in lettura con le vostre risposte precompilate, si consiglia di verificarne la validità rispetto la nuova struttura e salvare per consolidare il tutto.</p>
                                        <button class="btn btn-default pull-right" @click="updateInterviewStructure()">Aggiorna</button>
                                    </div>
                                    <div v-show="preview" class="alert warning-interview-preview">
                                        <p>ATTENZIONE: la scheda è in modalità preview, è necessario salvarla per rendere effettive le modifiche.</p>
                                    </div>
                                    <!--<h2 class="interview-title">{{interviewData.title}}</h2>-->
                                    <div class="div-survey-intestazione">
                                        <span class="survey-version-number">v{{interviewData.version}}</span>
                                        <!--<h4 v-html="interviewData.subtitle"></h4>-->
                                        <p class="survey-description" v-html="interviewData.description"></p>
                                    </div>

                                    <div id="interview-answers">
                                        <script type="text/x-template" id="item-template">
                                            <div class="box box-item" v-bind:style="{ borderTopColor: item.color }">
                                                <div class="box-header" @click="toggle">
                                                    <span class="open-icon"><i v-if="isOpen" class="fa fa-chevron-down"></i><i v-else class="fa fa-chevron-right"></i></span>
                                                    <h3 class="box-surveys-title" v-html="label+' '+item.title"></h3>
                                                </div>
                                                <div v-show="isOpen" class="box-body">
                                                    <h4 v-html="item.subtitle"></h4>

                                                    <!-- DOMANDE/ELEMENTI -->
                                                    <div class="questions-div">
                                                        <div v-for="question in item.questions">
                                                        
                                                            <!-- TESTO LIBERO -->
                                                            <div v-if="question.type == 'free_text' || question.type == 'standard_text'" v-html="question.value" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible"></div>

                                                            <!-- IMMAGINE -->
                                                            <div v-if="question.type == 'image' && question.path != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <img :src="'<?= Router::url('/surveys/ws/viewImage/'); ?>'+question.path" class="element-image" >
                                                                <p v-html="question.caption"></p>
                                                            </div>

                                                            <!-- RISPOSTA BREVE -->
                                                            <div v-if="question.type == 'short_answer' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <input :disabled="interviewDisabled" type="text" class="form-control" v-model="question.answer" />
                                                            </div>

                                                            <!-- RISPOSTA APERTA -->
                                                            <div v-if="question.type == 'free_answer' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <textarea :disabled="interviewDisabled" class="textarea-answer" v-model="question.answer"></textarea>
                                                            </div>

                                                            <!-- DATA -->
                                                            <div v-if="question.type == 'date' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <datepicker :disabled="interviewDisabled" :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" v-model="question.answer"></datepicker>
                                                            </div>

                                                            <!-- NUMERO -->
                                                            <div v-if="question.type == 'number' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <input :disabled="interviewDisabled" type="number" class="form-control number-integer" v-model="question.answer" />
                                                            </div>

                                                            <!-- RADIO SI/NO -->
                                                            <div v-if="question.type == 'yes_no' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <input :disabled="interviewDisabled" type="radio" value="yes" v-model="question.answer" @change="updateConditionedQuestions(question)" :checked="question.answer == 'yes'" /> Sì 
                                                                <input :disabled="interviewDisabled" type="radio" class="radio-no" value="no" v-model="question.answer" @change="updateConditionedQuestions(question)" :checked="question.answer == 'no'" /> No
                                                            </div>

                                                            <!-- SCELTA SINGOLA -->
                                                            <div v-if="question.type == 'single_choice' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <table v-if="question.view_mode == 'list'" class="table-question-choice">
                                                                    <tr v-for="(option, index) in question.options">
                                                                        <td class="td-question-check">
                                                                            <input :disabled="interviewDisabled" type="radio" :value="index" class="question-check-option" v-model="question.answer.check" @change="emptyExtensionSingle(question.answer.extensions); updateConditionedQuestions(question);" />
                                                                            <label class="question-choice-label" v-html="option.text"></label>
                                                                        </td>
                                                                        <td>
                                                                            <input v-if="option.extended" :disabled="interviewDisabled || !(question.answer.check === index && option.extended)" type="text" class="form-control input-extended-answer" v-model="question.answer.extensions[index]" />
                                                                        </td>
                                                                    </tr>  
                                                                </table>
                                                                <div v-if="question.view_mode == 'select'">
                                                                    <select :disabled="interviewDisabled" class="form-control answer-select" v-model="question.answer.check" @change="emptyExtensionSingle(question.answer.extensions); updateConditionedQuestions(question);">
                                                                        <option value=""></option>
                                                                        <option v-for="(option, index) in question.options" :value="index" v-html="option.text"></option>
                                                                    </select>
                                                                    <input v-if="question.options[question.answer.check] != undefined && question.options[question.answer.check].extended" :disabled="interviewDisabled" type="text" class="form-control answer-select-extended" v-model="question.answer.extensions[index]" />
                                                                </div>
                                                            </div>

                                                            <!-- SCELTA MULTIPLA -->
                                                            <div v-if="question.type == 'multiple_choice' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <table class="table-question-choice" :class="{'multiple-choice-scroll': question.scroll}">
                                                                    <tr v-for="(option, index) in question.options">
                                                                        <td class="td-question-check">
                                                                            <input :disabled="interviewDisabled" type="checkbox" class="question-check-option" v-model="question.answer[index].check" @change="emptyExtensionMultiple(question.answer[index])" />
                                                                            <label v-html="option.text"></label>
                                                                        </td>
                                                                        <td>
                                                                            <input v-if="option.extended" :disabled="interviewDisabled || !question.answer[index].check" type="text" class="form-control input-extended-answer" v-model="question.answer[index].extended"/>    
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-if="question.other">
                                                                        <td class="td-question-check">
                                                                            <input :disabled="interviewDisabled" type="checkbox" class="question-check-option" v-model="question.other_answer_check" @change="emptyAnswer(question)" :checked="question.other_answer_check" />
                                                                            <label>Altro</label>
                                                                        </td>
                                                                        <td>
                                                                            <input :disabled="interviewDisabled || !question.other_answer_check" type="text" class="form-control" v-model="question.other_answer" />
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>

                                                            <!-- TABELLA -->
                                                            <div v-if="question.type == 'table' && question.question != ''" class="question-div"  v-show="typeof question.visible == 'undefined' || question.visible">
                                                                <div class="question-text">   
                                                                    <span v-if="question.required" class="question-required">*&nbsp;</span><p v-html="question.question"></p>
                                                                    &nbsp;
                                                                    <a v-if="question.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
                                                                    <span hidden class="text-question-tooltip" v-html="question.tooltip"></span>
                                                                </div>
                                                                <table class="table table-bordered table-question-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th v-for="header in question.headers" v-html="header"></th>
                                                                            <th v-show="!interviewDisabled" class="question-table-actions-col">
                                                                                <button type="button" class="btn btn-info btn-xs" @click="addRowTable({'headers': question.headers, 'answer': question.answer})" title="Aggiungi riga">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr v-for="(answer, index) in question.answer"> 
                                                                            <td v-for="(a, i) in answer" >
                                                                                <input v-if="!interviewDisabled" type="text" class="form-control" v-model="answer[i]" />
                                                                                <span v-else v-html="a"></span>
                                                                            </td>
                                                                            <td v-show="!interviewDisabled" class="text-center">
                                                                                <button type="button" class="btn btn-danger btn-xs" @click="removeRowTable({'answer': question.answer, 'index': index})" title="Rimuovi riga">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody> 
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-show="isOpen" class="children">
                                                    <tree-item
                                                        v-for="(child, index) in item.items"
                                                        :key="index"
                                                        :index="index"
                                                        :item="child"
                                                        :label="label+'.'+(index+1)"
                                                        :status="status"
                                                        :interview-disabled="interviewDisabled"
                                                    ></tree-item>
                                                </div>
                                            </div>
                                        </script>
                                        <tree-item v-for="(child, index) in interviewData.items" :key="index" :index="index" :item="child" :label="index+1" :status="interviewData.status" :interview-disabled="interviewDisabled"></tree-item>
                                    </div>

                                    <div class="text-right">
                                        <button :disabled="disabledReport" type="button" class="btn btn-success save-interview" @click="saveInterview()">Salva scheda</button>
                                    </div>
                                </div>
                                <div v-show="reportId" class="tab-pane" id="tab_4">
                                    <?= $this->element('Reports.modal_document'); ?>
                                    <div class="box-table-documents box-body">
                                        <button :disabled="disabledReport" type="button" class="btn btn-info btn-xs pull-right" style="margin-left:10px" data-toggle="modal" data-target="#modalDocument">
                                            <i class="fa fa-plus"></i> Nuovo documento
                                        </button>
                                        <div id="pager-documents" class="pager col-sm-6">
                                            <form>
                                                <i class="first glyphicon glyphicon-step-backward"></i>
                                                <i class="prev glyphicon glyphicon-backward"></i>
                                                <span class="pagedisplay"></span>
                                                <i class="next glyphicon glyphicon-forward"></i>
                                                <i class="last glyphicon glyphicon-step-forward"></i>
                                                <select class="pagesize">
                                                    <option selected="selected" value="10">10</option>
                                                    <option value="20">20</option>
                                                    <option value="30">30</option>
                                                </select>
                                            </form>
                                        </div>

                                        <div class="table-content">
                                            <table id="table-documents" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>File</th>
                                                        <th>Titolo</th>
                                                        <th>Descrizione</th>
                                                        <th width="100px" class="filter-false" data-sorter="false"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4" class="text-center">Nessu documento disponibile</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div v-show="reportId" class="tab-pane" id="tab_5">
                                    <div v-for="event in history" 
                                        :class="{
                                            'event-open': event.event == 'open' || event.event == 'reopen', 
                                            'event-close': event.event == 'close', 
                                            'event-transfer': event.event == 'transfer', 
                                            'event-transfer-accepted': event.event == 'transfer_accepted'
                                        }">
                                        {{ event.message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?= $this->element('Reports.modal_anagrafica') ?>
    <?= $this->element('Surveys.modal_tooltip_question'); ?>
    <?= $this->element('Reports.modal_close_report') ?>
    <?= $this->element('Reports.modal_reopen_report') ?>
    <?= $this->element('Reports.modal_transfer_report') ?>

</div>
