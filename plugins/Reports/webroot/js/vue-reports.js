Vue.component('v-select', VueSelect.VueSelect);

Vue.component('tree-item', {
    template: '#item-template',
    props: [
        'index',
        'item',
        'parentitem',
        'label',
        'role',
        'selected-partner',
        'selected-gestore',
        'selected-structure',
        'id-interview',
        'status',
        'interview-disabled'
    ],
    data: function () {
        return {
            isOpen: false,
            datepickerItalian: vdp_translation_it.js
        }
    },
    components: {
        'datepicker': vuejsDatepicker,
    },
    mounted: function () {

    },
    methods: {
        toggle: function () {
            this.isOpen = !this.isOpen;
        },
        forceOpen: function () {
            this.isOpen = true;
        },
        emptyAnswer: function(question) {
            if(!question.other_answer_check){
                question.other_answer = '';
            }
        },
        emptyExtensionSingle: function(extensions) {
            extensions.forEach(function(extension, index){
                extensions[index] = '';
            });
        },
        emptyExtensionMultiple: function(answer) {
            if(!answer.check){
                answer.extended = '';
            }
        },
        updateConditionedQuestions: function(question) {
            this.$root.updateConditionedQuestions(question);
        },
        addRowTable: function(params) { 
            var row = [];

            params.headers.forEach(function(){
                row.push('');
            });

            params.answer.push(row);
        },
        removeRowTable: function(params) { 
            if(confirm("Si è sicuri di voler cancellare la riga?")){
                params.answer.splice(params.index, 1);
            }
        },
    }
});

