<div class="modal fade" id="modalAnagrafica" tabindex="-1" role="dialog" aria-labelledby="Modale anagrafica" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-inline">Anagrafica</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAnagrafica" class="form-horizontal">
                    <div v-show="anagrafica.type_anagrafica == 'victim' || (anagrafica.type_anagrafica == 'witness' && anagrafica.type == 'person')">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label :class="{'required': anagrafica.type == 'person'}" for="anagraficaLastname">Cognome</label>
                                <input type="text" maxlength="64" class="form-control" name="anagrafica_lastname" id="anagraficaLastname" v-model="anagrafica.lastname" @blur="checkAnagrafica($event, 'anagrafica', 'lastname')" />
                            </div>
                            <div class="col-md-3">
                                <label :class="{'required': anagrafica.type == 'person'}" for="anagraficaFirstname">Nome</label>
                                <input type="text" maxlength="64" class="form-control" name="anagrafica_firstname" id="anagraficaFirstname" v-model="anagrafica.firstname" />
                            </div>
                            <div class="col-md-3">
                                <label :class="{'required': anagrafica.type == 'person'}" for="anagraficaGender">Sesso</label>
                                <select class="form-control select-with-user-text" name="anagrafica_gender_id" id="anagraficaGender" @change="checkUserTextGender('anagrafica')" v-model="anagrafica.gender_id">
                                    <option value="">-- Seleziona --</option>
                                    <?php foreach($genders as $gender){ ?>
                                        <option value="<?= $gender['id'] ?>" data-user-text="<?= $gender['user_text'] ?>" ><?= $gender['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <input id="anagraficaGenderUserText" name="anagrafica_gender_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.gender_user_text" />
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaBirthYear">Anno di nascita</label>
                                <input type="text" maxlength="4" class="form-control number-integer" name="anagrafica_birth_year" id="anagraficaBirthYear" v-model="anagrafica.birth_year" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="anagraficaCountry">Nazione di nascita</label>
                                <v-select class="search-country-select" id="anagraficaCountry" :options="countries" v-model="anagrafica.country" 
                                    @search="searchCountry" placeholder="Seleziona una nazione">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna nazione trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una nazione.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaCitizenship">Cittadinanza</label>
                                <v-select class="search-citizenship-select" id="anagraficaCitizenship" :options="citizenships" v-model="anagrafica.citizenship" 
                                    @search="searchCitizenship" placeholder="Cerca una cittadinanza e selezionala" @input="checkUserTextCitizenship('anagrafica')">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna cittadinanza trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una cittadinanza.</em>
                                    </template>
                                </v-select>
                                <input id="anagraficaCitizenshipUserText" name="anagrafica_citizenship_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.citizenship_user_text" />
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaInItalyFromYear">In Italia dall'anno</label>
                                <input type="text" maxlength="4" class="form-control number-integer" name="anagrafica_in_italy_from_year" id="anagraficaInItalyFromYear" v-model="anagrafica.in_italy_from_year" />
                            </div>    
                            <div class="col-md-3">
                                <label for="anagraficaResidencyPermit">Permesso di soggiorno</label>
                                <select class="form-control select-with-user-text" name="anagrafica_residency_permit_id" id="anagraficaResidencyPermit" @change="checkUserTextResidencyPermit('anagrafica')" v-model="anagrafica.residency_permit_id">
                                    <option value="">-- Seleziona --</option>
                                    <?php foreach($residencyPermits as $permit){ ?>
                                        <option value="<?= $permit['id'] ?>" data-user-text="<?= $permit['user_text'] ?>" ><?= $permit['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <input id="anagraficaResidencyPermitUserText" name="anagrafica_residency_permit_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.residency_permit_user_text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="anagraficaMaritalStatus">Stato civile</label>
                                <select class="form-control select-with-user-text" name="anagrafica_marital_status_id" id="anagraficaMaritalStatus" @change="checkUserTextMaritalStatus('anagrafica')" v-model="anagrafica.marital_status_id">
                                    <option value="">-- Seleziona --</option>
                                    <?php foreach($maritalStatuses as $status){ ?>
                                        <option value="<?= $status['id'] ?>" data-user-text="<?= $status['user_text'] ?>" ><?= $status['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <input id="anagraficaMaritalStatusUserText" name="anagrafica_marital_status_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.marital_status_user_text" />
                            </div>
                            <div class="col-md-3">
                                <label>Vivie in italia con</label>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.mother" /> Madre                                          
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.father" /> Padre                                           
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.partner" /> Moglie/Marito/Partner                                         
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.son" /> Figlio/Figli                                           
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.brother" /> Fratello/i                                           
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.other_relatives" /> Altri parenti                                           
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.none" /> Nessuno (vive da sola/o)                                          
                                </div>
                                <div class="td-question-check">
                                    <input type="checkbox" class="check-option" v-model="anagrafica.lives_with.other_non_relatives" /> Altri non parenti                                           
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaReligion">Religione</label>
                                <select class="form-control select-with-user-text" name="anagrafica_religion_id" id="anagraficaReligion" @change="checkUserTextReligion('anagrafica')" v-model="anagrafica.religion_id">
                                    <option value="">-- Seleziona --</option>
                                    <?php foreach($religions as $religion){ ?>
                                        <option value="<?= $religion['id'] ?>" data-user-text="<?= $religion['user_text'] ?>" ><?= $religion['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <input id="anagraficaReligionUserText" name="anagrafica_religion_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.religion_user_text" />
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaEducationalQualification">Titolo di studio</label>
                                <select class="form-control select-with-user-text" name="anagrafica_educational_qualification_id" id="anagraficaEducationalQualification" @change="checkUserTextEducationalQualification('anagrafica')" v-model="anagrafica.educational_qualification_id">
                                    <option value="">-- Seleziona --</option>
                                    <?php foreach($educationalQualifications as $qualification){ ?>
                                        <option value="<?= $qualification['id'] ?>" data-user-text="<?= $qualification['user_text'] ?>" ><?= $qualification['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <input id="anagraficaEducationalQualificationUserText" name="anagrafica_educational_qualification_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.educational_qualification_user_text" />
                            </div>                                        
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label for="anagraficaTypeOccupation">Tipologia occupazione</label>
                                <select class="form-control select-with-user-text" name="anagrafica_type_occupation_id" id="anagraficaTypeOccupation" @change="checkUserTextTypeOccupation('anagrafica')" v-model="anagrafica.type_occupation_id">
                                    <option value="">-- Seleziona --</option>
                                    <?php foreach($occupationTypes as $type){ ?>
                                        <option value="<?= $type['id'] ?>" data-user-text="<?= $type['user_text'] ?>" ><?= $type['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <input id="anagraficaTypeOccupationUserText" name="anagrafica_type_occupation_user_text" type="text" maxlength="64" class="form-control select-user-text" v-model="anagrafica.type_occupation_user_text" />
                            </div>    
                            <div class="col-md-3">
                                <label for="anagraficaTelephone">Tel. fisso</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_telephone" id="anagraficaTelephone" v-model="anagrafica.telephone" />
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaMobile">Cellulare</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_mobile" id="anagraficaMobile" v-model="anagrafica.mobile" />
                            </div>
                            <div class="col-md-3">
                                <label for="anagraficaEmail">E-mail</label>
                                <input type="text" maxlength="64" class="form-control" name="anagrafica_email" id="anagraficaEmail" v-model="anagrafica.email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="anagraficaRegion">Regione</label>
                                <v-select class="search-region-select" id="anagraficaRegion" :options="regions" v-model="anagrafica.region" 
                                    @search="searchRegion" @input="anagrafica.province = 0; anagrafica.city = 0" placeholder="Seleziona una regione">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna regione trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaProvince">Provincia</label>
                                <v-select :disabled="!anagrafica.region" class="search-province-select" id="anagraficaProvince" :options="provinces" v-model="anagrafica.province" 
                                    @search="(search, loading) => searchProvince(search, loading, 'anagrafica_person')" @input="anagrafica.city = 0" placeholder="Seleziona una provincia">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna provincia trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaCity">Comune</label>
                                <v-select :disabled="!anagrafica.province" class="search-city-select" id="anagraficaCity" :options="cities" v-model="anagrafica.city" 
                                    @search="(search, loading) => searchCity(search, loading, 'anagrafica_person')" placeholder="Seleziona una regione">
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
                    <div v-show="anagrafica.type_anagrafica == 'witness' && anagrafica.type == 'business'">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label :class="{'required': anagrafica.type == 'business'}" for="anagraficaBusinessName">Ragione sociale</label>
                                <input type="text" maxlength="64" class="form-control" name="anagrafica_business_name" id="anagraficaBusinessName" v-model="anagrafica.business_name" @blur="checkAnagrafica($event, 'anagrafica', 'business_name')" />
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaPiva">Partita IVA</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_piva" id="anagraficaPiva" v-model="anagrafica.piva" />
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="anagraficaRegionLegal">Regione della sede legale</label>
                                <v-select class="search-region-select" id="anagraficaRegionLegal" :options="regions" v-model="anagrafica.region_legal" 
                                    @search="searchRegion" @input="anagrafica.province_legal = 0" placeholder="Cerca una regione e selezionala">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna regione trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaProvinceLegal">Provincia della sede legale</label>
                                <v-select :disabled="!anagrafica.region_legal" class="search-province-select" id="anagraficaProvinceLegal" :options="provinces" v-model="anagrafica.province_legal" 
                                    @search="(search, loading) => searchProvince(search, loading, 'anagrafica_witness_legal')" @input="anagrafica.city_legal = 0" placeholder="Cerca una provincia e selezionala">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna provincia trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaCityLegal">Comune della sede legale</label>
                                <v-select :disabled="!anagrafica.province_legal" class="search-city-select" id="anagraficaCityLegal" :options="cities" v-model="anagrafica.city_legal" 
                                    @search="(search, loading) => searchCity(search, loading, 'anagrafica_witness_legal')" placeholder="Cerca un comune e selezionalo">
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
                            <div class="col-md-12">
                                <label for="anagraficaAddressLegal">Indirizzo della sede legale</label>
                                <input type="text" maxlength="255" class="form-control" name="anagrafica_address_legal" id="anagraficaAddressLegal" v-model="anagrafica.address_legal" />
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="anagraficaRegionOperational">Regione della sede operativa</label>
                                <v-select class="search-region-select" id="anagraficaRegionOperational" :options="regions" v-model="anagrafica.region_operational" 
                                    @search="searchRegion" @input="anagrafica.province_operational = 0" placeholder="Cerca una regione e selezionala">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna regione trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una regione.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaProvinceOperational">Provincia della sede operativa</label>
                                <v-select :disabled="!anagrafica.region_operational" class="search-province-select" id="anagraficaProvinceOperational" :options="provinces" v-model="anagrafica.province_operational" 
                                    @search="(search, loading) => searchProvince(search, loading, 'anagrafica_witness_operational')" @input="anagrafica.city_operational = 0" placeholder="Cerca una provincia e selezionala">
                                    <template #no-options="{ search, searching }">
                                        <template v-if="searching">
                                            Nessuna provincia trovata per <em>{{ search }}</em>.
                                        </template>
                                        <em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una provincia.</em>
                                    </template>
                                </v-select>
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaCityOperational">Comune della sede operativa</label>
                                <v-select :disabled="!anagrafica.province_operational" class="search-city-select" id="anagraficaCityOperational" :options="cities" v-model="anagrafica.city_operational" 
                                    @search="(search, loading) => searchCity(search, loading, 'anagrafica_witness_operational')" placeholder="Cerca un comune e selezionalo">
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
                            <div class="col-md-12">
                                <label for="anagraficaAddressOperational">Indirizzo della sede operativa</label>
                                <input type="text" maxlength="255" class="form-control" name="anagrafica_address_operational" id="anagraficaAddressOperational" v-model="anagrafica.address_operational" />
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="anagraficaLegalRepresentative">Legale rappresentante</label>
                                <input type="text" maxlength="255" class="form-control" name="anagrafica_legal_representative" id="anagraficaLegalRepresentative" v-model="anagrafica.legal_representative" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="anagraficaTelephoneLegal">Tel. fisso (legale rappresentante)</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_telephone_legal" id="anagraficaTelephoneLegal" v-model="anagrafica.telephone_legal" />
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaMobileLegal">Cellulare (legale rappresentante)</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_mobile_legal" id="anagraficaMobileLegal" v-model="anagrafica.mobile_legal" />
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaEmailLegal">E-mail (legale rappresentante)</label>
                                <input type="text" maxlength="64" class="form-control" name="anagrafica_email_legal" id="anagraficaEmailLegal" v-model="anagrafica.email_legal" />
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="anagraficaOperationalContact">Referente operativo</label>
                                <input type="text" maxlength="255" class="form-control" name="anagrafica_operational_contact" id="anagraficaOperationalContact" v-model="anagrafica.operational_contact" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label for="anagraficaTelephoneOperational">Tel. fisso (referente operativo)</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_telephone_operational" id="anagraficaTelephoneOperational" v-model="anagrafica.telephone_operational" />
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaMobileOperational">Cellulare (referente operativo)</label>
                                <input type="text" maxlength="32" class="form-control" name="anagrafica_mobile_operational" id="anagraficaMobileOperational" v-model="anagrafica.mobile_operational" />
                            </div>
                            <div class="col-md-4">
                                <label for="anagraficaEmailOperational">E-mail (referente operativo)</label>
                                <input type="text" maxlength="64" class="form-control" name="anagrafica_email_operational" id="anagraficaEmailOperational" v-model="anagrafica.email_operational" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="loadAnagrafica" @click="loadAnagrafica(anagrafica.type_anagrafica)">Carica dati</button>
            </div>
        </div>
    </div>
</div>