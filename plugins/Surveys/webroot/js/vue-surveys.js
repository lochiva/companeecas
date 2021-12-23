Vue.component('v-select', VueSelect.VueSelect);

Vue.use(VueMaterial.default);

var nested = {
    template: '#template-draggable',
    name: 'nested-draggable',
    props: [
        'items',
        'item',
        'index',
        'parentitem',
        'label',
        'elements',
        'questions',
        'survey'
    ],
    data: function () {
        return {
            isOpen: false,
            editorInit: {
                resize: false, 
                height: 300, 
                language: 'it_IT', 
                branding: false, 
                plugins: ['image table'],
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                file_picker_callback: function(callback, value, meta) {
                    // svuoto l'input
                    $('#tinymce_upload').val('');
                    // Provide image and alt text for the image dialog
                    $('#tinymce_upload').off('change');
                    $('#tinymce_upload').trigger('click');
                    $('#tinymce_upload').on('change', function(){
                    // eseguo opportuni controlli sul file
                        file = this.files[0];
                        if(file === undefined){
                            return;
                        }
                        var fileType = file.type.substr(0,file.type.indexOf('/'));
                        
                        if(fileType != 'image'){
                            alert('Il file non è del formato corretto!');
                            return;
                        }
        
                        formData= new FormData(document.getElementById('tinymce_upload_form') );
                        // eseguo la chiamata ajax ache salva l'immagine, e di ritorno avrò l'url
                        $.ajax({
                            url: pathServer+'surveys/ws/saveImagePath/1',
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            success: function(data){
                                if(data.response == 'OK'){
                                    callback(data.data, {
                                    alt: ''
                                    });
                                }else{
                                    alert(data.msg);
                                }
                            },
        
                        });
        
                    });
                }
            }
        }
    },
    components: {
        'editor': Editor
    },
    mounted: function () {
        
    },
    methods: {
        toggle: function (item) {
            item.open = !item.open;
        },
        forceOpen: function () {
            this.isOpen = true;
        },
        showModalElements: function(params) { 
            this.$root.showModalElements(params);
        },
        showModalQuestions: function(params, event) { 
            this.$root.showModalQuestions(params, event);
        },
        removeQuestion: function(params) { 
            if(confirm("Si è sicuri di voler cancellare l'elemento?")){ 
                if(params.questions[params.index].type == 'yes_no'|| params.questions[params.index].type == 'single_choice'){

                    var isQuestionConditioning = function(items, question_id){
                        items.forEach(function(item){
                            item.questions.forEach(function(q){
                                if(q.connected_to == question_id){
                                    conditioning = true;
                                    return
                                }
                            })

                            if(conditioning){
                                return;
                            }
    
                            if(item.items.length > 0){
                                isQuestionConditioning(item.items, question_id);
                            }
                        });
                    }

                    var conditioning = false;

                    isQuestionConditioning(this.survey.items, params.questions[params.index].id);

                    var deleteQuestion = true;

                    if(conditioning){
                        deleteQuestion = confirm("La domanda condiziona la visibilità di una o più domande. Si è sicuri di volerla cancellare?");
                    }

                    if(deleteQuestion){
                        var conditioningQuestions = this.survey.conditioningQuestions;
                        conditioningQuestions.forEach(function(q, i){ 
                            if(q.question_id === params.questions[params.index].id){ 
                                conditioningQuestions.splice(i, 1);
                            }
                        });

                        var updateQuestions = function(items, question_id){
                            items.forEach(function(item){
                                item.questions.forEach(function(q){
                                    if(q.connected_to == question_id){
                                        q.conditioned = false;
                                        q.connected_to = '';
                                        q.show_on = '';
                                        q.visible = true;
                                    }
                                })
        
                                if(item.items.length > 0){
                                    updateQuestions(item.items, question_id);
                                }
                            });
                        }

                        updateQuestions(this.survey.items, params.questions[params.index].id);

                        params.questions.splice(params.index, 1);
                    }
                }else{
                    params.questions.splice(params.index, 1);
                }
            }
        },
        changeConditioningQuestion: function(params) { 
            this.survey.conditioningQuestions.forEach(function(q){ 
                if(q.question_id === params.question_id){
                    q.question = params.question;
                }
            });
        },
        changeConditioningOptions: function(params) { 
            this.survey.conditioningQuestions.forEach(function(q){ 
                if(q.question_id === params.question_id){
                    q.options = params.options;
                }
            });
        },
        addOptionSingle: function(params) { 
            params.options.push(
                {
                    'text': '',
                    'extended': false
                }
            ); 

            params.extensions.push(''); 
        },
        removeOptionSingle: function(params) { 
            if(confirm("Si è sicuri di voler cancellare l'opzione?")){
                params.options.splice(params.index, 1);
                params.extensions.splice(params.index, 1);
            }
        },
        addOptionMultiple: function(params) { 
            params.options.push(
                {
                    'text': '',
                    'extended': false
                }
            ); 

            params.answer.push(
                {
                    'check': false,
                    'extended': ''
                }
            ); 
        },
        removeOptionMultiple: function(params) { 
            if(confirm("Si è sicuri di voler cancellare l'opzione?")){
                params.options.splice(params.index, 1);
                params.answer.splice(params.index, 1);
            }
        },
        addHeaderTable: function(params) { 
            if(params.headers.length < 6){
                params.headers.push('');
            }else{
                alert("Attenzione! Numero massimo di intestazioni (6) raggiunto.");
            } 
        },
        removeHeaderTable: function(params) { 
            if(confirm("Si è sicuri di voler cancellare l'intestazione?")){
                params.headers.splice(params.index, 1);
            }
        },
        saveImagePath: function(params) { 
            var file = this.$refs['image'+params.index][0].files[0];

            let formData = new FormData();
            formData.append('file', file);
            formData.append('survey', params.idSurvey);
            axios.post(pathServer + 'surveys/ws/saveImagePath',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    } 
                )
                .then(res => {
                    if (res.data.response == 'OK') {
                        params.question.path = res.data.data;
                    } else {
                        $(this.$refs['image'+params.index][0]).val('');
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });           
        },
        viewImage: function(path) {
            window.open(pathServer + 'surveys/ws/viewImage/' + path);
        },
        deleteImage: function(question) {
            question.path = '';
        },
        showModalPreviewQuestion: function(params, event) { 
            this.$root.showModalPreviewQuestion(params, event);
        },
        updateShortLabels: function(question) { 
            this.$root.updateShortLabels(question);
        },
    }
};

