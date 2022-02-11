Vue.component('v-select', VueSelect.VueSelect);
Vue.use(VueMaterial.default);

var app = new Vue({
    el: '#app-guest',
    data: {
        role: role,
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
        countries: [],
        familyGuests: [],
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

        this.guestData.sede_id.value = url.searchParams.get("sede");
        this.guestData.id.value = url.searchParams.get("guest");

        if(this.guestData.id.value){
            this.loadGuest(this.guestData.id.value);
        }

    },
       
    methods: {

        loadGuest: function(id){
            axios.get(pathServer + 'aziende/ws/getGuest/' + id)
                .then(res => {  
                    if (res.data.response == 'OK') { 
                        this.guestData.sede_id.value = res.data.data.sede_id;
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
                        this.guestData.draft.value = res.data.data.draft;
                        this.guestData.draft_expiration.value = res.data.data.draft_expiration;
                        this.guestData.suspended.value = res.data.data.suspended;

                        this.loadedData = JSON.stringify(this.guestData);

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

            Object.keys(this.guestData).forEach((prop) => {
                if (this.guestData[prop].required) {
                    if(this.guestData[prop].value == "" || this.guestData[prop].value == null){
                        errors = true;
                        msg += 'Si prega di compilare tutti i campi obbligatori.\n'
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
                }else if(prop == 'vestanet_id'){
                    if(this.guestData[prop].value != "" && (this.guestData[prop].value.length < 9 || this.guestData[prop].value.length > 10)){
                        errors = true;
                        msg += 'ID Vestanet non valida.\n';
                        this.guestData[prop].hasError = true;
                    }else{
                        this.guestData[prop].hasError = false;
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
                if (prop == 'country_birth' || prop == 'family_guest') {
                    if (this.guestData[prop] == '' || this.guestData[prop] == null) {
                        params.append(prop, '');
                    } else {
                        params.append(prop, this.guestData[prop].value.id);
                    }
                } else {
                    params.append(prop, this.guestData[prop].value);
                }
            });

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
                if(this.guestData.family[index].id == ''){
                    this.guestData.family.splice(index, 1);
                    this.serviceFree += 1;
                }else{
                    let params = new URLSearchParams();
                    params.append('id', this.guestData.family[index].id);

                    axios.post(pathServer + 'aziende/ws/deleteGuest', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            alert(res.data.msg);
                            this.guestData.family.splice(index, 1);
                            this.isServiceFreeForSede(this.guestData.sede_id.value);
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

        searchGuest: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'aziende/ws/searchGuest/'+search+'/'+this.guestData.id.value)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.familyGuests = res.data.data;
                        loading(false);
                    } else {
                        this.familyGuests = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.familyGuests = [];
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
        }
        
    }

});

var beforeunload = true; 
window.addEventListener('beforeunload', function (e) { 
    if(beforeunload && (app.guestData.id.value === null || JSON.stringify(app.guestData) !== app.loadedData)){
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    }else{
        beforeunload = true;
    }
});