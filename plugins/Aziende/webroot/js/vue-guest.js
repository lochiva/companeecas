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
                required: false
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
        countries: [],
        familyGuests: [],
        educationalQualifications: [],
        educationalQualificationChildren: [],
        familyId : '',
        guestFamily: [],
        guestHistory: [],
        exitTypes: [],
        exitProcedureData: {
            exit_type_id: {
                required: true,
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
            note: {
                required: false,
                hasError: false,
                value: ''
            }
        },
        transferData: {
            destination: '',
            provenance: '',
            date: '',
            note: '',
        },
        datepickerItalian: vdp_translation_it.js,
        guestsForSearch: [],
        searchedGuest: null,
        searchGuestSelectVisible: false,
        loadedData: '',
        loadedFamily: ''
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    computed: {

    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.guestData.sede_id.value = url.searchParams.get("sede");
        this.guestData.id.value = url.searchParams.get("guest");

        if(this.guestData.id.value){
            this.loadGuest(this.guestData.id.value);
        }

        this.getEducationalQualifications();
        this.getExitTypes();

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
                        this.guestData.draft.value = res.data.data.draft;
                        this.guestData.draft_expiration.value = res.data.data.draft_expiration;
                        this.guestData.suspended.value = res.data.data.suspended;

                        this.loadedData = JSON.stringify(this.guestData);

                        this.guestPresenza = res.data.data.presenza;
                        this.guestStatus = res.data.data.status_id;

                        this.exitData.type = res.data.data.history_exit_type;
                        this.exitData.date = res.data.data.check_out_date;
                        this.exitData.note = res.data.data.history_note;
                        this.transferData.destination = res.data.data.history_destination;
                        this.transferData.provenance = res.data.data.history_provenance;
                        this.transferData.date = res.data.data.check_out_date;
                        this.transferData.note = res.data.data.history_note;

                        this.familyId = res.data.data.family_id;
                        this.guestFamily = res.data.data.family; 

                        this.loadedFamily = JSON.stringify(this.guestFamily);

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
                if(prop == 'minor'){
                    if(this.guestData[prop].value && !this.guestData.minor_family.value && !this.guestData.minor_alone.value){
                        errors = true;
                        msg += 'Avendo selezionato "Minore" è necessario indicare se con riferimento a nucleo familiare oppure solo.\n';
                    }
                }
                if(prop == 'educational_qualification'){
                    if(this.guestData[prop].value && this.guestData[prop].value.have_children && !this.guestData.educational_qualification_child.value){
                        errors = true;
                        msg += 'Per il titolo di studio selezionato è necessario selezionare anche il dettaglio.\n';
                    }
                }
            });

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
                if(index == ""){
                    let params = new URLSearchParams();
                    params.append('id', this.guestData.id.value);

                    axios.post(pathServer + 'aziende/ws/removeGuestFromFamily', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            alert(res.data.msg);
                            this.familyId = '';
                            this.guestFamily = [];
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
            axios.get(pathServer + 'aziende/ws/getExitTypes/'+this.ente_type)
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

        openExitModal: function() {
            if (this.guestPresenza) {
                alert("L'ospite è segnato come presente nella giornata di oggi. Non è possibile avviare la procedura di uscita.")
            } else {
                let modalGuestExit = this.$refs.modalGuestExit;
                $(modalGuestExit).modal('show');
            }
        },

        updateExitNote: function() {
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

            if(error){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{ 
                let params = new URLSearchParams();
                params.append('guest_id', this.guestData.id.value);
                Object.keys(this.exitProcedureData).forEach((prop) => {
                    params.append(prop, this.exitProcedureData[prop].value);
                });
    
                axios.post(pathServer + 'aziende/ws/exitProcedure', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        alert(res.data.msg);
                        this.guestStatus = res.data.data.history_status;
                        this.exitData.type = res.data.data.history_exit_type;
                        this.exitData.date = res.data.data.check_out_date;
                        this.exitData.note = res.data.data.history_note;
    
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
            }
        },

        clearExitProcedureData: function() {
            this.exitProcedureData = {
                exit_type_id: {
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
            $(modalConfirmGuestExit).modal('show');
        },

        confirmExit: function() {
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
                let params = new URLSearchParams();
                params.append('guest_id', this.guestData.id.value);
                Object.keys(this.confirmExitProcedureData).forEach((prop) => {
                    params.append(prop, this.confirmExitProcedureData[prop].value);
                });

                axios.post(pathServer + 'aziende/ws/confirmExit', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        alert(res.data.msg);
                        this.guestStatus = res.data.data.history_status;
                        this.exitData.type = res.data.data.history_exit_type;
                        this.exitData.date = res.data.data.check_out_date;
                        this.exitData.note = res.data.data.history_note;

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
            }
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
            }else{ 
                let params = new URLSearchParams();
                params.append('guest_id', this.guestData.id.value);
                Object.keys(this.transferProcedureData).forEach((prop) => {
                    if (prop == 'azienda' || prop == 'sede') {
                        params.append(prop, this.transferProcedureData[prop].value.id);
                    } else {
                        params.append(prop, this.transferProcedureData[prop].value);
                    }
                });
    
                axios.post(pathServer + 'aziende/ws/transferProcedure', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        alert(res.data.msg);
                        this.guestStatus = res.data.data.history_status;
                        this.transferData.destination = res.data.data.history_destination;
                        this.transferData.date = res.data.data.check_out_date;
                        this.transferData.note = res.data.data.history_note;
    
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
            }
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
                note: {
                    required: false,
                    hasError: false,
                    value: ''
                }
            };
        },

        acceptTransfer: function() {
            if (confirm('Si è sicuri di voler confermare l\'ingresso dell\'ospite?')) {
                let params = new URLSearchParams();
                params.append('guest_id', this.guestData.id.value);

                axios.post(pathServer + 'aziende/ws/acceptTransfer', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        alert(res.data.msg);
                        this.guestStatus = res.data.data.status_id;
                        this.guestData.check_in_date.value = res.data.data.check_in_date
                        this.transferData.provenance = '';
                        this.transferData.date = '';
                        this.transferData.note = '';
    
                        this.loadGuestHistory();

                        //Aggiorna conteggio notifiche
                        this.updateNotificationsCount();
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },

        searchTransferAziende: function(search, loading) { 
            if(search != ''){
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
            }
        },

        setTransferAzienda: function(value) { 
            if(value != null){
                this.transferProcedureData.azienda.value = value;
                this.transferSedi = [];
                this.transferProcedureData.sede.value = '';
            }
        },

        searchTransferSedi: function(search, loading) {
            if(search != ''){
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
            }
        },

        setTransferSede: function(value) { 
            if(value != null){
                this.transferProcedureData.sede.value = value;
            }            
        },

        updateNotificationsCount: function() {
            //Aggiorna conteggio notifiche
            axios.get(pathServer + 'aziende/ws/getGuestsNotificationsCount/')
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
        }
        
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