var app = new Vue({
    el: '#app-surveys',
    data: {
        statuses: {},
        partners: [],
        selectedPartner: null,
        questions: questions,
        elements: elements,
        surveyData: {
            idSurvey: '',
            title: '',
            subtitle: '',
            description: '',
            status: 0,
            version: '',
            partners: [],
            items: [],
            conditioningQuestions:[],
        },
        currentItem: {},
        currentQuestion: {},
        connected_to: '',
        show_on: '',
        loadedData: '',
        footerInViewport: true,
        questionToPreview: {},
        standardTexts: [],
        selectedStandardText: {},
    },

    computed: {

    },

    components: {
        "nested-draggable" : nested
    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.surveyData.idSurvey = url.searchParams.get("survey");

        if(this.surveyData.idSurvey){
            this.loadSurvey(this.surveyData.idSurvey);
        }

        this.getSurveyStatuses();
        this.getPartners();

        this.getStandardTexts();

    },
       
    methods: {
        loadSurvey: function(id){
            axios.get(pathServer + 'surveys/ws/getSurvey/' + id)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.surveyData.title = res.data.data.title;
                        this.surveyData.subtitle = res.data.data.subtitle;
                        this.surveyData.description = res.data.data.description;
                        this.surveyData.status = res.data.data.status;
                        this.surveyData.version = res.data.data.version;
                        this.surveyData.partners = res.data.data.partners;
                        this.surveyData.items = res.data.data.chapters;
                        if(res.data.data.yes_no_questions !== ''){
                            this.surveyData.conditioningQuestions = JSON.parse(res.data.data.yes_no_questions);
                        }else{
                            this.surveyData.conditioningQuestions = [];
                        }

                        this.loadedData = JSON.stringify(this.surveyData);
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },
        getSurveyStatuses: function(){

            axios.get(pathServer + 'surveys/ws/getSurveyStatuses')
                .then(res => { 
                    if (res.data.response == 'OK') {
                        this.statuses = res.data.data; 
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });

        },
        getPartners: function(){

            axios.get(pathServer + 'surveys/ws/getPartners')
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
        getStandardTexts: function(){
            axios.get(pathServer + 'surveys/ws/getStandardTexts')
            .then(res => { 
                if (res.data.response == 'OK') { 
                    this.standardTexts = res.data.data;
                } else {
                    alert(res.data.msg);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        checkFormSurvey: function(exit){

            var errors = false;

            /*if (this.surveyData.title == "" || this.surveyData.title == null){
                errors = true;
            }

            if (this.surveyData.subtitle == "" || this.surveyData.subtitle == null){
                errors = true;
            }

            if (this.surveyData.status == "" || this.surveyData.status == null){
                errors = true;
            }*/

            if(errors){
                alert('Si prega di compilare tutti i campi obbligatori.');
                return false;
            }else{
                this.saveSurvey(exit);
            }

        },
        saveSurvey: function(exit){

            let params = new URLSearchParams();

            if(this.surveyData.idSurvey){
                params.append('idSurvey', this.surveyData.idSurvey);
            }

            this.setItemsClosed(this.surveyData.items);

            params.append('title', this.surveyData.title);
            params.append('subtitle', this.surveyData.subtitle);
            params.append('description', this.surveyData.description);
            params.append('status', this.surveyData.status);
            params.append('partners', JSON.stringify(this.surveyData.partners));
            params.append('chapters', JSON.stringify(this.surveyData.items));
            params.append('yes_no_questions', JSON.stringify(this.surveyData.conditioningQuestions));

            if(this.surveyData.idSurvey === null || JSON.stringify(this.surveyData) !== this.loadedData){
                params.append('changed', 1);
            }else{
                params.append('changed', 0);
            }

            axios.post(pathServer + 'surveys/ws/saveSurvey', params)
                .then(res => {
                    if (res.data.response == 'OK') {
                        if(exit){
                            window.location = pathServer + 'surveys/surveys/index';
                        }else{ 
                            alert(res.data.msg);
                            if(!this.surveyData.idSurvey){
                                window.location = pathServer + 'surveys/surveys/edit?survey='+res.data.data.id;
                            }else{
                                this.surveyData.version = res.data.data.version;
                                this.loadedData = JSON.stringify(this.surveyData);
                            }
                        }
                    } else {
                        alert(res.data.msg);
                    }
                })
                .catch(error => {
                    console.log(error);
                });

        },
        setItemsClosed: function(items){
            items.forEach((item) => {
                item.open = false;
                if(item.items.length > 0){
                    this.setItemsClosed(item.items);
                }
            });
        },
        setSelectedPartner: function(value){
            if(value !== null){
                var partners = this.surveyData.partners;
                var enabled = false
                for( var i = 0; i < partners.length; i++) {
                    if(partners[i].code == value.code){
                        enabled = true;
                    }
                };

                if(enabled){
                    alert("L'azienda selezionata è gia stata associata.");
                    value = null;
                }
            }

            this.selectedPartner = value;
        },
        addPartner: function(){
            var partner = this.selectedPartner;
            if (partner === null || Object.keys(partner).length === 0){
                alert('Selezionare un\'azienda da associare.');
            }else{ 
                axios.get(pathServer + 'surveys/ws/getPartnerStructures/' + partner.code)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.surveyData.partners.push({
                            code: partner.code,
                            label: partner.label,
                            structures: res.data.data
                        });
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
                this.selectedPartner = null;
            }
        },
        removePartner: function(index){
            if(confirm("Si è sicuri di voler rimuovere l'ente abilitato?")){
                this.surveyData.partners.splice(index, 1);
            }
        },
        addItem: function(item){
            if(item.color != undefined && item.color != ''){
                color = item.color;
            }else{
                color = '#'+Math.floor(Math.random()*16777215).toString(16);
            }

            item.items.push({
                open: true,
                title: '',
                subtitle: '',
                color: color,
                items: [],
                questions: [],
            }); 

        },
        removeItem: function(params){ 
            if(confirm('Si è sicuri di voler cancellare il capitolo? Verrano cancellati anche tutti i sottocapitoli in esso contenuti.')){

                var updateQuestions = function(items, question_id){
                    items.forEach(function(item){
                        item.questions.forEach(function(q){
                            if(q.connected_to == question_id){
                                q.conditioned = false;
                                q.connected_to = '';
                                q.show_on = '';
                                q.visible = true;
                            }
                        })

                        if(item.items.length > 0){
                            updateQuestions(item.items, question_id);
                        }
                    });
                }

                var searchAndDeleteQuestions = (items) => {
                    items.forEach((item) => {
                        item.questions.forEach((question) => {
                            if(question.type == 'yes_no'){
                                var conditioningQuestions = this.surveyData.conditioningQuestions;
                                conditioningQuestions.forEach(function(q, i){ 
                                    if(q.question_id === question.id){ 
                                        conditioningQuestions.splice(i, 1);
                                    }
                                });
        
                                updateQuestions(this.surveyData.items, question.id);
                            }
                        });

                        if(item.items.length > 0){
                            searchAndDeleteQuestions(item.items, question_id);
                        }
                    });
                }

                params.item.items[params.index].questions.forEach((question) => {
                    if(question.type == 'yes_no'){ 
                        var conditioningQuestions = this.surveyData.conditioningQuestions;
                        conditioningQuestions.forEach(function(q, i){ 
                            if(q.question_id === question.id){ 
                                conditioningQuestions.splice(i, 1);
                            }
                        });

                        updateQuestions(this.surveyData.items, question.id);
                    }
                });

                searchAndDeleteQuestions(params.item.items[params.index].items);

                params.item.items.splice(params.index, 1);
            }else{
                return;
            }
        },
        changeStatus: function(status, e){
            if(status == 4){
                const msg = "ATTENZIONE: Questa matrice di questionario era congelata poichè già in uso per delle ispezioni e pertanto non è più ammissibile la sua modifica. Modificandone lo stato questo blocco verrà rimosso e l'operatore si assume le responsabilità di eventuali disallineamneti che potrebbero avvenire tra ispezioni fatte e matrice. Procedere al cambiamento di stato?"
                if(confirm(msg)){
                    this.surveyData.status = e.target.value;
                }else{
                    this.surveyData.status = e.target.value;
                    this.surveyData.status = 4;
                }
            }else{
                this.surveyData.status = e.target.value;
            }
        },
        checkInViewport: function(isInViewport) {
            this.footerInViewport = isInViewport;
        },
        showModalElements: function(params) {
            if(params.index !== ''){
                $(this.$refs['modalElements']).find('#clicked_index').val(params.index);
            }

            $(this.$refs['modalElements']).find('#section_label').val(params.label);

            this.currentItem = params.item;

            $(this.$refs['modalElements']).modal('show');
        },
        showModalQuestions: function(params, event) { 
            if(params.isInput){
                if(event.target.checked){
                    event.preventDefault();
                    if(params.index !== ''){
                        $(this.$refs['modalQuestions']).find('#clicked_index').val(params.index);
                    }

                    this.currentQuestion = params.question;

                    $(this.$refs['modalQuestions']).modal('show');
                }else{
                    params.question.conditioned = false;
                    params.question.connected_to = '';
                    params.question.show_on = '';
                    params.question.visible = true;
                }
            }else{
                this.currentQuestion = params.question;

                this.connected_to = params.question.connected_to;
                this.show_on = params.question.show_on;   

                $(this.$refs['modalQuestions']).modal('show');
            }
        },
        showModalPreviewQuestion: function(question) { 
            this.questionToPreview = question;

            $(this.$refs['modalPreviewQuestion']).modal('show');
        },
        addQuestion: function(params) { 
            let elem = $.extend(true, {}, params.question); 

            if(elem.type == 'standard_text'){

                $(this.$refs['modalStandardTexts']).find('#element_index').val(params.index);
                $(this.$refs['modalStandardTexts']).modal('show');

            }else{
                let index = $(this.$refs['modalElements']).find('#clicked_index').val();
                let label = $(this.$refs['modalElements']).find('#section_label').val();

                elem.id = new Date().valueOf();

                if(elem.type == 'yes_no' || elem.type == 'single_choice'){
                    this.surveyData.conditioningQuestions.push({'question_id': elem.id, 'question': elem.question, 'section': label, 'type': elem.type});
                }

                if(index === ''){
                    this.currentItem.questions.push(elem);
                }else{
                    this.currentItem.questions.splice(index, 0, elem);
                }
                
                $(this.$refs['modalElements']).find('#clicked_index').val('');
                $(this.$refs['modalElements']).modal('hide');
            }
        },
        addElementStandardText: function() { 
            let elem_index = $(this.$refs['modalStandardTexts']).find('#element_index').val();
            let elem = $.extend(true, {}, this.elements[elem_index]); 
            let index = $(this.$refs['modalElements']).find('#clicked_index').val();

            elem.id = new Date().valueOf();
            elem.name = this.standardTexts[this.selectedStandardText].name;
            elem.value = this.standardTexts[this.selectedStandardText].content;

            if(index === ''){
                this.currentItem.questions.push(elem);
            }else{
                this.currentItem.questions.splice(index, 0, elem);
            }

            $(this.$refs['modalStandardTexts']).modal('hide');
            
            $(this.$refs['modalElements']).find('#clicked_index').val('');
            $(this.$refs['modalElements']).modal('hide');
        },
        setConditionedQuestion: function() {
            if(this.connected_to !== '' && this.show_on !== ''){
                this.currentQuestion.conditioned = true;
                this.currentQuestion.connected_to = this.connected_to;
                this.currentQuestion.show_on = this.show_on;
                this.currentQuestion.visible = false;
                this.connected_to = '';
                this.show_on = '';
                this.currentQuestion = {};

                $(this.$refs['modalQuestions']).modal('hide');
            }else{
                alert('Per "Condizionare" seleziona sia la domanda che la risposta.');
            }
        },
        clearConditionedQuestion: function() {
            this.connected_to = '';
            this.show_on = '';
            this.currentQuestion = {};

            $(this.$refs['modalQuestions']).modal('hide');
        },

        //anteprima domanda

        showModalPreviewQuestion: function(question) { 
            this.questionToPreview = JSON.parse(JSON.stringify(question));
            $(this.$refs['modalPreviewQuestion']).modal('show');
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
        updateShortLabels: function(question) {
            if (!question.show_in_table) {
                question.label_table = '';
            }

            if (!question.show_in_export) {
                question.label_export = '';
            }
        }
    },
      
    filters: {

    }
    
});

var beforeunload = true; 
window.addEventListener('beforeunload', function (e) { 
    if(beforeunload && (app.surveyData.idSurvey === null || JSON.stringify(app.surveyData) !== app.loadedData)){
        // Cancel the event
        e.preventDefault();
        // Chrome requires returnValue to be set
        e.returnValue = '';
    }else{
        beforeunload = true;
    }
});