Vue.component('v-select', VueSelect.VueSelect);
Vue.use(VueMaterial.default);

var app = new Vue({
    el: '#app-guest',
    data: {
        role: role,
        ente_type: ente_type,
        guestData: {
            id: {
                hasError: false,
                value: '',
                required: false
            },
            sede_id: {
                hasError: false,
                value: '',
                required: true
            },
            check_in_date: {
                hasError: false,
                value: new Date(),
                required: true
            },
            cui: {
                hasError: false,
                value: '',
                required: false
            },
            vestanet_id: {
                hasError: false,
                value: '',
                required: false
            },
            name: {
                hasError: false,
                value: '',
                required: true
            },
            surname: {
                hasError: false,
                value: '',
                required: true
            },
            minor: {
                hasError: false,
                value: '',
                required: false
            },
            minor_note: {
                hasError: false,
                value: '',
                required: false
            },
            minor_family: {
                hasError: false,
                value: '',
                required: false
            },
            family_guest: {
                hasError: false,
                value: '',
                required: false
            },
            minor_alone: {
                hasError: false,
                value: '',
                required: false
            },
            birthdate: {
                hasError: false,
                value: '',
                required: true
            },
            country_birth: {
                hasError: false,
                value: '',
                required: true
            },
            sex: {
                hasError: false,
                value: '',
                required: true
            },
            educational_qualification: {
                hasError: false,
                value: '',
                required: false
            },
            educational_qualification_child: {
                hasError: false,
                value: '',
                required: false
            },
            electronic_residence_permit: {
                hasError: false,
                value: false,
                required: false
            },
            draft: {
                hasError: false,
                value: true,
                required: false
            },
            draft_expiration: {
                hasError: false,
                value: new Date((new Date()).getTime()+(60*24*60*60*1000)),
                required: false
            },
            suspended: {
                hasError: false,
                value: '',
                required: false
            },
        },
        guestPresenza: null,
        guestStatus: '',
        guestExitRequestStatus: null,
        countries: [],
        familyGuests: [],
        educationalQualifications: [],
        educationalQualificationChildren: [],
        familyId : '',
        guestFamily: [],
        guestHistory: [],
        requestExitTypes: [],
        requestExitProcedureData: {
            exit_type_id: {
                required: true,
                hasError: false,
                value: ''
            },
            file:  {
                required: false,
                hasError: false,
                value: ''
            },
            note:  {
                required: false,
                hasError: false,
                value: ''
            }
        },
        requestExitData: {
            type: {
                id: '',
                name: '',
                modello_decreto: '',
                modello_notifica: ''
            },
            file: '',
            note: '',
        },
        authorizeRequestExitProcedureData: {
            file:  {
                required: false,
                hasError: false,
                value: ''
            },
            note:  {
                required: false,
                hasError: false,
                value: ''
            }
        },
        authorizeRequestExitData: {
            type: {
                id: '',
                name: ''
            },
            file: '',
            note: '',
        },
        exitTypes: [],
        exitProcedureData: {
            exit_type_id: {
                required: true,
                hasError: false,
                value: ''
            },
            file:  {
                required: false,
                hasError: false,
                value: ''
            },
            note:  {
                required: false,
                hasError: false,
                value: ''
            }
        },
        confirmExitProcedureData: {
            check_out_date: {
                required: true,
                hasError: false,
                value: ''
            }
        },
        exitData: {
            type: '',
            date: '',
            file: '',
            note: '',
        },
        transferAziende: [],
        transferSedi: [],
        transferProcedureData: {
            azienda: {
                required: true,
                hasError: false,
                value: ''
            },
            sede: {
                required: true,
                hasError: false,
                value: ''
            },
            check_out_date: {
                required: true,
                hasError: false,
                value: new Date()
            },
            note: {
                required: false,
                hasError: false,
                value: ''
            }
        },
        transferData: {
            destination: '',
            destination_id: '',
            provenance: '',
            date: '',
            note: '',
            cloned_guest: ''
        },
        acceptTransferProcedureData: {
            check_in_date: {
                required: true,
                hasError: false,
                value: ''
            }
        },
        readmissionAziende: [],
        readmissionSedi: [],
        readmissionProcedureData: {
            azienda: {
                required: true,
                hasError: false,
                value: ''
            },
            sede: {
                required: true,
                hasError: false,
                value: ''
            },
            note: {
                required: false,
                hasError: false,
                value: ''
            }
        },
        datepickerItalian: vdp_translation_it.js,
        guestsForSearch: [],
        searchedGuest: null,
        searchGuestSelectVisible: false,
        loadedData: '',
        loadedFamily: '',
        decreti: false,
        notifiche: false
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    computed: {
        countFamilyAdults() {
            var count = this.guestData.minor.value ? 0 : 1;

            this.guestFamily.forEach((guest) => {
                if (guest.minor == 0) {
                    count++;
                }
            });

            return count;
        },

        removeFamilyButtonMessage() {
            if (this.guestStatus == 1 && !this.familyId) {
                return "Rimozione ospite dal nucleo familiare disabilitata: l'ospite non appartiene a nessun nucleo familiare";
            }
            
            if (this.guestStatus == 1 && this.guestData.minor.value) {
                return "Rimozione ospite dal nucleo familiare disabilitata: l'ospite è un minore";
            }

            if (this.guestStatus == 1 && this.countFamilyAdults == 1) {
                return "Rimozione ospite dal nucleo familiare disabilitata: unico adulto presente nel nucleo familiare";
            }
            
            return "Rimuovi ospite dal nucleo familiare";
        },
        decreti_url() {
            if (this.decreti) {
                return pathServer + 'surveys/surveys/answers?interview=' + this.decreti.interview_id;
            }
        }, 
        notifiche_url() {
            if (this.decreti) {
                return pathServer + 'surveys/surveys/answers?interview=' + this.notifiche.interview_id;
            }
        }
    },

    watch: {
        
    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.guestData.sede_id.value = url.searchParams.get("sede");
        this.guestData.id.value = url.searchParams.get("guest");

        if(this.guestData.id.value){
            this.loadGuest(this.guestData.id.value);
        } else {
            this.getExitTypes();
        }

        this.getEducationalQualifications();
        this.getRequestExitTypes();

        let modalGuestRequestExit = this.$refs.modalGuestRequestExit; 
        $(modalGuestRequestExit).on('hidden.bs.modal', () => {
            this.clearRequestExitProcedureData();
        });

        let modalAuthorizeGuestRequestExit = this.$refs.modalAuthorizeGuestRequestExit; 
        $(modalAuthorizeGuestRequestExit).on('hidden.bs.modal', () => {
            this.clearAuthorizeRequestExitProcedureData();
        });

        let modalGuestExit = this.$refs.modalGuestExit; 
        $(modalGuestExit).on('hidden.bs.modal', () => {
            this.clearExitProcedureData();
        });

        let modalConfirmGuestExit = this.$refs.modalConfirmGuestExit; 
        $(modalConfirmGuestExit).on('hidden.bs.modal', () => {
            this.clearConfirmExitProcedureData();
        });

        let modalGuestTransfer = this.$refs.modalGuestTransfer; 
        $(modalGuestTransfer).on('hidden.bs.modal', () => {
            this.clearTransferProcedureData();
        });

        let modalConfirmGuestTransfer = this.$refs.modalConfirmGuestTransfer; 
        $(modalConfirmGuestTransfer).on('hidden.bs.modal', () => {
            this.clearAcceptTransferProcedureData();
        });

    },
       
    methods: {

        loadGuest: function(id){
            axios.get(pathServer + 'aziende/ws/getGuest/' + id)
                .then(res => {  
                    if (res.data.response == 'OK') { 
                        this.guestData.sede_id.value = res.data.data.sede_id;
                        this.guestData.check_in_date.value = res.data.data.check_in_date;
                        this.guestData.cui.value = res.data.data.cui;
                        this.guestData.vestanet_id.value = res.data.data.vestanet_id;
                        this.guestData.name.value = res.data.data.name;
                        this.guestData.surname.value = res.data.data.surname;
                        this.guestData.minor.value = res.data.data.minor;
                        this.guestData.minor_family.value = res.data.data.minor_family;
                        if (res.data.data.family_guest) {
                            this.guestData.family_guest.value = {
                                'id': res.data.data.family_guest.id,
                                'label': res.data.data.family_guest.cui + ' - ' + res.data.data.family_guest.name + ' ' + res.data.data.family_guest.surname
                            };
                        }
                        this.guestData.minor_alone.value = res.data.data.minor_alone;
                        this.guestData.minor_note.value = res.data.data.minor_note;
                        this.guestData.birthdate.value = res.data.data.birthdate;
                        if (res.data.data.country) {
                            this.guestData.country_birth.value = {
                                'id': res.data.data.country.c_luo,
                                'label': res.data.data.country.des_luo
                            };
                        }
                        this.guestData.sex.value = res.data.data.sex;
                        this.guestData.educational_qualification.value = res.data.data.educational_qualification;
                        if (res.data.data.educational_qualification_child) {
                            this.guestData.educational_qualification_child.value = res.data.data.educational_qualification_child;
                            this.getEducationalQualifications(res.data.data.educational_qualification.id);
                        }
                        this.guestData.electronic_residence_permit.value = res.data.data.electronic_residence_permit;
                        this.guestData.draft.value = res.data.data.draft;
                        this.guestData.draft_expiration.value = res.data.data.draft_expiration;
                        this.guestData.suspended.value = res.data.data.suspended;

                        this.loadedData = JSON.stringify(this.guestData);

                        this.guestPresenza = res.data.data.presenza;
                        this.guestStatus = res.data.data.status_id;
                        this.guestExitRequestStatus = res.data.data.exit_request_status_id;

                        if (this.guestStatus == 1 && this.guestExitRequestStatus == 1) {
                            this.requestExitData.type.id = res.data.data.history_exit_type_id;
                            this.requestExitData.type.name = res.data.data.history_exit_type_name;
                            this.requestExitData.file = res.data.data.history_file;
                            this.requestExitData.note = res.data.data.history_note;

                            this.requestExitData.type.modello_decreto = res.data.data.history_exit_type_modello_decreto;
                            this.requestExitData.type.modello_notifica = res.data.data.history_exit_type_modello_notifica;

                            this.decreti = res.data.data.decreti;
                            this.notifiche = res.data.data.notifiche;
                        }
                        if (this.guestStatus == 1 && this.guestExitRequestStatus == 2) {
                            this.authorizeRequestExitData.type.id = res.data.data.history_exit_type_id;
                            this.authorizeRequestExitData.type.name = res.data.data.history_exit_type_name;
                            this.authorizeRequestExitData.file = res.data.data.history_file;
                            this.authorizeRequestExitData.note = res.data.data.history_note;
                        }
                        if (this.guestStatus == 2 || this.guestStatus == 3) {
                            this.exitData.type = res.data.data.history_exit_type_name;
                            this.exitData.date = res.data.data.check_out_date;
                            this.exitData.file = res.data.data.history_file;
                            this.exitData.note = res.data.data.history_note;
                        }
                        if (this.guestStatus == 4 || this.guestStatus == 5 || this.guestStatus == 6) {
                            this.transferData.destination = res.data.data.history_destination;
                            this.transferData.destination_id = res.data.data.history_destination_id;
                            this.transferData.provenance = res.data.data.history_provenance;
                            this.transferData.date = res.data.data.history_date;
                            this.transferData.note = res.data.data.history_note;
                            this.transferData.cloned_guest = res.data.data.history_cloned_guest;
                        }

                        this.familyId = res.data.data.family_id;
                        this.guestFamily = res.data.data.family; 

                        this.existsInFuture = res.data.data.exists_in_future; 

                        this.loadedFamily = JSON.stringify(this.guestFamily);

                        this.getExitTypes();

                        this.loadGuestHistory();

                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        checkFormGuest: function(exit){

            var errors = false;
            var msg = '';
            var errorRequired = false;

            Object.keys(this.guestData).forEach((prop) => {
                if (this.guestData[prop].required) {
                    if(this.guestData[prop].value == "" || this.guestData[prop].value == null){
                        errors = true;
                        if (!errorRequired) {
                            errorRequired = true;
                            msg += 'Si prega di compilare tutti i campi obbligatori.\n'
                        }
                        this.guestData[prop].hasError = true;
                    }else{
                        this.guestData[prop].hasError = false;
                    }
                }

                if(prop == 'cui'){
                    if(this.guestData[prop].value != "" && this.guestData[prop].value.length != 7){
                        errors = true;
                        msg += 'CUI non valido.\n';
                        this.guestData[prop].hasError = true;
                    }else{
                        this.guestData[prop].hasError = false;
                    }
                }
                if(prop == 'vestanet_id'){
                    if(this.guestData[prop].value != "" && (this.guestData[prop].value.length < 9 || this.guestData[prop].value.length > 10)){
                        errors = true;
                        msg += 'ID Vestanet non valida.\n';
                        this.guestData[prop].hasError = true;
                    }else{
                        this.guestData[prop].hasError = false;
                    }
                }
                if(prop == 'educational_qualification'){
                    if(this.guestData[prop].value && this.guestData[prop].value.have_children && !this.guestData.educational_qualification_child.value){
                        errors = true;
                        msg += 'Per il titolo di studio selezionato è necessario selezionare anche il dettaglio.\n';
                    }
                }
            });
            var familyAdult = false;
            this.guestFamily.forEach((guest) => {
                if (guest.minor == 0) {
                    familyAdult = true;
                }
            });
            if(this.guestData.minor.value && !this.guestData.minor_alone.value && !familyAdult){
                errors = true;
                msg += 'L\'ospite è un minore e non si dichiara solo pertanto è necessario associarlo ad un nucleo familiare con adulto.\n';
            }

            if(errors){
                alert(msg);
                return false;
            }else{
                this.saveGuest(exit);
            }

        },

        saveGuest: function(exit){

            let params = new URLSearchParams();

            Object.keys(this.guestData).forEach((prop) => {
                if (prop != 'educational_qualification_child') {
                    if (prop == 'country_birth' || prop == 'family_guest') {
                        if (this.guestData[prop] == '' || this.guestData[prop] == null) {
                            params.append(prop, '');
                        } else {
                            params.append(prop, this.guestData[prop].value.id);
                        }
                    } else if (prop == 'educational_qualification') {
                        if (this.guestData.educational_qualification_child.value) {
                            params.append('educational_qualification_id', this.guestData.educational_qualification_child.value.id);
                        } else if (this.guestData[prop].value) {
                            params.append('educational_qualification_id', this.guestData[prop].value.id);
                        }
                    } else {
                        params.append(prop, this.guestData[prop].value);
                    }
                }
            });

            params.append('ente_type', this.ente_type);
            params.append('family', JSON.stringify(this.guestFamily));

            axios.post(pathServer + 'aziende/ws/saveGuest', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        this.loadedData = JSON.stringify(this.guestData);
                        if(exit){
                            window.location = pathServer + 'aziende/guests/index/'+this.guestData.sede_id.value;
                        }else{ 
                            alert(res.data.msg);
                            window.location = pathServer + 'aziende/guests/guest?sede='+this.guestData.sede_id.value+'&guest='+res.data.data;
                        }
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        },

        deleteGuest: function(index){
            if(confirm("Attenzione! Si è sicuri di voler eliminare l'ospite?")){
                if(this.guestFamily[index].id == ''){
                    this.guestFamily.splice(index, 1);
                }else{
                    let params = new URLSearchParams();
                    params.append('id', this.guestFamily[index].id);

                    axios.post(pathServer + 'aziende/ws/deleteGuest', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            alert(res.data.msg);
                            this.guestFamily.splice(index, 1);
                            this.loadedFamily = JSON.stringify(this.guestFamily);
                        } else {
                            alert(res.data.msg);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }
            }
        },

        searchCountry: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'aziende/ws/searchCountry/'+search)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.countries = res.data.data;
                        loading(false);
                    } else {
                        this.countries = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.countries = [];
            }
        },

        removeGuestFromFamily: function(index = ""){ 
            if(confirm("Attenzione! Si è sicuri di voler rimuovere l'ospite dalla famiglia?")){ 
                if(index === ""){
                    let params = new URLSearchParams();
                    params.append('id', this.guestData.id.value);

                    axios.post(pathServer + 'aziende/ws/removeGuestFromFamily', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            alert(res.data.msg);
                            this.familyId = '';
                            this.guestFamily = [];
                            this.loadedFamily = JSON.stringify(this.guestFamily);
                        } else {
                            alert(res.data.msg);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }else{
                    let params = new URLSearchParams();
                    params.append('id', this.guestFamily[index].id);

                    axios.post(pathServer + 'aziende/ws/removeGuestFromFamily', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            alert(res.data.msg);
                            this.guestFamily.splice(index, 1);
                            this.loadedFamily = JSON.stringify(this.guestFamily);
                        } else {
                            alert(res.data.msg);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
                }
            }
        },

        searchGuests: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'aziende/ws/searchGuestsBySede/'+this.guestData.sede_id.value+'/'+search+'/'+this.guestData.id.value)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.guestsForSearch = res.data.data; 
                        loading(false);
                    } else {
                        this.guestsForSearch = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.guestsForSearch = [];
            }
        },

        addSearchedGuest: function(value){ 
            if(value !== null){
                var familyGuests = this.guestFamily;
                var enabled = false
                var wrongFamily = false;
                for( var i = 0; i < familyGuests.length; i++) {
                    if(familyGuests[i].id == value.id){
                        enabled = true;
                    } 
                    if(value.family_id != null && familyGuests[i].family_id != null && familyGuests[i].family_id != value.family_id){
                        wrongFamily = true;
                    }
                };

                if(enabled){
                    alert("L'ospite selezionato è gia stato associato alla famiglia.");
                    value = null;
                }else if(wrongFamily){
                    alert("L'ospite selezionato appartiene ad una famiglia diversa da quella degli altri altri ospiti associati.");
                    value = null;
                }else{
                    this.guestFamily.push(value);
                    this.loadedFamily = JSON.stringify(this.guestFamily);
                }
            }

            this.searchedGuest = null;
        },

        showHideSearchGuestSelect: function(){ 
            if(this.searchGuestSelectVisible){
                this.searchedGuest = null;
                this.guestsForSearch = [];
                this.searchGuestSelectVisible = false;
                $('#searchGuestSelect').hide('slide', {direction: 'right'});
            }else{
                this.searchGuestSelectVisible = true;
                $('#searchGuestSelect').show('slide', {direction: 'right'});
                setTimeout(function(){ $('#searchGuestSelect input').focus(); }, 400);
            }
        },

        setDraft: function() {
            if (this.guestData.cui.value != '' || this.guestData.vestanet_id.value != '') {
                this.guestData.draft.value = false;
                this.guestData.draft_expiration.value = '';
            } else {
                this.guestData.draft.value = true;
                this.guestData.draft_expiration.value = new Date((new Date()).getTime()+(60*24*60*60*1000));
            }
        },

        resetMinor: function() {
            this.guestData.minor_note.value = '';
            this.guestData.minor_family.value = '';
            this.guestData.family_guest.value = '';
            this.guestData.family_guest.required = false;
            this.guestData.minor_alone.value = '';
            this.familyGuests = [];
        },

        changeMinorFamily: function(family) {
            this.guestData.family_guest.value = '';
            this.familyGuests = [];
            if (family) {
                this.guestData.family_guest.required = true;
                this.guestData.minor_alone.value = '';
            } else {
                this.guestData.family_guest.required = false;
                this.guestData.minor_family.value = '';
            }
        },

        changeMinorAlone: function() {
            if (!this.guestData.minor_alone.value) {
                if (confirm('Attenzione! Confermi che il minore è solo? Verrà rimosso dal nucleo familiare.')) {
                    this.familyGuests = [];
                } else {
                    this.guestData.minor_alone.value = '';
                }
            }
        },

        loadGuestHistory: function() {
            axios.get(pathServer + 'aziende/ws/loadGuestHistory/' + this.guestData.id.value)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.guestHistory = res.data.data; 
                } else {
                    alert(res.data.msg);
                }
            }).catch(error => {
                console.log(error);
            });
        },

        getExitTypes: function() {
            var all = this.guestExitRequestStatus == 2 ? 1 : 0;
            axios.get(pathServer + 'aziende/ws/getExitTypes/'+this.ente_type+'/'+all)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.exitTypes = res.data.data;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },
        
        getRequestExitTypes: function() {
            axios.get(pathServer + 'aziende/ws/getRequestExitTypes/'+this.ente_type)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.requestExitTypes = res.data.data;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        openRequestExitModal: function() {
            let modalGuestRequestExit = this.$refs.modalGuestRequestExit;
            $(modalGuestRequestExit).modal({
                backdrop: false,
                keyboard: false
            });
            $(modalGuestRequestExit).modal('show');
        },

        updateRequestExitRequirements: function() {
            if (this.requestExitProcedureData.exit_type_id.value && this.requestExitTypes[this.requestExitProcedureData.exit_type_id.value].required_request_file) {
                this.requestExitProcedureData.file.required = true;
            } else {
                this.requestExitProcedureData.file.hasError = false;
                this.requestExitProcedureData.file.required = false;
            }
            if (this.requestExitProcedureData.exit_type_id.value && this.requestExitTypes[this.requestExitProcedureData.exit_type_id.value].required_request_note) {
                this.requestExitProcedureData.note.required = true;
            } else {
                this.requestExitProcedureData.note.hasError = false;
                this.requestExitProcedureData.note.required = false;
            }
        },

        requestExitProcedure: function() { 
            var error = false;

            Object.keys(this.requestExitProcedureData).forEach((prop) => {
                if (this.requestExitProcedureData[prop].required && (this.requestExitProcedureData[prop].value == "" || this.requestExitProcedureData[prop].value == null)) {
                    error = true;
                    this.requestExitProcedureData[prop].hasError = true;
                } else {
                    this.requestExitProcedureData[prop].hasError = false;
                }
            });                 

            if (error) {
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            } else { 
                if (this.guestFamily.length > 0) {
                    let requestExitFamily = this.$refs.requestExitFamily;
                    $(requestExitFamily).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else { 
                    this.requestExitGuest(0);
                }        
            }
        },

        requestExitGuest: function(requestExitFamily){
            let formData = new FormData();
            formData.append('guest_id', this.guestData.id.value);
            Object.keys(this.requestExitProcedureData).forEach((prop) => {
                formData.append(prop, this.requestExitProcedureData[prop].value);
            });
            formData.append('request_exit_family', requestExitFamily);

            axios.post(pathServer + 'aziende/ws/requestExitProcedure', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                    this.guestExitRequestStatus = res.data.data.history_exit_request_status;
                    this.requestExitData.type.id = res.data.data.history_exit_type_id;
                    this.requestExitData.type.name = res.data.data.history_exit_type_name;
                    this.requestExitData.file = res.data.data.history_file;
                    this.requestExitData.note = res.data.data.history_note;

                    if(requestExitFamily){
                        this.guestFamily.forEach((guest) => {
                            guest.exit_request_status_id = res.data.data.family_exit_request_status[guest.id];
                        });
                        this.loadedFamily = JSON.stringify(this.guestFamily);
                    }

                    this.loadGuestHistory();

                    let modalGuestRequestExit = this.$refs.modalGuestRequestExit;
                    $(modalGuestRequestExit).modal('hide');

                    //Aggiorna conteggio notifiche
                    this.updateNotificationsCount();
                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearRequestExitProcedureData: function() {
            this.requestExitProcedureData = {
                exit_type_id: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                file: {
                    required: false,
                    hasError: false,
                    value: ''
                },
                note:  {
                    required: false,
                    hasError: false,
                    value: ''
                }
            };
        },

        openAuthorizeRequestExitModal: function() {
            let modalAuthorizeGuestRequestExit = this.$refs.modalAuthorizeGuestRequestExit;
            $(modalAuthorizeGuestRequestExit).modal({
                backdrop: false,
                keyboard: false
            });
            $(modalAuthorizeGuestRequestExit).modal('show');
        },

        authorizeRequestExitProcedure: function() { 
            var error = false;

            Object.keys(this.authorizeRequestExitProcedureData).forEach((prop) => {
                if (this.authorizeRequestExitProcedureData[prop].required && (this.authorizeRequestExitProcedureData[prop].value == "" || this.authorizeRequestExitProcedureData[prop].value == null)) {
                    error = true;
                    this.authorizeRequestExitProcedureData[prop].hasError = true;
                } else {
                    this.authorizeRequestExitProcedureData[prop].hasError = false;
                }
            });

            if (error) {
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            } else { 
                if (this.guestFamily.length > 0) {
                    let authorizeRequestExitFamily = this.$refs.authorizeRequestExitFamily;
                    $(authorizeRequestExitFamily).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else { 
                    this.authorizeRequestExitGuest(0);
                }       
            }
        },

        authorizeRequestExitGuest: function(authorizeRequestExitFamily){
            let formData = new FormData();
            formData.append('guest_id', this.guestData.id.value);
            Object.keys(this.authorizeRequestExitProcedureData).forEach((prop) => {
                formData.append(prop, this.authorizeRequestExitProcedureData[prop].value);
            });
            formData.append('authorize_request_exit_family', authorizeRequestExitFamily);
            formData.append('exit_type_id', this.requestExitData.type.id);

            axios.post(pathServer + 'aziende/ws/authorizeRequestExitProcedure', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                    this.guestExitRequestStatus = res.data.data.history_exit_request_status;
                    this.authorizeRequestExitData.type.id = res.data.data.history_exit_type_id;
                    this.authorizeRequestExitData.type.name = res.data.data.history_exit_type_name;
                    this.authorizeRequestExitData.file = res.data.data.history_file;
                    this.authorizeRequestExitData.note = res.data.data.history_note;

                    if(authorizeRequestExitFamily){
                        this.guestFamily.forEach((guest) => {
                            guest.exit_request_status_id = res.data.data.family_exit_request_status[guest.id];
                        });
                        this.loadedFamily = JSON.stringify(this.guestFamily);
                    }

                    this.loadGuestHistory();

                    let modalAuthorizeGuestRequestExit = this.$refs.modalAuthorizeGuestRequestExit;
                    $(modalAuthorizeGuestRequestExit).modal('hide');

                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearAuthorizeRequestExitProcedureData: function() {
            this.authorizeRequestExitProcedureData = {
                file: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                note:  {
                    required: false,
                    hasError: false,
                    value: ''
                }
            };
        },

        openExitModal: function() {
            if (this.guestPresenza) {
                alert("L'ospite è segnato come presente nella giornata di oggi. Non è possibile avviare la procedura di uscita.")
            } else {
                if (this.guestExitRequestStatus == 2) {
                    this.exitProcedureData.exit_type_id.value = this.authorizeRequestExitData.type.id;
                    this.updateExitRequirements();
                }
                let modalGuestExit = this.$refs.modalGuestExit;
                $(modalGuestExit).modal({
                    backdrop: false,
                    keyboard: false
                });
                $(modalGuestExit).modal('show');
            }
        },

        updateExitRequirements: function() {
            if (this.exitProcedureData.exit_type_id.value && this.exitTypes[this.exitProcedureData.exit_type_id.value].required_file) {
                this.exitProcedureData.file.required = true;
            } else {
                this.exitProcedureData.file.hasError = false;
                this.exitProcedureData.file.required = false;
            }
            if (this.exitProcedureData.exit_type_id.value && this.exitTypes[this.exitProcedureData.exit_type_id.value].required_note) {
                this.exitProcedureData.note.required = true;
            } else {
                this.exitProcedureData.note.hasError = false;
                this.exitProcedureData.note.required = false;
            }
        },

        executeExitProcedure: function() { 
            var error = false;

            Object.keys(this.exitProcedureData).forEach((prop) => {
                if (this.exitProcedureData[prop].required && (this.exitProcedureData[prop].value == "" || this.exitProcedureData[prop].value == null)) {
                    error = true;
                    this.exitProcedureData[prop].hasError = true;
                } else {
                    this.exitProcedureData[prop].hasError = false;
                }
            });                 

            if (error) {
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            } else { 
                if (this.guestFamily.length > 0) {
                    let exitFamily = this.$refs.exitFamily;
                    $(exitFamily).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else { 
                    this.exitGuest(0);
                }                
            }
        },

        exitGuest: function(exitFamily){
            let formData = new FormData();
            formData.append('guest_id', this.guestData.id.value);
            Object.keys(this.exitProcedureData).forEach((prop) => {
                formData.append(prop, this.exitProcedureData[prop].value);
            });
            formData.append('exit_family', exitFamily);

            axios.post(pathServer + 'aziende/ws/exitProcedure', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                    this.guestStatus = res.data.data.history_status;
                    this.guestExitRequestStatus = null;
                    this.exitData.type = res.data.data.history_exit_type;
                    this.exitData.date = res.data.data.check_out_date;
                    this.exitData.file = res.data.data.history_file;
                    this.exitData.note = res.data.data.history_note;

                    if(exitFamily){
                        this.guestFamily.forEach((guest) => {
                            guest.status_id = res.data.data.family_status[guest.id];
                        });
                        this.loadedFamily = JSON.stringify(this.guestFamily);
                    }

                    this.loadGuestHistory();

                    let modalGuestExit = this.$refs.modalGuestExit;
                    $(modalGuestExit).modal('hide');

                    //Aggiorna conteggio notifiche
                    this.updateNotificationsCount();
                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearExitProcedureData: function() {
            this.exitProcedureData = {
                exit_type_id: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                file: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                note:  {
                    required: false,
                    hasError: false,
                    value: ''
                }
            };
        },

        openConfirmExitModal: function() {
            var date = this.exitData.date.split('/');
            this.confirmExitProcedureData.check_out_date.value = date[2]+'-'+date[1]+'-'+date[0];
            let modalConfirmGuestExit = this.$refs.modalConfirmGuestExit;
            $(modalConfirmGuestExit).modal({
                backdrop: false,
                keyboard: false
            });
            $(modalConfirmGuestExit).modal('show');
        },

        confirmExitProcedure: function() {
            var error = false;

            Object.keys(this.confirmExitProcedureData).forEach((prop) => {
                if (this.confirmExitProcedureData[prop].required && (this.confirmExitProcedureData[prop].value == "" || this.confirmExitProcedureData[prop].value == null)) {
                    error = true;
                    this.confirmExitProcedureData[prop].hasError = true;
                } else {
                    this.confirmExitProcedureData[prop].hasError = false;
                }
            });                 

            if(error){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{ 
                if (this.guestFamily.length > 0) {
                    let confirmExitFamily = this.$refs.confirmExitFamily;
                    $(confirmExitFamily).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else { 
                    this.confirmExitGuest(0);
                }
            }
        },

        confirmExitGuest: function(confirmExitFamily) {
            let params = new URLSearchParams();
            params.append('guest_id', this.guestData.id.value);
            Object.keys(this.confirmExitProcedureData).forEach((prop) => {
                params.append(prop, this.confirmExitProcedureData[prop].value);
            });
            params.append('confirm_exit_family', confirmExitFamily);

            axios.post(pathServer + 'aziende/ws/confirmExit', params)
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                    this.guestStatus = res.data.data.history_status;
                    this.exitData.type = res.data.data.history_exit_type;
                    this.exitData.date = res.data.data.check_out_date;
                    this.exitData.file = res.data.data.history_file;
                    this.exitData.note = res.data.data.history_note;

                    if(confirmExitFamily){
                        this.guestFamily.forEach((guest) => {
                            guest.status_id = res.data.data.family_status[guest.id];
                        });
                        this.loadedFamily = JSON.stringify(this.guestFamily);
                    }

                    this.loadGuestHistory();

                    let modalConfirmGuestExit = this.$refs.modalConfirmGuestExit;
                    $(modalConfirmGuestExit).modal('hide');

                    //Aggiorna conteggio notifiche
                    this.updateNotificationsCount();
                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearConfirmExitProcedureData: function() {
            this.confirmExitProcedureData = {
                check_out_date: {
                    required: true,
                    hasError: false,
                    value: ''
                }
            };
        },

        openTransferModal: function() {
            if (this.guestPresenza) {
                alert("L'ospite è segnato come presente nella giornata di oggi. Non è possibile avviare la procedura di trasferimento.")
            } else {
                axios.get(pathServer + 'aziende/ws/getTransferAziendaDefault/'+this.guestData.sede_id.value)
                    .then(res => { 
                        if (res.data.response == 'OK') {
                            this.transferProcedureData.azienda.value = res.data.data; 
                            let modalGuestTransfer = this.$refs.modalGuestTransfer;
                            $(modalGuestTransfer).modal({
                                backdrop: false,
                                keyboard: false
                            });
                            $(modalGuestTransfer).modal('show');
                        } else {
                            alert(res.data.msg);
                        }
                    }).catch(error => {
                        console.log(error);
                    });
            }
        },

        executeTransferProcedure: function() { 
            var error = false;

            Object.keys(this.transferProcedureData).forEach((prop) => {
                if (this.transferProcedureData[prop].required && (this.transferProcedureData[prop].value == "" || this.transferProcedureData[prop].value == null)) {
                    error = true;
                    this.transferProcedureData[prop].hasError = true;
                } else {
                    this.transferProcedureData[prop].hasError = false;
                }
            });                 

            if(error){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            } else {
                if (this.guestFamily.length > 0) {
                    let transferFamily = this.$refs.transferFamily;
                    $(transferFamily).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else { 
                    this.transferGuest(0);
                } 
            }       
        },

        transferGuest: function(transferFamily) {
            let params = new URLSearchParams();
            params.append('guest_id', this.guestData.id.value);
            Object.keys(this.transferProcedureData).forEach((prop) => {
                if (prop == 'azienda' || prop == 'sede') {
                    params.append(prop, this.transferProcedureData[prop].value.id);
                } else {
                    params.append(prop, this.transferProcedureData[prop].value);
                }
            });
            params.append('transfer_family', transferFamily);

            axios.post(pathServer + 'aziende/ws/transferProcedure', params)
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                    this.guestStatus = res.data.data.history_status;
                    this.transferData.destination = res.data.data.history_destination;
                    this.transferData.destination_id = res.data.data.history_destination_id;
                    this.transferData.date = res.data.data.history_date;
                    this.transferData.note = res.data.data.history_note;
                    this.transferData.cloned_guest = res.data.data.history_cloned_guest;

                    if (transferFamily) {
                        location.reload();
                    }

                    this.loadGuestHistory();

                    let modalGuestTransfer = this.$refs.modalGuestTransfer;
                    $(modalGuestTransfer).modal('hide');

                    //Aggiorna conteggio notifiche
                    this.updateNotificationsCount();
                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearTransferProcedureData: function() {
            this.transferAziende = [];
            this.transferSedi = [];
            this.transferProcedureData = {
                azienda: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                sede: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                check_out_date: {
                    required: true,
                    hasError: false,
                    value: new Date()
                },
                note: {
                    required: false,
                    hasError: false,
                    value: ''
                }
            };
        },

        openConfirmTransferModal: function() {
            var date = this.transferData.date.split('/');
            this.acceptTransferProcedureData.check_in_date.value = date[2]+'-'+date[1]+'-'+date[0];
            let modalConfirmGuestTransfer = this.$refs.modalConfirmGuestTransfer;
            $(modalConfirmGuestTransfer).modal({
                backdrop: false,
                keyboard: false
            });
            $(modalConfirmGuestTransfer).modal('show');
        },

        acceptTransferProcedure: function() {
            var error = false;

            Object.keys(this.acceptTransferProcedureData).forEach((prop) => {
                if (this.acceptTransferProcedureData[prop].required && (this.acceptTransferProcedureData[prop].value == "" || this.acceptTransferProcedureData[prop].value == null)) {
                    error = true;
                    this.acceptTransferProcedureData[prop].hasError = true;
                } else {
                    this.acceptTransferProcedureData[prop].hasError = false;
                }
            });                 

            if(error){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{ 
                if (this.guestFamily.length > 0) {
                    let acceptTransferFamily = this.$refs.acceptTransferFamily;
                    $(acceptTransferFamily).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else { 
                    this.acceptTransfer(0);
                }
            }
        },

        acceptTransfer: function(acceptTransferFamily) {
            let params = new URLSearchParams();
            params.append('guest_id', this.guestData.id.value);
            params.append('accept_transfer_family', acceptTransferFamily);
            Object.keys(this.acceptTransferProcedureData).forEach((prop) => {
                params.append(prop, this.acceptTransferProcedureData[prop].value);
            });

            axios.post(pathServer + 'aziende/ws/acceptTransfer', params)
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                    this.guestStatus = res.data.data.status_id;
                    this.guestData.check_in_date.value = res.data.data.check_in_date
                    this.transferData.destination = '';
                    this.transferData.destination_id = '';
                    this.transferData.provenance = '';
                    this.transferData.date = '';
                    this.transferData.note = '';
                    this.transferData.cloned_guest = '';

                    if(acceptTransferFamily){
                        this.guestFamily.forEach((guest) => {
                            guest.status_id = res.data.data.family_status[guest.id];
                        });
                        this.loadedFamily = JSON.stringify(this.guestFamily);
                    }

                    this.loadedData = JSON.stringify(this.guestData);

                    this.loadGuestHistory();

                    //Aggiorna conteggio notifiche
                    this.updateNotificationsCount();

                    let modalConfirmGuestTransfer = this.$refs.modalConfirmGuestTransfer;
                    $(modalConfirmGuestTransfer).modal('hide');
                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearAcceptTransferProcedureData: function() {
            this.acceptTransferProcedureData = {
                check_in_date: {
                    required: true,
                    hasError: false,
                    value: ''
                }
            };
        },

        searchTransferAziende: function(search, loading) { 
            search = search || this.$refs.selectTransferAzienda.search;
            loading = loading || this.$refs.selectTransferAzienda.toggleLoading;

            loading(true);
            axios.get(pathServer + 'aziende/ws/searchTransferAziende/'+search)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.transferAziende = res.data.data; 
                    loading(false);
                } else {
                    this.transferAziende = [];
                    loading(false);
                }
            }).catch(error => {
                console.log(error);
                loading(false);
            });
        },

        setTransferAzienda: function(value) { 
            if(value != null){
                this.transferProcedureData.azienda.value = value;
                this.transferSedi = [];
                this.transferProcedureData.sede.value = '';
            }
        },

        searchTransferSedi: function(search, loading) {
            search = search || this.$refs.selectTransferSede.search;
            loading = loading || this.$refs.selectTransferSede.toggleLoading;

            var error = false;

            if(this.transferProcedureData.azienda.value == "" || this.transferProcedureData.azienda.value == null){
                error = true;
                this.transferProcedureData.azienda.hasError = true;
            }else{
                this.transferProcedureData.azienda.hasError = false;
            }   

            if(error){
                alert('Si prega di compilare il campo ENTE.');
                this.transferSedi = [];
            }else{
                loading(true);
                axios.get(pathServer + 'aziende/ws/searchTransferSedi/'+this.guestData.sede_id.value+'/'+this.transferProcedureData.azienda.value.id+'/'+search)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.transferSedi = res.data.data; 
                        loading(false);
                    } else {
                        this.transferSedi = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }
        },

        setTransferSede: function(value) { 
            if(value != null){
                this.transferProcedureData.sede.value = value;
            }            
        },

        updateNotificationsCount: function() {
            //Aggiorna conteggio notifiche
            axios.get(pathServer + 'aziende/ws/getGuestsNotificationsCount/1')
            .then(res => { 
                if (res.data.response == 'OK') {
                    var count = res.data.data;
                    if(count > 0){
                        $('.guests_notify_count_label').html(count);
                    } else {
                        $('.guests_notify_count_label').html('');
                    }
                }
            }).catch(error => {
                console.log(error);
            });
        },

        getEducationalQualifications: function(parent_id = 0) {
            axios.get(pathServer + 'aziende/ws/getEducationalQualifications/'+parent_id)
            .then(res => { 
                if (res.data.response == 'OK') {
                    if (parent_id > 0) {
                        this.educationalQualificationChildren = res.data.data;
                    } else {
                        this.educationalQualifications = res.data.data;
                        if (this.guestData.educational_qualification.value == '') {
                            this.guestData.educational_qualification.value = res.data.data[0];
                            this.loadedData = JSON.stringify(this.guestData);
                        }
                    }
                } else {
                    alert(res.data.msg);
                }
            }).catch(error => {
                console.log(error);
            });
        },

        updateEducationalQualificationChildren: function() {
            this.guestData.educational_qualification_child.value = '';
            if (this.guestData.educational_qualification.value.have_children) {
                this.getEducationalQualifications(this.guestData.educational_qualification.value.id);
            } else {
                this.educationalQualificationChildren = [];
            }
        },

        searchReadmissionAziende: function(search, loading) { 
            search = search || this.$refs.selectReadmissionAzienda.search;
            loading = loading || this.$refs.selectReadmissionAzienda.toggleLoading;

            loading(true);
            axios.get(pathServer + 'aziende/ws/searchReadmissionAziende/'+search)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.readmissionAziende = res.data.data; 
                    loading(false);
                } else {
                    this.readmissionAziende = [];
                    loading(false);
                }
            }).catch(error => {
                console.log(error);
                loading(false);
            });
        },

        setReadmissionAzienda: function(value) { 
            if(value != null){
                this.readmissionProcedureData.azienda.value = value;
                this.readmissionSedi = [];
                this.readmissionProcedureData.sede.value = '';
            }
        },

        searchReadmissionSedi: function(search, loading) {
            search = search || this.$refs.selectReadmissionSede.search;
            loading = loading || this.$refs.selectReadmissionSede.toggleLoading;

            var error = false;

            if(this.readmissionProcedureData.azienda.value == "" || this.readmissionProcedureData.azienda.value == null){
                error = true;
                this.readmissionProcedureData.azienda.hasError = true;
            }else{
                this.readmissionProcedureData.azienda.hasError = false;
            }   

            if(error){
                alert('Si prega di compilare il campo ENTE.');
                this.readmissionSedi = [];
            }else{
                loading(true);
                axios.get(pathServer + 'aziende/ws/searchReadmissionSedi/'+this.readmissionProcedureData.azienda.value.id+'/'+search)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.readmissionSedi = res.data.data; 
                        loading(false);
                    } else {
                        this.readmissionSedi = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }
        },

        setReadmissionSede: function(value) { 
            if(value != null){
                this.readmissionProcedureData.sede.value = value;
            }            
        },

        openReadmissionModal: function() {
            axios.get(pathServer + 'aziende/ws/getReadmissionAziendaDefault/'+this.guestData.sede_id.value)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.readmissionProcedureData.azienda.value = res.data.data; 
                        axios.get(pathServer + 'aziende/ws/getReadmissionSedeDefault/'+this.guestData.sede_id.value)
                        .then(res => { 
                            if (res.data.response == 'OK') {
                                this.readmissionProcedureData.sede.value = res.data.data; 
                                let modalGuestReadmission = this.$refs.modalGuestReadmission;
                                $(modalGuestReadmission).modal({
                                    backdrop: false,
                                    keyboard: false
                                });
                                $(modalGuestReadmission).modal('show');
                            } else {
                                alert(res.data.msg);
                            }
                        }).catch(error => {
                            console.log(error);
                        });
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        executeReadmissionProcedure: function() { 
            var error = false;

            Object.keys(this.readmissionProcedureData).forEach((prop) => {
                if (this.readmissionProcedureData[prop].required && (this.readmissionProcedureData[prop].value == "" || this.readmissionProcedureData[prop].value == null)) {
                    error = true;
                    this.readmissionProcedureData[prop].hasError = true;
                } else {
                    this.readmissionProcedureData[prop].hasError = false;
                }
            });                 

            if(error){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            } else {
                this.readmitGuest();
            }       
        },

        readmitGuest: function() {
            let params = new URLSearchParams();
            params.append('guest_id', this.guestData.id.value);
            Object.keys(this.readmissionProcedureData).forEach((prop) => {
                if (prop == 'azienda' || prop == 'sede') {
                    params.append(prop, this.readmissionProcedureData[prop].value.id);
                } else {
                    params.append(prop, this.readmissionProcedureData[prop].value);
                }
            });

            axios.post(pathServer + 'aziende/ws/readmissionProcedure', params)
            .then(res => {
                if (res.data.response == 'OK') {
                    alert(res.data.msg);
                
                    let modalGuestReadmission = this.$refs.modalGuestReadmission;
                    $(modalGuestReadmission).modal('hide');

                    //Apertura pagina ospite riammesso
                    window.location = pathServer + 'aziende/guests/guest?sede='+res.data.data.sede_id+'&guest='+res.data.data.guest_id;
                } else {
                    alert(res.data.msg);
                }
            })
            .catch(error => {
                console.log(error);
            });
        },

        clearReadmissionProcedureData: function() {
            this.readmissionAziende = [];
            this.readmissionSedi = [];
            this.readmissionProcedureData = {
                azienda: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                sede: {
                    required: true,
                    hasError: false,
                    value: ''
                },
                note: {
                    required: false,
                    hasError: false,
                    value: ''
                }
            };
        },

        downloadExitDocument: function(file) {
            $('#template-spinner').show();
            document.cookie = 'downloadStarted=0;path=/';    
            window.location = pathServer + 'aziende/ws/downloadGuestExitFile?file=' + encodeURIComponent(file);
            checkCookieForLoader('downloadStarted', '1');
        },

        createInterview: function(type){
            $('#template-spinner').show();
            let params = new URLSearchParams();
            params.append('guest_id', this.guestData.id.value);

            if (type.indexOf('decreto') === 0) {
                params.append('survey_id', this.requestExitData.type.modello_decreto);
            } else if (type.indexOf('notifica') === 0) {
                params.append('survey_id', this.requestExitData.type.modello_notifica);
            }

            axios.post(pathServer + 'surveys/ws/create_interview', params)
            .then(res => {
                if (res.data.response == 'OK') {
                    if (type.indexOf('decreto') === 0) {
                        this.decreti = res.data.data;
                    } else if (type.indexOf('notifica') === 0) {
                        this.notifiche = res.data.data;
                    }
                    console.log(res.data.data);
                } else {
                    alert(res.data.msg);
                }
                $('#template-spinner').hide();
            })
            .catch(error => {
                console.log(error);
                $('#template-spinner').hide();
            });
                
        },
        
    }

});

var beforeunload = true; 
window.addEventListener('beforeunload', function (e) { 
    if(
        beforeunload && 
        (
            app.guestData.id.value === null || 
            JSON.stringify(app.guestData) !== app.loadedData || 
            JSON.stringify(app.guestFamily) !== app.loadedFamily
        )
    ){
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    }else{
        beforeunload = true;
    }
});