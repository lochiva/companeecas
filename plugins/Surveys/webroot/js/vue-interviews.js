Vue.component('v-select', VueSelect.VueSelect);

Vue.use(VueMaterial.default)

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
        'status'
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
    el: '#app-interviews',
    data: {
        idSurvey: 0,
        selectedPartner: null,
        selectedStructure: null,
        role: role,
        partners: [],
        gestori: [],
        structures: [],
        interviewData: {
            idInterview: 0,
            idPartner: 0,
            idStructure: 0,
            title: '',
            subtitle: '',
            description: '',
            status: '',
            items: []
        },
        loadedData: '',
        footerInViewport: true,
    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.idSurvey = url.searchParams.get("survey");

        if(url.searchParams.get("interview") != null){
            this.interviewData.idInterview = url.searchParams.get("interview");
        }

        if(url.searchParams.get("managentity") != null){
            this.interviewData.idPartner = url.searchParams.get("managentity");
        }

        if(url.searchParams.get("structure") != null){
            this.interviewData.idStructure = url.searchParams.get("structure");
        }

        if(this.interviewData.idInterview){
            this.loadInterview(this.interviewData.idInterview);
        }else{
            this.loadSurvey(this.idSurvey);
        }

        if(this.role == 'admin'){
            this.getPartners();
        }
    },
       
    methods: {
        loadInterview: function(id){
            axios.get(pathServer + 'surveys/ws/getInterview/' + id)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.interviewData.title = res.data.data.title;
                        this.interviewData.subtitle = res.data.data.subtitle;
                        this.interviewData.description = res.data.data.description;
                        this.interviewData.idPartner = res.data.data.id_azienda;
                        this.interviewData.idStructure = res.data.data.id_sede;
                        this.interviewData.status = res.data.data.status;
                        this.interviewData.items = res.data.data.answers;

                        this.loadedData = JSON.stringify(this.interviewData);
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },
        loadSurvey: function(id){
            axios.get(pathServer + 'surveys/ws/getSurvey/' + id)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.interviewData.title = res.data.data.title;
                        this.interviewData.subtitle = res.data.data.subtitle;
                        this.interviewData.description = res.data.data.description;
                        this.interviewData.items = res.data.data.chapters;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                }); 
        },
        getPartners: function(){

            axios.get(pathServer + 'surveys/ws/getPartners/')
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.partners = res.data.data; 
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });

        },
        getStructures: function(){

            axios.get(pathServer + 'surveys/ws/getStructuresInterview/' + this.interviewData.idPartner)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.structures = res.data.data; 
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });

        },
        setSelectedPartner: function(value){
            if(value !== null){ 
                this.selectedPartner = value;
                this.interviewData.idPartner = value.code;
                this.getStructures();
            }
        },
        setSelectedStructure: function(value){
            if(value !== null){ 
                this.selectedStructure = value;
                this.interviewData.idStructure = value.code;
            }
        },
        saveInterview: function(exit){
            if(this.role == 'admin' && this.interviewData.idPartner == 0){
                alert("Selezionare un'azienda per poter salvare l'intervista.");
                return false;
            /*}else if(this.role == 'admin' && this.interviewData.idGestore == 0){
                alert("Selezionare un ente gestore per poter salvare l'intervista.");
                return false;
            */}else if(this.role == 'admin' && this.interviewData.idStructure == 0){
                alert("Selezionare una sede per poter salvare l'intervista.");
                return false;
            }else{
                let params = new URLSearchParams();
                if(this.interviewData.idInterview){
                    params.append('idInterview', this.interviewData.idInterview);
                }
                params.append('id_survey', this.idSurvey);
                params.append('title', this.interviewData.title);
                params.append('subtitle', this.interviewData.subtitle);
                params.append('description', this.interviewData.description);
                params.append('id_azienda', this.interviewData.idPartner);
                //params.append('id_gestore', this.interviewData.idGestore);
                params.append('id_sede', this.interviewData.idStructure);
                params.append('answers', JSON.stringify(this.interviewData.items));

                var notValid = false;

                this.interviewData.items.forEach(function(item){
                    item.questions.forEach(function(question){ 
                        if(question.required){
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
                    });
                });

                params.append('not_valid', notValid);

                axios.post(pathServer + 'surveys/ws/saveInterview', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            if(exit){
                                if(this.role == 'admin'){
                                    window.location = pathServer + 'surveys/surveys/interviews/'+this.idSurvey;
                                }else{
                                    window.location = pathServer + 'surveys/surveys/interviews/'+this.idSurvey/*+'/'+this.interviewData.idGestore+'/'+this.interviewData.idStructure*/;
                                }
                            }else{
                                alert(res.data.msg);
                                if(!this.interviewData.idInterview){
                                    window.location = pathServer + 'surveys/surveys/answers?survey='+this.idSurvey+'&interview='+res.data.data;
                                }
                            }
                        } else {
                            alert(res.data.msg);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });

            }
        },
        checkInViewport: function(isInViewport) {
            this.footerInViewport = isInViewport;
        },
        setInterviewSigned: function(){
            if(confirm("Si è sicuri di voler impostare lo stato dell'ispezione a 'Firmata'? Diventerà disponibile solo più in lettura e non potrà essere modificata.")){
                let params = new URLSearchParams();
                params.append('idInterview', this.interviewData.idInterview);

                axios.post(pathServer + 'surveys/ws/setInterviewSigned', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            this.interviewData.status = 2;
                            alert(res.data.msg);
                        } else {
                            alert(res.data.msg);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
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
    },
      
    filters: {

    }
    
});

var beforeunload = true;
window.addEventListener('beforeunload', function (e) {
    if(beforeunload && (app.interviewData.idInterview === null || JSON.stringify(app.interviewData) !== app.loadedData)){
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    }else{
        beforeunload = true;
    }
});