var app = new Vue({
    el: '#app-report',
    data: {
        datepickerItalian: vdp_translation_it.js,
        reportId: 0,
        reportCode: '',
        reportStatus: '',
        userRole: '',
        reportHolder: {
            holder: 'centro',
            node: 0
        },
        holderChanged: false,
        loadedHolder: '',
        loadedVictim: '',
        loadedWitness: '',
        loadedInterview: '',
        victimChanged: false,
        witnessChanged: false,
        interviewChanged: false,
        victim : {
            victim_id: 0,
            date_update: '',
            user_update: '',
            lastname: '',
            firstname: '',
            gender_id: '',
            gender_user_text: '',
            birth_year: '',
            country: 0,
            citizenship: 0,
            citizenship_user_text: '',
            educational_qualification_id: '',
            educational_qualification_user_text: '',
            religion_id: '',
            religion_user_text: '',
            type_occupation_id: '',
            type_occupation_user_text: '',
            marital_status_id: '',
            marital_status_user_text: '',
            in_italy_from_year: '',
            residency_permit_id: '',
            residency_permit_user_text: '',
            lives_with : {
                mother: false,
                father: false,
                partner: false,
                son: false,
                brother: false,
                other_relatives: false,
                none: false,
                other_non_relatives: false
            },
            telephone: '',
            mobile: '',
            email: '',
            region: 0,
            province: 0,
            city: 0
        },
        witness: {
            witness_id: 0,
            date_update: '',
            user_update: '',
            type_reporter: '',
            type: '',
            lastname: '',
            firstname: '',
            gender_id: '',
            gender_user_text: '',
            birth_year: '',
            country: 0,
            citizenship: 0,
            citizenship_user_text: '',
            educational_qualification_id: '',
            educational_qualification_user_text: '',
            religion_id: '',
            religion_user_text: '',
            type_occupation_id: '',
            type_occupation_user_text: '',
            marital_status_id: '',
            marital_status_user_text: '',
            in_italy_from_year: '',
            residency_permit_id: '',
            residency_permit_user_text: '',
            lives_with : {
                mother: false,
                father: false,
                partner: false,
                son: false,
                brother: false,
                other_relatives: false,
                none: false,
                other_non_relatives: false
            },
            telephone: '',
            mobile: '',
            email: '',
            region: 0,
            province: 0,
            city: 0,
            business_name: '',
            piva: '',
            region_legal: 0,
            province_legal: 0,
            city_legal: 0,
            address_legal: '',
            region_operational: 0,
            province_operational: 0,
            city_operational: 0,
            address_operational: '',
            legal_representative: '',
            telephone_legal: '',
            mobile_legal: '',
            email_legal: '',
            operational_contact: '',
            telephone_operational: '',
            mobile_operational: '',
            email_operational: ''
        },
        nodes: [],
        countries: [],
        regions: [],
        provinces: [],
        cities: [],
        anagrafica: {
            type_anagrafica: '',
            date_update: '',
            user_update: '',
            type_reporter: '',
            type: '',
            lastname: '',
            firstname: '',
            gender_id: '',
            gender_user_text: '',
            birth_year: '',
            country: 0,
            citizenship: 0,
            citizenship_user_text: '',
            educational_qualification_id: '',
            educational_qualification_user_text: '',
            religion_id: '',
            religion_user_text: '',
            type_occupation_id: '',
            type_occupation_user_text: '',
            marital_status_id: '',
            marital_status_user_text: '',
            in_italy_from_year: '',
            residency_permit_id: '',
            residency_permit_user_text: '',
            lives_with : {
                mother: false,
                father: false,
                partner: false,
                son: false,
                brother: false,
                other_relatives: false,
                none: false,
                other_non_relatives: false
            },
            telephone: '',
            mobile: '',
            email: '',
            region: 0,
            province: 0,
            city: 0,
            business_name: '',
            piva: '',
            region_legal: 0,
            province_legal: 0,
            city_legal: 0,
            address_legal: '',
            region_operational: 0,
            province_operational: 0,
            city_operational: 0,
            address_operational: '',
            legal_representative: '',
            telephone_legal: '',
            mobile_legal: '',
            email_legal: '',
            operational_contact: '',
            telephone_operational: '',
            mobile_operational: '',
            email_operational: ''
        },
        nodes: [],
        countries: [],
        citizenships: [],
        regions: [],
        provinces: [],
        cities: [],
        idSurvey: 0,
        surveyVersion: '',
        interviewData: {
            idInterview: 0,
            date_update: '',
            user_update: '',
            title: '',
            subtitle: '',
            description: '',
            status: '',
            version: '',
            items: []
        },
        preview: 0,
        close_report: {
            date: '',
            motivation: '',
            outcome: ''
        },
        reopenMotivation: '',
        transferMotivation: '',
        history: []
    },

    components: {
        'datepicker': vuejsDatepicker,
    },

    watch: {

        reportHolder: {
            handler(){
                if(this.loadedHolder == JSON.stringify(this.reportHolder)) {
                    this.holderChanged = false;
                } else {
                    this.holderChanged = true;
                }
            },
            deep: true
        },

        victim: {
            handler(){
                if(this.loadedVictim == JSON.stringify(this.victim)) {
                    this.victimChanged = false;
                } else {
                    this.victimChanged = true;
                }
            },
            deep: true
        },

        witness: {
            handler(){
                if(this.loadedWitness == JSON.stringify(this.witness)) {
                    this.witnessChanged = false;
                } else {
                    this.witnessChanged = true;
                }
            },
            deep: true
        },

        interviewData: {
            handler(){
                if(this.loadedInterview == JSON.stringify(this.interviewData)) {
                    this.interviewChanged = false;
                } else {
                    this.interviewChanged = true;
                }
            },
            deep: true
        },
    },

    computed: {
        interviewDisabled: function(){
            return this.reportStatus == 'transfer' || this.reportStatus == 'transfer_accepted' || 
                (this.userRole != 'admin' && this.reportStatus == 'closed') || (this.userRole == 'centro' && this.reportHolder.holder == 'nodo') 
                || this.interviewData.status == 2;
        },

        disabledReport: function() {
            return this.reportStatus == 'transfer' || this.reportStatus == 'transfer_accepted' || 
                (this.userRole != 'admin' && this.reportStatus == 'closed') || (this.userRole == 'centro' && this.reportHolder.holder == 'nodo');
        },

        disabledReportHolder: function() {
            return this.reportStatus == 'transfer_accepted' || this.reportStatus == 'closed';
        },

        reportStatusLabel: function() {
            var statusLabel = '';
            switch (this.reportStatus) {
                case 'open':
                    statusLabel = 'aperto';
                    break;
                case 'closed':
                    statusLabel = 'chiuso';
                    break;
                case 'transfer':
                    statusLabel = 'trasferimento';
                    break;
                case 'transfer_accepted':
                    statusLabel = 'trasferito';
                    break;
            }
            return statusLabel;
        }
    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        if (url.searchParams.get("report")) {
            this.reportId = url.searchParams.get("report");
        }

        if (url.searchParams.get("preview")) {
            this.preview = url.searchParams.get("preview");
        }

        this.loadedVictim = JSON.stringify(this.victim);
        this.loadedWitness = JSON.stringify(this.witness);

        this.userRole = this.getUserRole();

        if(this.reportId){
            this.loadReportData(this.reportId);
        }else{
            this.loadSurvey();
        }

    },
       
    methods: {

        getUserRole: function(){
            axios.get(pathServer + 'reports/ws/getUserRole')
            .then(res => {  
                if (res.data.response == 'OK') { 
                    this.userRole = res.data.data;
                } else {
                    alert(res.data.msg);
                }
            }).catch(error => {
                console.log(error);
            });
        },

        loadReportData: function(id){
            axios.get(pathServer + 'reports/ws/loadReport/' + id)
                .then(res => {  
                    if (res.data.response == 'OK') { 
                        this.reportCode = res.data.data.report.province_code + res.data.data.report.code;
                        if (res.data.data.report.node) {
                            this.reportHolder.node = {
                                'id': res.data.data.report.node.id,
                                'label': res.data.data.report.node.denominazione
                            };
                            this.reportHolder.holder = 'nodo';
                        } else {
                            this.reportHolder.holder = 'centro';
                        }
                        this.reportStatus = res.data.data.report.status;
                        if (res.data.data.victim) {
                            this.victim.victim_id = res.data.data.victim.id;
                            this.victim.date_update = res.data.data.victim.date_update;
                            if (res.data.data.victim.user) {
                                this.victim.user_update = res.data.data.victim.user.nome+' '+res.data.data.victim.user.cognome;
                            }
                            this.victim.lastname = res.data.data.victim.lastname;
                            this.victim.firstname = res.data.data.victim.firstname;
                            this.victim.gender_id = String(res.data.data.victim.gender_id);
                            this.checkUserTextGender('victim');
                            this.victim.gender_user_text = res.data.data.victim.gender_user_text;
                            this.victim.birth_year = res.data.data.victim.birth_year;
                            if (res.data.data.victim.country && res.data.data.victim.country.c_luo != 0) {
                                this.victim.country = {
                                    'id': res.data.data.victim.country.c_luo,
                                    'label': res.data.data.victim.country.des_luo
                                };
                            }
                            if (res.data.data.victim.citizenship && res.data.data.victim.citizenship.c_luo != 0) {
                                this.victim.citizenship = {
                                    'id': res.data.data.victim.citizenship.c_luo,
                                    'label': res.data.data.victim.citizenship.des_luo,
                                    'user_text': res.data.data.victim.citizenship.user_text
                                };
                            }else{
                                this.victim.citizenship = 0;
                            }
                            this.checkUserTextCitizenship('victim');
                            this.victim.citizenship_user_text = res.data.data.victim.citizenship_user_text;
                            this.victim.educational_qualification_id = String(res.data.data.victim.educational_qualification_id);
                            this.checkUserTextEducationalQualification('victim');
                            this.victim.educational_qualification_user_text = res.data.data.victim.educational_qualification_user_text;
                            this.victim.religion_id = String(res.data.data.victim.religion_id);
                            this.checkUserTextReligion('victim');
                            this.victim.religion_user_text = res.data.data.victim.religion_user_text;
                            this.victim.type_occupation_id = String(res.data.data.victim.type_occupation_id);
                            this.checkUserTextTypeOccupation('victim');
                            this.victim.type_occupation_user_text = res.data.data.victim.type_occupation_user_text;
                            this.victim.marital_status_id = String(res.data.data.victim.marital_status_id);
                            this.checkUserTextMaritalStatus('victim');
                            this.victim.marital_status_user_text = res.data.data.victim.marital_status_user_text;
                            this.victim.in_italy_from_year = res.data.data.victim.in_italy_from_year;
                            this.victim.residency_permit_id = String(res.data.data.victim.residency_permit_id);
                            this.checkUserTextResidencyPermit('victim');
                            this.victim.residency_permit_user_text = res.data.data.victim.residency_permit_user_text;
                            if (res.data.data.victim.lives_with.length > 0) {
                                for (i = 0; i < res.data.data.victim.lives_with.length; ++i) {
                                    this.victim.lives_with[res.data.data.victim.lives_with[i]] = true;
                                }
                            }
                            this.victim.telephone = res.data.data.victim.telephone;
                            this.victim.mobile = res.data.data.victim.mobile;
                            this.victim.email = res.data.data.victim.email;
                            if (res.data.data.victim.region && res.data.data.victim.region.c_luo != 0) {
                                this.victim.region = {
                                    'id': res.data.data.victim.region.c_luo,
                                    'label': res.data.data.victim.region.des_luo
                                };
                            }
                            if (res.data.data.victim.province && res.data.data.victim.province.c_luo != 0) {
                                this.victim.province = {
                                    'id': res.data.data.victim.province.c_luo,
                                    'label': res.data.data.victim.province.des_luo
                                };
                            }
                            if (res.data.data.victim.city && res.data.data.victim.city.c_luo != 0) {
                                this.victim.city = {
                                    'id': res.data.data.victim.city.c_luo,
                                    'label': res.data.data.victim.city.des_luo
                                };
                            }
                        }

                        if (res.data.data.witness) {
                            this.witness.witness_id = res.data.data.witness.id;
                            this.witness.date_update = res.data.data.witness.date_update;
                            if (res.data.data.witness.user) {
                                this.witness.user_update = res.data.data.witness.user.nome+' '+res.data.data.witness.user.cognome;
                            }
                            this.witness.type_reporter = res.data.data.report.type_reporter;
                            this.witness.type = res.data.data.witness.type;
                            if (res.data.data.witness.type == 'person') {
                                this.witness.lastname = res.data.data.witness.lastname;
                                this.witness.firstname = res.data.data.witness.firstname;
                                this.witness.gender_id = res.data.data.witness.gender_id ? res.data.data.witness.gender_id : '';
                                this.checkUserTextGender('witness');
                                this.witness.gender_user_text = res.data.data.witness.gender_user_text;
                                this.witness.birth_year = res.data.data.witness.birth_year;
                                if (res.data.data.witness.country && res.data.data.witness.country.c_luo != 0) {
                                    this.witness.country = {
                                        'id': res.data.data.witness.country.c_luo,
                                        'label': res.data.data.witness.country.des_luo
                                    };
                                }
                                if (res.data.data.witness.citizenship && res.data.data.witness.citizenship.c_luo != 0) {
                                    this.witness.citizenship = {
                                        'id': res.data.data.witness.citizenship.c_luo,
                                        'label': res.data.data.witness.citizenship.des_luo,
                                        'user_text': res.data.data.witness.citizenship.user_text
                                    };
                                }else{
                                    this.witness.citizenship = 0;
                                }
                                this.checkUserTextCitizenship('witness');
                                this.witness.citizenship_user_text = res.data.data.witness.citizenship_user_text;
                                this.witness.educational_qualification_id = res.data.data.witness.educational_qualification_id ? res.data.data.witness.educational_qualification_id : '';
                                this.checkUserTextEducationalQualification('witness');
                                this.witness.educational_qualification_user_text = res.data.data.witness.educational_qualification_user_text;
                                this.witness.religion_id = res.data.data.witness.religion_id ? res.data.data.witness.religion_id : '';
                                this.checkUserTextReligion('witness');
                                this.witness.religion_user_text = res.data.data.witness.religion_user_text;
                                this.witness.type_occupation_id = res.data.data.witness.type_occupation_id ? res.data.data.witness.type_occupation_id : '';
                                this.checkUserTextTypeOccupation('witness');
                                this.witness.type_occupation_user_text = res.data.data.witness.type_occupation_user_text;
                                this.witness.marital_status_id = res.data.data.witness.marital_status_id ? res.data.data.witness.marital_status_id : '';
                                this.checkUserTextMaritalStatus('witness');
                                this.witness.marital_status_user_text = res.data.data.witness.marital_status_user_text;
                                this.witness.in_italy_from_year = res.data.data.witness.in_italy_from_year;
                                this.witness.residency_permit_id = res.data.data.witness.residency_permit_id ? res.data.data.witness.residency_permit_id : '';
                                this.checkUserTextResidencyPermit('witness');
                                this.witness.residency_permit_user_text = res.data.data.witness.residency_permit_user_text;
                                if (res.data.data.witness.lives_with.length > 0) {
                                    for (i = 0; i < res.data.data.witness.lives_with.length; ++i) {
                                        this.witness.lives_with[res.data.data.witness.lives_with[i]] = true;
                                    }
                                }
                                this.witness.telephone = res.data.data.witness.telephone;
                                this.witness.mobile = res.data.data.witness.mobile;
                                this.witness.email = res.data.data.witness.email;
                                if (res.data.data.witness.region && res.data.data.witness.region.c_luo != 0) {
                                    this.witness.region = {
                                        'id': res.data.data.witness.region.c_luo,
                                        'label': res.data.data.witness.region.des_luo
                                    };
                                }
                                if (res.data.data.witness.province && res.data.data.witness.province.c_luo != 0) {
                                    this.witness.province = {
                                        'id': res.data.data.witness.province.c_luo,
                                        'label': res.data.data.witness.province.des_luo
                                    };
                                }
                                if (res.data.data.witness.city && res.data.data.witness.city.c_luo != 0) {
                                    this.witness.city = {
                                        'id': res.data.data.witness.city.c_luo,
                                        'label': res.data.data.witness.city.des_luo
                                    };
                                }
                            } else if (res.data.data.witness.type == 'business') {
                                this.witness.business_name = res.data.data.witness.business_name;
                                this.witness.piva = res.data.data.witness.piva;
                                if (res.data.data.witness.region_legal && res.data.data.witness.region_legal.c_luo != 0) {
                                    this.witness.region_legal = {
                                        'id': res.data.data.witness.region_legal.c_luo,
                                        'label': res.data.data.witness.region_legal.des_luo
                                    };
                                }
                                if (res.data.data.witness.province_legal && res.data.data.witness.province_legal.c_luo != 0) {
                                    this.witness.province_legal = {
                                        'id': res.data.data.witness.province_legal.c_luo,
                                        'label': res.data.data.witness.province_legal.des_luo
                                    };
                                }
                                if (res.data.data.witness.city_legal && res.data.data.witness.city_legal.c_luo != 0) {
                                    this.witness.city_legal = {
                                        'id': res.data.data.witness.city_legal.c_luo,
                                        'label': res.data.data.witness.city_legal.des_luo
                                    };
                                }
                                this.witness.address_legal = res.data.data.witness.address_legal;
                                if (res.data.data.witness.region_operational && res.data.data.witness.region_operational.c_luo != 0) {
                                    this.witness.region_operational = {
                                        'id': res.data.data.witness.region_operational.c_luo,
                                        'label': res.data.data.witness.region_operational.des_luo
                                    };
                                }
                                if (res.data.data.witness.province_operational && res.data.data.witness.province_operational.c_luo != 0) {
                                    this.witness.province_operational = {
                                        'id': res.data.data.witness.province_operational.c_luo,
                                        'label': res.data.data.witness.province_operational.des_luo
                                    };
                                }
                                if (res.data.data.witness.city_operational && res.data.data.witness.city_operational.c_luo != 0) {
                                    this.witness.city_operational = {
                                        'id': res.data.data.witness.city_operational.c_luo,
                                        'label': res.data.data.witness.city_operational.des_luo
                                    };
                                }
                                this.witness.address_operational = res.data.data.witness.address_operational;
                                this.witness.legal_representative = res.data.data.witness.legal_representative;
                                this.witness.telephone_legal = res.data.data.witness.telephone_legal;
                                this.witness.mobile_legal = res.data.data.witness.mobile_legal;
                                this.witness.email_legal = res.data.data.witness.email_legal;
                                this.witness.operational_contact = res.data.data.witness.operational_contact;
                                this.witness.telephone_operational = res.data.data.witness.telephone_operational;
                                this.witness.mobile_operational = res.data.data.witness.mobile_operational;
                                this.witness.email_operational = res.data.data.witness.email_operational;
                            }
                        }
                        
                        this.loadedVictim = JSON.stringify(this.victim);
                        this.loadedWitness = JSON.stringify(this.witness);
                        this.victimChanged = false;
                        this.witnessChanged = false;

                        this.loadedHolder = JSON.stringify(this.reportHolder);
                        this.holderChanged = false;

                        if(res.data.data.report.interview_id){
                            this.interviewData.idInterview = res.data.data.report.interview_id;
                            this.loadInterview(this.interviewData.idInterview);
                        }else{
                            this.loadSurvey();
                        }

                        this.history = res.data.data.history;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        changeWitnessType: function(){
            this.witness.lastname = '';
            this.witness.firstname = '';
            this.witness.gender_id = '';
            this.witness.gender_user_text = '';
            this.witness.birth_year =  '';
            this.witness.country =  0;
            this.witness.citizenship = 0;
            this.witness.citizenship_user_text = '';
            this.witness.educational_qualification_id = '';
            this.witness.educational_qualification_user_text = '';
            this.witness.religion_id = '';
            this.witness.religion_user_text = '';
            this.witness.type_occupation_id = '';
            this.witness.type_occupation_user_text = '';
            this.witness.marital_status_id = '';
            this.witness.marital_status_user_text = '';
            this.witness.in_italy_from_year =  '';
            this.witness.residency_permit_id = '';
            this.witness.residency_permit_user_text = '';
            this.witness.lives_with =  {
                mother: false,
                father: false,
                partner: false,
                son: false,
                brother: false,
                other_relatives: false,
                none: false,
                other_non_relatives: false
            };
            this.witness.telephone =  '';
            this.witness.mobile =  '';
            this.witness.email =  '';
            this.witness.region =  0;
            this.witness.province =  0;
            this.witness.city =  0;
            this.witness.business_name = '';
            this.witness.piva = '';
            this.witness.region_legal = 0;
            this.witness.province_legal = 0;
            this.witness.city_legal = 0;
            this.witness.address_legal = '';
            this.witness.region_operational = 0;
            this.witness.province_operational = 0;
            this.witness.city_operational = 0;
            this.witness.address_operational = '';
            this.witness.legal_representative = '';
            this.witness.telephone_legal = '';
            this.witness.mobile_legal = '';
            this.witness.email_legal = '';
            this.witness.operational_contact = '';
            this.witness.telephone_operational = '';
            this.witness.mobile_operational = '';
            this.witness.email_operational = '';
        },

        checkFormVictim: function(){
            
            if (this.reportHolder.holder == 'nodo' && !this.reportHolder.node) {
                alert('Selezionare un nodo.');
            } else {

                var errors = false;

                if (this.victim.lastname == "" || this.victim.lastname == null){
                    errors = true;
                }

                if (this.victim.firstname == "" || this.victim.firstname == null){
                    errors = true;
                }

                if (this.victim.gender_id == "" || this.victim.gender_id == null){
                    errors = true;
                }

                if (this.victim.birth_year == "" || this.victim.birth_year == null){
                    errors = true;
                }

                if (this.victim.country == "" || this.victim.country == null){
                    errors = true;
                }

                if (this.victim.citizenship == "" || this.victim.citizenship == null){
                    errors = true;
                }

                if(errors){
                    alert('Si prega di compilare tutti i campi obbligatori.');
                    return false;
                }else{
                    this.saveVictim();
                }
            }

        },

        saveVictim: function(){

            let params = new URLSearchParams();

            params.append('report_id', this.reportId);
            if (this.reportHolder.holder == 'nodo' && this.reportHolder.node) {
                params.append('node_id', this.reportHolder.node.id);
            }
            
            $.each(this.victim, function(index, element) {
                if (index == 'lives_with') {
                    params.append(index, JSON.stringify(element));
                } else if (index == 'country' || index == 'citizenship' || index == 'region' || index == 'province' || index == 'city') {
                    if (element) {
                        params.append(index, element.id);
                    } else {
                        params.append(index, element);
                    }
                } else {
                    params.append(index, element);
                }
            });

            axios.post(pathServer + 'reports/ws/saveVictim', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        this.reportId = res.data.data.report.id;
                        this.reportCode = res.data.data.report.province_code + res.data.data.report.code;
                        this.victim.victim_id = res.data.data.victim;
                        this.loadedVictim = JSON.stringify(this.victim);
                        this.victimChanged = false;
                        this.loadedHolder = JSON.stringify(this.reportHolder);
                        this.holderChanged = false;
                        this.history = res.data.data.history;
                        alert(res.data.msg);
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        },

        checkFormWitness: function(){

            if (this.reportHolder.holder == 'nodo' && !this.reportHolder.node) {
                alert('Selezionare un nodo.');
            } else {
                var errors = false;

                if (this.witness.type_reporter == "" || this.witness.type_reporter == null){
                    errors = true;
                }

                if (this.witness.type == "" || this.witness.type == null){
                    errors = true;
                }

                if (this.witness.type == 'person') {
                    if (this.witness.lastname == "" || this.witness.lastname == null){
                        errors = true;
                    }
        
                    if (this.witness.firstname == "" || this.witness.firstname == null){
                        errors = true;
                    }
        
                    if (this.witness.gender_id == "" || this.witness.gender_id == null){
                        errors = true;
                    }

                    if (this.witness.birth_year == "" || this.witness.birth_year == null){
                        errors = true;
                    }
    
                    if (this.witness.country == "" || this.witness.country == null){
                        errors = true;
                    }
    
                    if (this.witness.citizenship == "" || this.witness.citizenship == null){
                        errors = true;
                    }
                } else if (this.witness.type == 'business') {
                    if (this.witness.business_name == "" || this.witness.business_name == null){
                        errors = true;
                    }
                }

                if(errors){
                    alert('Si prega di compilare tutti i campi obbligatori.');
                    return false;
                }else{
                    this.saveWitness();
                }
            }

        },

        saveWitness: function(){

            let params = new URLSearchParams();

            params.append('report_id', this.reportId);
            if (this.reportHolder.holder == 'nodo' && this.reportHolder.node) {
                params.append('node_id', this.reportHolder.node.id);
            }
            
            $.each(this.witness, function(index, element) {
                if (index == 'lives_with') {
                    params.append(index, JSON.stringify(element));
                } else if (index == 'country' || index == 'citizenship' || index == 'region' || index == 'province' || index == 'city' ||
                        index == 'region_legal' || index == 'province_legal' || index == 'city_legal' ||
                        index == 'region_operational' || index == 'province_operational' || index == 'city_operational') {
                    if (element) {
                        params.append(index, element.id);
                    } else {
                        params.append(index, element);
                    }
                } else {
                    params.append(index, element);
                }
            });

            axios.post(pathServer + 'reports/ws/saveWitness', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        if (this.reportId) {
                            this.reportId = res.data.data.report.id;
                            this.reportStatus = res.data.data.report.status;
                            this.reportCode = res.data.data.report.province_code + res.data.data.report.code;
                            this.witness.witness_id = res.data.data.witness;
                            this.loadedWitness = JSON.stringify(this.witness);
                            this.witnessChanged = false;
                            this.loadedHolder = JSON.stringify(this.reportHolder);
                            this.holderChanged = false;
                            this.history = res.data.data.history;
                            alert(res.data.msg);
                        } else {
                            alert(res.data.msg);
                            this.witnessChanged = false;
                            this.holderChanged = false;
                            window.location.replace(pathServer + 'reports/reports/edit?report=' + res.data.data.report.id);
                        }
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        },

        searchNode: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'reports/ws/searchNode/'+search)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.nodes = res.data.data; 
                        loading(false);
                    } else {
                        this.nodes = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.nodes = [];
            }
        },

        searchCountry: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'reports/ws/searchCountry/'+search)
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

        searchCitizenship: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'reports/ws/searchCitizenship/'+search)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.citizenships = res.data.data; 
                        loading(false);
                    } else {
                        this.citizenships = [];
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

        searchRegion: function(search, loading){
            if(search != ''){
                loading(true);
                axios.get(pathServer + 'reports/ws/searchRegion/'+search)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.regions = res.data.data; 
                        loading(false);
                    } else {
                        this.regions = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.regions = [];
            }
        },

        searchProvince: function(search, loading, type){
            if(search != ''){
                loading(true);
                var region_id = 0;
                switch(type){
                    case 'victim':
                        region_id = this.victim.region.id;
                        break;
                    case 'witness_person':
                        region_id = this.witness.region.id;
                        break;
                    case 'witness_legal':
                        region_id = this.witness.region_legal.id;
                        break;
                    case 'witness_operational':
                        region_id = this.witness.region_operational.id;
                        break;
                    case 'anagrafica_victim':
                        region_id = this.anagrafica.region.id;
                        break;
                    case 'anagrafica_witness_legal':
                        region_id = this.anagrafica.region_legal.id;
                        break;
                    case 'anagrafica_witness_operational':
                        region_id = this.anagrafica.region_operational.id;
                        break;
                }
                axios.get(pathServer + 'reports/ws/searchProvince/'+search+'/'+region_id)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.provinces = res.data.data; 
                        loading(false);
                    } else {
                        this.provinces = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.provinces = [];
            }
        },

        searchCity: function(search, loading, type){
            if(search != ''){
                loading(true);
                var province_id = 0;
                switch(type){
                    case 'victim':
                        province_id = this.victim.province.id;
                        break;
                    case 'witness_person':
                        province_id = this.witness.province.id;
                        break;
                    case 'witness_legal':
                        province_id = this.witness.province_legal.id;
                        break;
                    case 'witness_operational':
                        province_id = this.witness.province_operational.id;
                        break;
                    case 'anagrafica_victim':
                        province_id = this.anagrafica.province.id;
                        break;
                    case 'anagrafica_witness_legal':
                        province_id = this.anagrafica.province_legal.id;
                        break;
                    case 'anagrafica_witness_operational':
                        province_id = this.anagrafica.province_operational.id;
                        break;
                }
                axios.get(pathServer + 'reports/ws/searchCity/'+search+'/'+province_id)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.cities = res.data.data; 
                        loading(false);
                    } else {
                        this.cities = [];
                        loading(false);
                    }
                }).catch(error => {
                    console.log(error);
                    loading(false);
                });
            }else{
                this.cities = [];
            }
        },

        checkAnagrafica: function(e, type, field) {
            axios.get(pathServer + 'reports/ws/checkAnagrafica/'+type+'/'+field+'/'+e.target.value)
                .then(res => {  
                    if (res.data.response == 'OK') { 
                        if (res.data.data) {
                            this.anagrafica.type_anagrafica = type;
                            this.anagrafica[type+'_id'] = res.data.data.id;
                            this.anagrafica.date_update = res.data.data.date_update;
                            if (res.data.data.user) {
                                this.anagrafica.user_update = res.data.data.user.nome+' '+res.data.data.user.cognome;
                            }
                            if(type == 'witness'){
                                this.anagrafica.type_reporter = this.witness.type_reporter;
                                this.anagrafica.type = res.data.data.type;
                            }
                            if (type == 'victim' || (type == 'witness' && field != 'business_name')) {
                                this.anagrafica.lastname = res.data.data.lastname;
                                this.anagrafica.firstname = res.data.data.firstname;
                                this.anagrafica.gender_id = res.data.data.gender_id ? res.data.data.gender_id : '';
                                this.checkUserTextGender('anagrafica');
                                this.anagrafica.gender_user_text = res.data.data.gender_user_text;
                                this.anagrafica.birth_year = res.data.data.birth_year;
                                if (res.data.data.country && res.data.data.country.c_luo != 0) {
                                    this.anagrafica.country = {
                                        'id': res.data.data.country.c_luo,
                                        'label': res.data.data.country.des_luo
                                    };
                                }else{
                                    this.anagrafica.country = 0;
                                }
                                if (res.data.data.citizenship && res.data.data.citizenship.c_luo != 0) {
                                    this.anagrafica.citizenship = {
                                        'id': res.data.data.citizenship.c_luo,
                                        'label': res.data.data.citizenship.des_luo,
                                        'user_text': res.data.data.citizenship.user_text
                                    };
                                }else{
                                    this.anagrafica.citizenship = 0;
                                }
                                this.checkUserTextCitizenship('anagrafica');
                                this.anagrafica.citizenship_user_text = res.data.data.citizenship_user_text;
                                this.anagrafica.educational_qualification_id = res.data.data.educational_qualification_id ? res.data.data.educational_qualification_id : '';
                                this.checkUserTextEducationalQualification('anagrafica');
                                this.anagrafica.educational_qualification_user_text = res.data.data.educational_qualification_user_text;
                                this.anagrafica.religion_id = res.data.data.religion_id ? res.data.data.religion_id : '';
                                this.checkUserTextReligion('anagrafica');
                                this.anagrafica.religion_user_text = res.data.data.religion_user_text;
                                this.anagrafica.type_occupation_id = res.data.data.type_occupation_id ? res.data.data.type_occupation_id : '';
                                this.checkUserTextTypeOccupation('anagrafica');
                                this.anagrafica.type_occupation_user_text = res.data.data.type_occupation_user_text;
                                this.anagrafica.marital_status_id = res.data.data.marital_status_id ? res.data.data.marital_status_id : '';
                                this.checkUserTextMaritalStatus('anagrafica');
                                this.anagrafica.marital_status_user_text = res.data.data.marital_status_user_text;
                                this.anagrafica.in_italy_from_year = res.data.data.in_italy_from_year;
                                this.anagrafica.residency_permit_id = res.data.data.residency_permit_id ? res.data.data.residency_permit_id : '';
                                this.checkUserTextResidencyPermit('anagrafica');
                                this.anagrafica.residency_permit_user_text = res.data.data.residency_permit_user_text;
                                this.anagrafica.lives_with = {
                                    mother: false,
                                    father: false,
                                    partner: false,
                                    son: false,
                                    brother: false,
                                    other_relatives: false,
                                    none: false,
                                    other_non_relatives: false
                                };
                                if (res.data.data.lives_with.length > 0) {
                                    for (i = 0; i < res.data.data.lives_with.length; ++i) {
                                        this.anagrafica.lives_with[res.data.data.lives_with[i]] = true;
                                    }
                                }
                                this.anagrafica.telephone = res.data.data.telephone;
                                this.anagrafica.mobile = res.data.data.mobile;
                                this.anagrafica.email = res.data.data.email;
                                if (res.data.data.region && res.data.data.region.c_luo != 0) {
                                    this.anagrafica.region = {
                                        'id': res.data.data.region.c_luo,
                                        'label': res.data.data.region.des_luo
                                    };
                                }else{
                                    this.anagrafica.region = 0;
                                }
                                if (res.data.data.province && res.data.data.province.c_luo != 0) {
                                    this.anagrafica.province = {
                                        'id': res.data.data.province.c_luo,
                                        'label': res.data.data.province.des_luo
                                    };
                                }else{
                                    this.anagrafica.province = 0;
                                }
                                if (res.data.data.city && res.data.data.city.c_luo != 0) {
                                    this.anagrafica.city = {
                                        'id': res.data.data.city.c_luo,
                                        'label': res.data.data.city.des_luo
                                    };
                                }else{
                                    this.anagrafica.city = 0;
                                }
                            } else if (type == 'witness' && field == 'business_name') {
                                this.anagrafica.business_name = res.data.data.business_name;
                                this.anagrafica.piva = res.data.data.piva;
                                if (res.data.data.region_legal && res.data.data.region_legal.c_luo != 0) {
                                    this.anagrafica.region_legal = {
                                        'id': res.data.data.region_legal.c_luo,
                                        'label': res.data.data.region_legal.des_luo
                                    };
                                }else{
                                    this.anagrafica.region_legal = 0;
                                }
                                if (res.data.data.province_legal && res.data.data.province_legal.c_luo != 0) {
                                    this.anagrafica.province_legal = {
                                        'id': res.data.data.province_legal.c_luo,
                                        'label': res.data.data.province_legal.des_luo
                                    };
                                }else{
                                    this.anagrafica.province_legal = 0;
                                }
                                if (res.data.data.city_legal && res.data.data.city_legal.c_luo != 0) {
                                    this.anagrafica.city_legal = {
                                        'id': res.data.data.city_legal.c_luo,
                                        'label': res.data.data.city_legal.des_luo
                                    };
                                }else{
                                    this.anagrafica.city_legal = 0;
                                }
                                this.anagrafica.address_legal = res.data.data.address_legal;
                                if (res.data.data.region_operational && res.data.data.region_operational.c_luo != 0) {
                                    this.anagrafica.region_operational = {
                                        'id': res.data.data.region_operational.c_luo,
                                        'label': res.data.data.region_operational.des_luo
                                    };
                                }else{
                                    this.anagrafica.region_operational = 0;
                                }
                                if (res.data.data.province_operational && res.data.data.province_operational.c_luo != 0) {
                                    this.anagrafica.province_operational = {
                                        'id': res.data.data.province_operational.c_luo,
                                        'label': res.data.data.province_operational.des_luo
                                    };
                                }else{
                                    this.anagrafica.province_operational = 0;
                                }
                                if (res.data.data.city_operational && res.data.data.city_operational.c_luo != 0) {
                                    this.anagrafica.city_operational = {
                                        'id': res.data.data.city_operational.c_luo,
                                        'label': res.data.data.city_operational.des_luo
                                    };
                                }else{
                                    this.anagrafica.city_operational = 0;
                                }
                                this.anagrafica.address_operational = res.data.data.address_operational;
                                this.anagrafica.legal_representative = res.data.data.legal_representative;
                                this.anagrafica.telephone_legal = res.data.data.telephone_legal;
                                this.anagrafica.mobile_legal = res.data.data.mobile_legal;
                                this.anagrafica.email_legal = res.data.data.email_legal;
                                this.anagrafica.operational_contact = res.data.data.operational_contact;
                                this.anagrafica.telephone_operational = res.data.data.telephone_operational;
                                this.anagrafica.mobile_operational = res.data.data.mobile_operational;
                                this.anagrafica.email_operational = res.data.data.email_operational;
                            }

                            $('#modalAnagrafica').modal('show');
                        }
                    } else {
                        console.log(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        loadAnagrafica: function(type) {
            if(type == 'victim'){
                this.victim = $.extend(true, {}, this.anagrafica);
            }else if(type == 'witness'){
                this.witness = $.extend(true, {}, this.anagrafica);
            }

            this.checkUserTextGender(type);
            this.checkUserTextCitizenship(type);
            this.checkUserTextEducationalQualification(type);
            this.checkUserTextReligion(type);
            this.checkUserTextTypeOccupation(type);
            this.checkUserTextMaritalStatus(type);
            this.checkUserTextResidencyPermit(type);

            $('#modalAnagrafica').modal('hide');

            this.anagrafica.type_anagrafica = '';
            this.anagrafica.date_update = '';
            this.anagrafica.user_update = '';
            this.anagrafica.type_reporter = '';
            this.anagrafica.type = '';
            this.anagrafica.lastname = '';
            this.anagrafica.firstname = '';
            this.anagrafica.gender_id =  '';
            this.anagrafica.gender_user_text = '';
            this.anagrafica.birth_year =  '';
            this.anagrafica.country =  0;
            this.anagrafica.citizenship = 0;
            this.anagrafica.citizenship_user_text = '';
            this.anagrafica.educational_qualification_id = '';
            this.anagrafica.educational_qualification_user_text = '';
            this.anagrafica.religion_id = '';
            this.anagrafica.religion_user_text = '';
            this.anagrafica.type_occupation_id = '';
            this.anagrafica.type_occupation_user_text = '';
            this.anagrafica.marital_status_id = '';
            this.anagrafica.marital_status_user_text = '';
            this.anagrafica.in_italy_from_year =  '';
            this.anagrafica.residency_permit_id = '';
            this.anagrafica.residency_permit_user_text = '';
            this.anagrafica.lives_with =  {
                mother: false,
                father: false,
                partner: false,
                son: false,
                brother: false,
                other_relatives: false,
                none: false,
                other_non_relatives: false
            };
            this.anagrafica.telephone =  '';
            this.anagrafica.mobile =  '';
            this.anagrafica.email =  '';
            this.anagrafica.region =  0;
            this.anagrafica.province =  0;
            this.anagrafica.city =  0;
            this.anagrafica.business_name = '';
            this.anagrafica.piva = '';
            this.anagrafica.region_legal = 0;
            this.anagrafica.province_legal = 0;
            this.anagrafica.city_legal = 0;
            this.anagrafica.address_legal = '';
            this.anagrafica.region_operational = 0;
            this.anagrafica.province_operational = 0;
            this.anagrafica.city_operational = 0;
            this.anagrafica.address_operational = '';
            this.anagrafica.legal_representative = '';
            this.anagrafica.telephone_legal = '';
            this.anagrafica.mobile_legal = '';
            this.anagrafica.email_legal = '';
            this.anagrafica.operational_contact = '';
            this.anagrafica.telephone_operational = '';
            this.anagrafica.mobile_operational = '';
            this.anagrafica.email_operational = '';
        },

        loadInterview: function(id){
            if (this.preview) {
                var url = pathServer + 'surveys/ws/getInterviewForNewSurvey/' + id;
            } else {
                var url = pathServer + 'surveys/ws/getInterview/' + id;
            }

            axios.get(url)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.idSurvey = res.data.data.id_survey;
                        this.surveyVersion = res.data.data.survey_version;
                        this.interviewData.date_update = res.data.data.date_update;
                        this.interviewData.user_update = res.data.data.user_update;
                        this.interviewData.title = res.data.data.title;
                        this.interviewData.subtitle = res.data.data.subtitle;
                        this.interviewData.description = res.data.data.description;
                        this.interviewData.status = res.data.data.status;
                        this.interviewData.version = res.data.data.version;
                        this.interviewData.items = res.data.data.answers;

                        this.loadedInterview = JSON.stringify(this.interviewData);
                        this.interviewChanged = false;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        loadSurvey: function(){
            axios.get(pathServer + 'surveys/ws/getSurvey/')
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.idSurvey = res.data.data.id;
                        this.surveyVersion = res.data.data.version;
                        this.interviewData.title = res.data.data.title;
                        this.interviewData.subtitle = res.data.data.subtitle;
                        this.interviewData.description = res.data.data.description;
                        this.interviewData.version = res.data.data.version;
                        this.interviewData.items = res.data.data.chapters;

                        this.loadedInterview = JSON.stringify(this.interviewData);
                        this.interviewChanged = false;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                }); 
        },

        saveInterview: function(){
            if (this.witness.witness_id) {

                notValid = this.checkInterviewRequiredQuestions(this.interviewData.items, false);

                if (notValid) {
                    alert("Si prega di compilare tutti i campi obbligatori.");
                } else {
                    let params = new URLSearchParams();

                    params.append('report_id', this.reportId);
                    if (this.reportHolder.holder == 'nodo' && this.reportHolder.node) {
                        params.append('node_id', this.reportHolder.node.id);
                    }

                    if(this.interviewData.idInterview){
                        params.append('idInterview', this.interviewData.idInterview);
                    }
                    params.append('id_survey', this.idSurvey);
                    params.append('title', this.interviewData.title);
                    params.append('subtitle', this.interviewData.subtitle);
                    params.append('description', this.interviewData.description);
                    params.append('version', this.interviewData.version);
                    params.append('answers', JSON.stringify(this.interviewData.items));
                    params.append('not_valid', notValid);

                    axios.post(pathServer + 'surveys/ws/saveInterview', params)
                        .then(res => {
                            if (res.data.response == 'OK') {
                                if (this.preview) {
                                    window.location.href = pathServer + "reports/reports/edit?report=" + this.reportId;
                                } else {
                                    this.reportId = res.data.data.report.id;
                                    this.reportCode = res.data.data.report.province_code + res.data.data.report.code;
                                    this.interviewData.idInterview = res.data.data.interview;
                                    this.loadedInterview = JSON.stringify(this.interviewData);
                                    this.interviewChanged = false;
                                    this.loadedHolder = JSON.stringify(this.reportHolder);
                                    this.holderChanged = false;
                                    alert(res.data.msg);
                                }
                            } else {
                                alert(res.data.msg);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            } else {
                alert("Attenzione! Per poter salvare la scheda caso è necessario aver prima salvato l'anagrafica del segnalante.");
            }

        },

        updateConditionedQuestions: function(question) {
            var updateQuestionsVisibility = (items, question_id, question_answer, question_type) => {
                items.forEach((item) => {
                    item.questions.forEach((q) => {
                        if(q.connected_to == question_id){
                            if((question_type == 'yes_no' && q.show_on == question_answer) || (question_type == 'single_choice' && q.show_on == question_answer.check)){
                                q.visible = true;
                            }else{
                                q.visible = false;

                                switch(q.type){
                                    case 'short_answer':
                                    case 'free_answer':
                                    case 'yes_no':
                                    case 'date':
                                    case 'number':
                                        q.answer = '';
                                        break;
                                    case 'table':
                                        q.answer = [];
                                        break;
                                    case 'single_choice':
                                        q.answer = {
                                            "check": "",
                                            "extensions": []
                                        };
                                             
                                        q.options.forEach(function(){
                                            q.answer.extensions.push('');
                                        });
                                        break;
                                    case 'multiple_choice':
                                        q.options.forEach(function(){
                                            q.answer.push(
                                                {
                                                    'check': false,
                                                    'extended': ''
                                                }
                                            );
                                        });
                                        break;
                                }

                                if(q.type == 'yes_no' || q.type == 'single_choice'){
                                    updateQuestionsVisibility(this.interviewData.items, q.id, q.answer, q.type);
                                }
                            }
                        }
                    })

                    if(item.items.length > 0){
                        updateQuestionsVisibility(item.items, question_id, question_answer, question.type);
                    }
                });
            }

            updateQuestionsVisibility(this.interviewData.items, question.id, question.answer, question.type);
        },

        updateInterviewStructure: function() {
            window.location.href = pathServer + "reports/reports/edit?report=" + this.reportId + "&preview=1";
        },

        checkUserTextGender: function(type) {
            var selected_option = $('#'+type+'Gender option[value="'+this[type].gender_id+'"]');
            if (selected_option.attr("data-user-text")) {
                $('#'+type+'Gender').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'Gender').parent().find('.select-user-text').hide();
                this[type].gender_user_text = '';
            }
        },

        checkUserTextCitizenship: function(type) {
            if (this[type].citizenship && this[type].citizenship.user_text) {
                $('#'+type+'Citizenship').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'Citizenship').parent().find('.select-user-text').hide();
                this[type].citizenship_user_text = '';
            }
        },

        checkUserTextEducationalQualification: function(type) {
            var selected_option = $('#'+type+'EducationalQualification option[value="'+this[type].educational_qualification_id+'"]');
            if (selected_option.attr("data-user-text")) {
                $('#'+type+'EducationalQualification').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'EducationalQualification').parent().find('.select-user-text').hide();
                this[type].educational_qualification_user_text = '';
            }
        },

        checkUserTextReligion: function(type) {
            var selected_option = $('#'+type+'Religion option[value="'+this[type].religion_id+'"]');
            if (selected_option.attr("data-user-text")) {
                $('#'+type+'Religion').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'Religion').parent().find('.select-user-text').hide();
                this[type].religion_user_text = '';
            }
        },

        checkUserTextTypeOccupation: function(type) {
            var selected_option = $('#'+type+'TypeOccupation option[value="'+this[type].type_occupation_id+'"]');
            if (selected_option.attr("data-user-text")) {
                $('#'+type+'TypeOccupation').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'TypeOccupation').parent().find('.select-user-text').hide();
                this[type].type_occupation_user_text = '';
            }
        },

        checkUserTextMaritalStatus: function(type) {
            var selected_option = $('#'+type+'MaritalStatus option[value="'+this[type].marital_status_id+'"]');
            if (selected_option.attr("data-user-text")) {
                $('#'+type+'MaritalStatus').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'MaritalStatus').parent().find('.select-user-text').hide();
                this[type].marital_status_user_text = '';
            }
        },

        checkUserTextResidencyPermit: function(type) {
            var selected_option = $('#'+type+'ResidencyPermit option[value="'+this[type].residency_permit_id+'"]');
            if (selected_option.attr("data-user-text")) {
                $('#'+type+'ResidencyPermit').parent().find('.select-user-text').show();
            } else {
                $('#'+type+'ResidencyPermit').parent().find('.select-user-text').hide();
                this[type].residency_permit_user_text = '';
            }
        },

        checkInterviewRequiredQuestions: function(items, notValid) {
            items.every(item => {
                item.questions.every(question => {
                    if(question.required && question.visible){
                        switch(question.type){
                            case 'short_answer':
                            case 'free_answer':
                            case 'yes_no':
                            case 'date':
                            case 'number':
                                if(question.answer === ''){
                                    notValid = true;
                                }
                                break;
                            case 'table':
                                if(question.answer.length == 0){
                                    notValid = true;
                                }
                                break;
                            case 'single_choice':
                                if(question.answer.check === ''){
                                    notValid = true;
                                };
                                break;
                            case 'multiple_choice':
                                var checked = false;
                                question.options.forEach(function(){
                                    if(question.answer.check !== ''){
                                        checked = true;
                                    }
                                });
                                if(!checked){
                                    notValid = true;
                                }
                                break;
                        }
                    }

                    if (notValid) {
                        return false;
                    }

                    return true;
                });

                if (!notValid && item.items.length > 0) {
                    notValid = this.checkInterviewRequiredQuestions(item.items, notValid);
                }

                if (notValid) {
                    return false;
                }
                
                return true;
            });

            return notValid;
        },
        
        openModalCloseReport: function() {
            if (confirm('Si è sicuri di voler chiudere il caso?')) {
                this.close_report.date = '';
                this.close_report.motivation = '';
                this.close_report.outcome = '';

                $('#modalCloseReport').modal('show');
            }
        },

        closeReport: function() {
            var errors = false;

            if (this.close_report.date == '' || this.close_report.date == null){
                errors = true;
            }

            if (this.close_report.motivation == '' || this.close_report.motivation == null){
                errors = true;
            }

            if (this.close_report.outcome == '' || this.close_report.outcome == null){
                errors = true;
            }

            if(errors){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{
                let params = new URLSearchParams();
                params.append('report_id', this.reportId);
                params.append('date', this.close_report.date);
                params.append('motivation', this.close_report.motivation);
                params.append('outcome', this.close_report.outcome);

                axios.post(pathServer + 'reports/ws/closeReport', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        location.reload();
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },

        openModalReopenReport: function() {
            if (confirm('Si è sicuri di voler riaprire il caso?')) {
                this.reopenMotivation = '';

                $('#modalReopenReport').modal('show');
            }
        },

        reopenReport: function() {
            var errors = false;

            if (this.reopenMotivation == '' || this.reopenMotivation == null){
                errors = true;
            }

            if(errors){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{
                let params = new URLSearchParams();
                params.append('report_id', this.reportId);
                params.append('motivation', this.reopenMotivation);

                axios.post(pathServer + 'reports/ws/reopenReport', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        location.reload();
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },

        openModalTransferReport: function() {
            if (confirm('Si è sicuri di voler trasferire il caso?')) {
                this.transferMotivation = '';

                $('#modalTransferReport').modal('show');
            }
        },

        transferReport: function() {
            var errors = false;

            if (this.transferMotivation == '' || this.transferMotivation == null){
                errors = true;
            }

            if(errors){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{
                let params = new URLSearchParams();
                params.append('report_id', this.reportId);
                params.append('motivation', this.transferMotivation);

                axios.post(pathServer + 'reports/ws/transferReport', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        location.reload();
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        },

        confirmTransferReport: function() {
            if (confirm('Si è sicuri di voler confermare il trasferimento del caso?')) {
                let params = new URLSearchParams();
                params.append('report_id', this.reportId);
                if (this.reportHolder.holder == 'nodo' && this.reportHolder.node) {
                    params.append('node_id', this.reportHolder.node.id);
                } else {
                    params.append('node_id', '');
                }

                axios.post(pathServer + 'reports/ws/confirmTransferReport', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        this.loadedHolder = JSON.stringify(this.reportHolder);
                        this.holderChanged = false;
                        location.reload();
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
      
    filters: {

    }
    
});

var beforeunload = true; 
window.addEventListener('beforeunload', function (e) { 
    if(beforeunload && (app.holderChanged || app.victimChanged || app.witnessChanged || app.interviewChanged)){
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    }else{
        beforeunload = true;
    }
});
