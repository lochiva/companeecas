Vue.component('v-select', VueSelect.VueSelect);

Vue.use(VueMaterial.default)

Vue.component('tree-item', {
    template: '#item-template',
    props: [
        'index',
        'item',
        'parentitem',
        'number-label',
        'role',
        'id-interview',
        'status'
    ],
    data: function () {
        return {
            isOpen: true,
            datepickerItalian: vdp_translation_it.js,
            baseImageUrl: baseImageUrl,
            editorInit: {
                resize: true, 
                min_height: 300,
                max_height: 900,
                language: 'it_IT', 
                branding: false, 
                plugins: ['image', 'table', 'code', 'paste', 'autoresize'],
                menubar: 'file edit view insert format table',
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                content_style: "* { font-family: Times; }",
                paste_retain_style_properties: 'color font-size background-color padding-left padding-right text-align padding-top padding-bottom line-height border-collapse collapse width border-style word-wrap border cellpadding page-break-inside',
                indentation: '10%',
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
        'datepicker': vuejsDatepicker,
        'editor': Editor
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
        computeNumberLabel: function(items, index) {
            var excludedItems = 0;
            var number = '';
            if (items[index].questions.length > 0) {
                for(i = 0; i <= index; i++) {
                    if (items[i].questions.length == 0) {
                        excludedItems++;
                    }
                }
                number = (index + 1) - excludedItems + '.';
            }

            return number;
        },
        isComponentActive: function(components) {
            var activeComponents = this.$root.activeComponents;
            var componentIds = [];
            components.forEach(function(component) {
                componentIds.push(component.id);
            });
            return componentIds.some(id => activeComponents.includes(id));
        },
        getDimensions: function() {
            return this.$root.dimensions;
        },
    }
});

var app = new Vue({
    el: '#app-interviews',
    data: {
        idSurvey: 0,
        idQuotation: 0,
        surveyVersion: '',
        role: role,
        interviewData: {
            idInterview: 0,
            title: '',
            subtitle: '',
            description: '',
            status: '',
            version: '',
            items: []
        },
        loadedData: '',
        footerInViewport: true,
        preview: 0,
        activeComponents: [],
        dimensions: []
    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.idSurvey = url.searchParams.get("survey");

        if(url.searchParams.get("quotation") != null){
            this.idQuotation = url.searchParams.get("quotation");
        }

        if(url.searchParams.get("interview") != null){
            this.interviewData.idInterview = url.searchParams.get("interview");
        }

        if (url.searchParams.get("preview")) {
            this.preview = url.searchParams.get("preview");
        }

        if(this.interviewData.idInterview){
            this.loadInterview(this.interviewData.idInterview);
        }else{
            this.loadSurvey(this.idSurvey);
        }
    },
       
    methods: {
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
                        this.interviewData.title = res.data.data.title;
                        this.interviewData.subtitle = res.data.data.subtitle;
                        this.interviewData.description = res.data.data.description;
                        this.interviewData.status = res.data.data.status;
                        this.interviewData.version = res.data.data.version;
                        this.interviewData.items = res.data.data.answers;

                        this.dimensions = res.data.data.dimensions;

                        if (this.preview) {
                            this.updateInterviewQuestionsVisibility();
                        }

                        this.loadedData = JSON.stringify(this.interviewData);
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },
        loadSurvey: function(id){
            axios.get(pathServer + 'surveys/ws/getSurvey/' + id + '/' + 1 + '/' + this.idQuotation)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.idSurvey = res.data.data.id;
                        this.surveyVersion = res.data.data.version;
                        this.interviewData.title = res.data.data.title;
                        this.interviewData.subtitle = res.data.data.subtitle;
                        this.interviewData.description = res.data.data.description;
                        this.interviewData.version = res.data.data.version;
                        this.interviewData.items = res.data.data.chapters;

                        this.dimensions = res.data.data.dimensions;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                }); 
        },
        saveInterview: function(exit){
            notValid = this.checkInterviewRequiredQuestions(this.interviewData.items, false);

            if (notValid) {
                alert("Si prega di compilare tutti i campi obbligatori.");
            } else {
                for (let item of this.interviewData.items) {
                    for (let question of item.questions) {
                        if (question.type.indexOf('answer_text_editor' === 0)) {
                            question.answer = question.value_to_show;
                        }
                    }
                }

                let params = new URLSearchParams();
                if(this.interviewData.idInterview){
                    params.append('idInterview', this.interviewData.idInterview);
                }
                params.append('id_survey', this.idSurvey);
                if (this.idQuotation) {
                    params.append('id_quotation', this.idQuotation);
                }
                params.append('title', this.interviewData.title);
                params.append('subtitle', this.interviewData.subtitle);
                params.append('description', this.interviewData.description);
                params.append('version', this.interviewData.version);
                params.append('answers', JSON.stringify(this.interviewData.items));
                params.append('not_valid', notValid);

                axios.post(pathServer + 'surveys/ws/saveInterview', params)
                    .then(res => {
                        if (res.data.response == 'OK') {
                            this.loadedInterview = JSON.stringify(this.interviewData);
                            if(exit){
                                window.location = pathServer + 'surveys/surveys/interviews/'+this.idSurvey;
                            }else{
                                alert(res.data.msg);
                                if(!this.interviewData.idInterview || this.preview){
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
                            case 'answer_text_editor':
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

        updateInterviewQuestionsVisibility: function() {
            var updateItemsQuestionsVisibility = (items) =>  {
                items.forEach((item) => {
                    item.questions.forEach((q) => {
                        this.updateConditionedQuestions(q);
                    });

                    if(item.items.length > 0){
                        updateItemsQuestionsVisibility(item.items);
                    }
                });
            }
            updateItemsQuestionsVisibility(this.interviewData.items);
        },

        updateInterviewStructure: function() {
            window.location.href = pathServer + "surveys/surveys/answers?survey=" + this.idSurvey + "&interview=" + this.interviewData.idInterview + "&preview=1";
        },

        documentPreview: function() {
            window.open(pathServer + 'surveys/surveys/documentPreview/' + this.interviewData.idInterview);
        },

        getActiveComponentsByInterview: function() {
            axios.get(pathServer + 'surveys/ws/getActiveComponentsByInterview/' + this.interviewData.idInterview)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.activeComponents = res.data.data;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                }); 
        },

        getActiveComponentsByQuotation: function() {
            axios.get(pathServer + 'surveys/ws/getActiveComponentsByQuotation/' + this.idQuotation)
                .then(res => { 
                    if (res.data.response == 'OK') { 
                        this.activeComponents = res.data.data;
                    } else {
                        alert(res.data.msg);
                    }
                }).catch(error => {
                    console.log(error);
                }); 
        }
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