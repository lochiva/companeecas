Vue.component('v-select', VueSelect.VueSelect);
Vue.use(VueMaterial.default);

var app = new Vue({
    el: '#app-guest',
    data: {
        role: role,
        guestData: {
            id: '',
            code: '',
            sede_id: '',
            name: {
                hasError: false,
                value: ''
            },
            surname: {
                hasError: false,
                value: ''
            },
            guest_type_id: {
                hasError: false,
                value: ''
            },
            service_type_id: {
                hasError: false,
                value: ''
            },
            pregnant: '',
            cf: {
                hasError: false,
                value: ''
            },
            tel: {
                hasError: false,
                value: ''
            },
            email: {
                hasError: false,
                value: ''
            },
            birth_date: {
                hasError: false,
                value: ''
            },
            citizenship: {
                hasError: false,
                value: ''
            },
            ethnicity: {
                hasError: false,
                value: ''
            },
            native_language: {
                hasError: false,
                value: ''
            },
            other_languages: {
                hasError: false,
                value: ''
            },
            legal_situation: {
                hasError: false,
                value: ''
            },
            iban: {
                hasError: false,
                value: ''
            },
            opening_date: {
                hasError: false,
                value: ''
            },
            reference_bank: {
                hasError: false,
                value: ''
            },
            biography: {
                hasError: false,
                value: ''
            },
            arrival_date: {
                hasError: false,
                value: '',
                text: ''
            },
            extension: '',
            status: {
                hasError: false,
                value: '1'
            },
            due_date: '',
            notice_date: '',
            family: [],
            exit_type: '',
            exit_date: '',
            exit_note: '',
            absences: [],
            locked: false
        },
        datepickerItalian: vdp_translation_it.js,
        loadedData: ''
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    computed: {

    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.guestData.sede_id = url.searchParams.get("sede");

        this.setServiceType(this.guestData.sede_id);

        this.guestData.id = url.searchParams.get("guest");

        if(this.guestData.id){
            this.loadGuest(this.guestData.id);
        }

    },
       
    methods: {

        loadGuest: function(id){
            axios.get(pathServer + 'diary/ws/getGuest/' + id)
                .then(res => {  
                    if (res.data.response == 'OK') { 
                        this.guestData.code = res.data.data.code;
                        this.guestData.name.value = res.data.data.name;
                        this.guestData.surname.value = res.data.data.surname;
                        this.guestData.guest_type_id.value = res.data.data.guest_type_id;
                        this.guestData.service_type_id.value = res.data.data.service_type_id.toString(); 
                        this.guestData.pregnant = res.data.data.pregnant;
                        this.guestData.arrival_date.value = new Date(res.data.data.arrival_date);
                        this.guestData.arrival_date.text = new Date(res.data.data.arrival_date).toLocaleDateString('it-IT', {year: 'numeric', month: '2-digit', day: '2-digit'});
                        this.guestData.extension = res.data.data.extension;
                        this.guestData.status.value = res.data.data.status;
                        this.guestData.cf.value = res.data.data.cf;
                        this.guestData.tel.value = res.data.data.tel;
                        this.guestData.email.value = res.data.data.email;
                        this.guestData.birth_date.value = res.data.data.birth_date;
                        this.guestData.citizenship.value = res.data.data.citizenship;
                        this.guestData.ethnicity.value = res.data.data.ethnicity;
                        this.guestData.native_language.value = res.data.data.native_language;
                        this.guestData.other_languages.value = res.data.data.other_languages;
                        this.guestData.legal_situation.value = res.data.data.legal_situation;
                        this.guestData.iban.value = res.data.data.iban;
                        this.guestData.opening_date.value = res.data.data.opening_date;
                        this.guestData.reference_bank.value = res.data.data.reference_bank;
                        this.guestData.biography.value = res.data.data.biography;
                        this.guestData.due_date = res.data.data.due_date;
                        this.guestData.notice_date = res.data.data.notice_date;
                        this.guestData.family = res.data.data.family; 
                        if([2, 4, 6].includes(res.data.data.status)){
                            this.guestData.exit_type = res.data.data.exit_type.name;
                            this.guestData.exit_date = res.data.data.exit_date;
                            this.guestData.exit_note = res.data.data.exit_note;
                            this.guestData.exit_destination = res.data.data.exit_destination;
                        }else{
                            this.guestData.exit_type = '';
                            this.guestData.exit_date = '';
                            this.guestData.exit_note = '';
                            this.guestData.exit_destination = '';
                        }
                        this.guestData.absences = res.data.data.absences;
                        this.familyId = res.data.data.family_id;
                        this.statusScadenza = res.data.data.status_scadenza;                         
                        this.guestData.locked = res.data.data.locked;
                        if(res.data.data.status == '3'){
                            this.transferDestination = res.data.data.transfer_destination;
                        }
                        if(res.data.data.status == '5'){
                            this.transferProvenance = res.data.data.transfer_provenance;
                            this.transferNote = res.data.data.transfer_note;
                            this.transferOriginalGuest = res.data.data.transfer_original_guest;
                        }

                        this.loadedData = JSON.stringify(this.guestData);

                        this.loadSurveys(this.guestData.sede_id, this.guestData.id);
                        this.loadGuestHistory(this.guestData.id);

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

            let excludedProp = ['id', 'sede_id', 'due_date', 'notice_date', 'family', 'code', 'exit_type', 
                                'exit_date', 'exit_destination', 'exit_note', 'absences', 'pregnant', 'extension', 'locked',
                                'tel', 'birth_date', 'citizenship', 'ethnicity', 'native_language', 'other_languages',
                                'legal_situation', 'opening_date', 'reference_bank', 'biography'];

            Object.keys(this.guestData).forEach((prop) => {
                if(!excludedProp.includes(prop)){
                    if(prop == 'cf'){
                        if(this.guestData[prop].value != "" && !this.checkCf(this.guestData[prop].value)){
                            errors = true;
                            msg += 'Codice fiscale non valido.\n';
                            this.guestData[prop].hasError = true;
                        }else{
                            this.guestData[prop].hasError = false;
                        }
                    }else if(prop == 'email'){
                        var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                        if(this.guestData[prop].value != "" && !regex.test(this.guestData[prop].value)){
                            errors = true;
                            msg += 'Email non valida.\n';
                            this.guestData[prop].hasError = true;
                        }else{
                            this.guestData[prop].hasError = false;
                        }
                    }else if(prop == 'iban'){
                        if(this.guestData[prop].value != "" && this.guestData[prop].value.length != 27){
                            errors = true;
                            msg += 'L\'IBAN deve essere lungo esattamente 27 caratteri.\n';
                            this.guestData[prop].hasError = true;
                        }else{
                            this.guestData[prop].hasError = false;
                        }
                    }else{
                        if(this.guestData[prop].value == "" || this.guestData[prop].value == null){
                            errors = true;
                            msg += 'Si prega di compilare tutti i campi obbligatori.\n'
                            this.guestData[prop].hasError = true;
                        }else{
                            this.guestData[prop].hasError = false;
                        }
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

            let readonlyProp = ['exit_type', 'exit_date', 'exit_destination', 'exit_note'];
            let excludedProp = ['id', 'sede_id', 'due_date', 'notice_date', 'code', 'pregnant', 'extension', 'locked'];

            Object.keys(this.guestData).forEach((prop) => {
                if(!readonlyProp.includes(prop)){
                    if(excludedProp.includes(prop)){
                        params.append(prop, this.guestData[prop]);
                    }else if(prop == 'family' || prop == 'absences'){
                        params.append(prop, JSON.stringify(this.guestData[prop]));
                    }else{
                        params.append(prop, this.guestData[prop].value);
                    }
                }
            });

            axios.post(pathServer + 'diary/ws/saveGuest', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        if(exit){
                            window.location = pathServer + 'diary/guests/index/'+this.guestData.sede_id;
                        }else{ 
                            alert(res.data.msg);
                            window.location = pathServer + 'diary/guests/guest?sede='+this.guestData.sede_id+'&guest='+res.data.data;
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
                if(this.guestData.family[index].id == ''){
                    this.guestData.family.splice(index, 1);
                    this.serviceFree += 1;
                }else{
                    let params = new URLSearchParams();
                    params.append('id', this.guestData.family[index].id);

                    axios.post(pathServer + 'diary/ws/deleteGuest', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            alert(res.data.msg);
                            this.guestData.family.splice(index, 1);
                            this.isServiceFreeForSede(this.guestData.sede_id);
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

        checkCf: function(cfins){
            var cf = cfins.toUpperCase();
            var cfReg = /^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/;

            if (!cfReg.test(cf)){
                return false;
            }
            
            var set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            var set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
            var setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            var setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
            var s = 0;

            for( i = 1; i <= 13; i += 2 ){
                s += setpari.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
            }

            for( i = 0; i <= 14; i += 2 ){
                s += setdisp.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
            }

            if ( s%26 != cf.charCodeAt(15)-'A'.charCodeAt(0) ){
                return false;
            }

            return true;
        }
        
    }

});

var beforeunload = true; 
window.addEventListener('beforeunload', function (e) { 
    if(beforeunload && (app.guestData.id === null || JSON.stringify(app.guestData) !== app.loadedData)){
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    }else{
        beforeunload = true;
    }
});