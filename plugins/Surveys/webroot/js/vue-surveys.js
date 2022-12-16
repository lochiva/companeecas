Vue.component('v-select', VueSelect.VueSelect);
Vue.component('toggle-switch', ToggleSwitch.ToggleSwitch);

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
        'survey',
        'editor',
        'placeholders'
    ],
    data: function () {
        return {
            isOpen: false,
            sectionToggleOptions: {
				layout: {
					color: '#007aff',
					backgroundColor: '#ffffff',
					borderColor: '#007aff',
					fontFamily: 'Arial',
					fontWeight: 'normal',
					fontWeightSelected: 'bold',
					squareCorners: false,
					noBorder: false
				},
				size: {
					fontSize: '1',
					height: '2.3',
					padding: '0.3',
					width: '18'
				},
                config: {
                    delay: .4,
                    items: [
                        { name: '1 colonna', value: 'single', color: '#ffffff', backgroundColor: '#16A1E7' },
                        { name: '2 colonne', value: 'double', color: '#ffffff', backgroundColor: '#16A1E7' }
                    ]
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
        onMovedItem: function(event) {
            console.log(event.draggedContext.element);
            console.log(event.relatedContext.element);
            if (
                typeof event.relatedContext.element === 'undefined' ||
                event.draggedContext.element.primary !== event.relatedContext.element.primary
            ) {
                return false;
            }
            return true;
        },
        showModalItemVisibility: function(item, type) { 
            this.$root.showModalItemVisibility(item, type);
        },
    }
};

var app = new Vue({
    el: '#app-surveys',
    data: {
        statuses: [],
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
        baseImageUrl: baseImageUrl,
        placeholders: placeholders,
        searchedDataSheets: [],
        dataSheetOptions: {
            data_sheet: '',
            visibility_by_component: false,
            components: [],
            section: {
                disabled: true,
                value: ''
            },
            block: {
                disabled: true,
                value: ''
            },
            component: {
                disabled: true,
                value: ''
            }
        },
        itemVisibilityOptions: {
            visibility_by_component: false,
            components: [],
            section: {
                disabled: true,
                value: ''
            },
            block: {
                disabled: true,
                value: ''
            },
            component: {
                disabled: true,
                value: ''
            }
        },
        currentVisibilityItem: {},
        currentVisibilityItemType: {},
        sectionsList: [],
        blocksList: [],
        componentsList: [],
        editorInit: {
            resize: false, 
            height: 300, 
            language: 'it_IT', 
            branding: false, 
            plugins: ['image', 'table', 'code', 'paste'],
            relative_urls : false,
            remove_script_host : false,
            convert_urls : true,
            content_style: "* { font-family: Times; }",
            paste_retain_style_properties: 'color font-size background-color padding-left padding-right text-align padding-top padding-bottom line-height',
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
        },

        previewEditorInit: {
            resize: false, 
            height: 300, 
            language: 'it_IT', 
            branding: false, 
            plugins: ['image', 'table', 'code', 'paste'],
            content_style: "* { font-family: Times; }",
            paste_retain_style_properties: 'color font-size background-color padding-left padding-right text-align padding-top padding-bottom line-height',
            relative_urls : false,
            remove_script_host : false,
            convert_urls : true
        }
    },

    computed: {

    },

    components: {
        'editor': Editor,
        "nested-draggable" : nested
    },
      
    mounted: function () {

        var url = new URL(window.location.href);

        this.surveyData.idSurvey = url.searchParams.get("survey");

        if(this.surveyData.idSurvey){
            this.loadSurvey(this.surveyData.idSurvey);
        }
        this.getSurveyStatuses();
        this.getStandardTexts();

    },
       
    methods: {
        loadSurvey: function(id){
            axios.get(pathServer + 'surveys/ws/getSurvey/' + id)
                .then(res => { 
                    if (res.data.response == 'OK') {
                        //this.updateSectionsList();
                        this.surveyData.title = res.data.data.title;
                        this.surveyData.subtitle = res.data.data.subtitle;
                        this.surveyData.description = res.data.data.description;
                        this.surveyData.status = res.data.data.status;
                        this.surveyData.version = res.data.data.version;
                        this.surveyData.partners = res.data.data.partners;
                        this.surveyData.items = res.data.data.chapters.map((item) => {
                            item.open= true;
                            return item;
                        });
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
        resetVisibility: function(items) {
            items.forEach((item) => {
                item.visibility.visibility_by_component = false;
                item.visibility.components = [];

                if (item.questions.length > 0) {
                    item.questions.forEach((question) => {
                        if (question.type == 'data_sheet') {
                            question.visibility_by_component = false;
                            question.components = [];
                        }
                    });
                }

                if (item.items.length > 0) {
                    this.resetVisibility(item.items);
                }
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

            if (this.surveyData.title == "" || this.surveyData.title == null){
                errors = true;
            }

            if (this.surveyData.subtitle == "" || this.surveyData.subtitle == null){
                errors = true;
            }

            /*
            if (this.surveyData.status == "" || this.surveyData.status == null){
                errors = true;
            }
            */

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
        addItem: function(item, primary = false){
            if(item.color != undefined && item.color != ''){
                color = item.color;
            }else{
                color = '#'+Math.floor(Math.random()*16777215).toString(16);
            }

            item.items.push({
                open: true,
                primary: primary,
                layout: 'single',
                visibility: {
                    visibility_by_component: false,
                    components: []
                },
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

            if(elem.type == 'data_sheet'){

                $(this.$refs['modalDataSheetOptions']).find('#element_index').val(params.index);
                //Reset options
                this.resetModalDataSheetOptions();
                $(this.$refs['modalDataSheetOptions']).modal('show');

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
        addElementDataSheet: function() {
            if (
                this.dataSheetOptions.data_sheet && 
                (!this.dataSheetOptions.visibility_by_component || this.dataSheetOptions.components.length > 0)
            ) {
                let elem_index = $(this.$refs['modalDataSheetOptions']).find('#element_index').val();
                let elem = $.extend(true, {}, this.elements[elem_index]); 
                let index = $(this.$refs['modalElements']).find('#clicked_index').val();

                elem.id = new Date().valueOf();
                elem.data_sheet = this.dataSheetOptions.data_sheet;
                elem.visibility_by_component = this.dataSheetOptions.visibility_by_component;
                elem.components = this.dataSheetOptions.components;

                if(index === ''){
                    this.currentItem.questions.push(elem);
                }else{
                    this.currentItem.questions.splice(index, 0, elem);
                }

                $(this.$refs['modalDataSheetOptions']).modal('hide');
                
                $(this.$refs['modalElements']).find('#clicked_index').val('');
                $(this.$refs['modalElements']).modal('hide');
            } else {
                alert('Selezionare una scheda tecnica e un criterio di visualizzazione (se si seleziona "Guidato dal componente" è necessario aggiungere almeno un componente).');
            }
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
        },
        searchDataSheet: function(search, loading) { 
            search = search || this.$refs.selectDataSheet.search;
            loading = loading || this.$refs.selectDataSheet.toggleLoading;

            loading(true);
            axios.get(pathServer + 'surveys/ws/searchDataSheet/'+search)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.searchedDataSheets = res.data.data; 
                    loading(false);
                } else {
                    this.searchedDataSheets = [];
                    loading(false);
                }
            }).catch(error => {
                console.log(error);
                loading(false);
            });
        },
        resetModalDataSheetOptions: function() {
            this.dataSheetOptions.data_sheet = '';
            this.dataSheetOptions.visibility_by_component = false;
            this.dataSheetOptions.components = [];
            this.dataSheetOptions.section = {
                disabled: true,
                value: ''
            };
            this.dataSheetOptions.block = {
                disabled: true,
                value: ''
            };
            this.dataSheetOptions.component = {
                disabled: true,
                value: ''
            };  
            this.blocksList = [];
            this.componentsList = [];
        },
        changeDataSheetVisibilityByComponent: function() {
            if (this.dataSheetOptions.visibility_by_component) {
                this.dataSheetOptions.section.disabled = false;
                this.dataSheetOptions.block.disabled = false;
                this.dataSheetOptions.component.disabled = false;
            } else {
                this.dataSheetOptions.components = [];
                this.dataSheetOptions.section = {
                    disabled: true,
                    value: ''
                };
                this.dataSheetOptions.block = {
                    disabled: true,
                    value: ''
                };
                this.dataSheetOptions.component = {
                    disabled: true,
                    value: ''
                };
                this.blocksList = [];
                this.componentsList = [];
            }
        },
        updateBlocksList: function() {
            this.dataSheetOptions.block.value = '';
            this.blocksList = [];
            this.dataSheetOptions.component.value = '';
            this.componentsList = [];

            axios.get(pathServer + 'building/ws/getBlocksBySection/'+this.dataSheetOptions.section.value.id)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.blocksList = res.data.data;
                } else {
                    this.blocksList = [];
                }
            }).catch(error => {
                console.log(error);
            });
        },
        updateComponentsList: function() {
            this.dataSheetOptions.component.value = '';
            this.componentsList = [];

            axios.get(pathServer + 'building/ws/getComponentsByBlock/'+this.dataSheetOptions.block.value.id)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.componentsList = res.data.data;
                } else {
                    this.componentsList = [];
                }
            }).catch(error => {
                console.log(error);
            });
        },
        addComponentToDataSheet: function() {
            if (this.dataSheetOptions.section.value && this.dataSheetOptions.block.value && this.dataSheetOptions.component.value) {
                this.dataSheetOptions.components.push({
                    id: this.dataSheetOptions.component.value.id,
                    text: this.dataSheetOptions.section.value.name + ' / ' + this.dataSheetOptions.block.value.name + ' / ' + this.dataSheetOptions.component.value.name
                });

                //Reset select
                this.dataSheetOptions.section.value = '';
                this.dataSheetOptions.block.value = '';
                this.blocksList = [];
                this.dataSheetOptions.component.value = '';
                this.componentsList = [];
            } else {
                alert('Selezionare un componente da aggiungere.');
            }
        },
        removeComponentFromDataSheet: function(index) {
            this.dataSheetOptions.components.splice(index, 1);
        },
        showModalItemVisibility: function(item, type) {
            this.resetModalItemVisibility();

            this.currentVisibilityItem = item;
            this.currentVisibilityItemType = type;
            if (type == 'section') {
                this.itemVisibilityOptions.visibility_by_component = JSON.parse(JSON.stringify(item.visibility.visibility_by_component));
            } else if (type == 'element') {
                this.itemVisibilityOptions.visibility_by_component = JSON.parse(JSON.stringify(item.visibility_by_component));
            }
            this.changeItemVisibilityByComponent();
            if (type == 'section') {
                this.itemVisibilityOptions.components = JSON.parse(JSON.stringify(item.visibility.components));
            } else if (type == 'element') {
                this.itemVisibilityOptions.components = JSON.parse(JSON.stringify(item.components));
            }

            $(this.$refs['modalItemVisibility']).modal('show');
        },
        resetModalItemVisibility: function() {
            this.itemVisibilityOptions.visibility_by_component = false;
            this.itemVisibilityOptions.components = [];
            this.itemVisibilityOptions.section = {
                disabled: true,
                value: ''
            };
            this.itemVisibilityOptions.block = {
                disabled: true,
                value: ''
            };
            this.itemVisibilityOptions.component = {
                disabled: true,
                value: ''
            };  
            this.blocksList = [];
            this.componentsList = [];
        },
        changeItemVisibilityByComponent: function() {
            if (this.itemVisibilityOptions.visibility_by_component) {
                this.itemVisibilityOptions.section.disabled = false;
                this.itemVisibilityOptions.block.disabled = false;
                this.itemVisibilityOptions.component.disabled = false;
            } else {
                this.itemVisibilityOptions.components = [];
                this.itemVisibilityOptions.section = {
                    disabled: true,
                    value: ''
                };
                this.itemVisibilityOptions.block = {
                    disabled: true,
                    value: ''
                };
                this.itemVisibilityOptions.component = {
                    disabled: true,
                    value: ''
                };
                this.blocksList = [];
                this.componentsList = [];
            }
        },
        updateBlocksListForVisibility: function() {
            this.itemVisibilityOptions.block.value = '';
            this.blocksList = [];
            this.itemVisibilityOptions.component.value = '';
            this.componentsList = [];

            axios.get(pathServer + 'building/ws/getBlocksBySection/'+this.itemVisibilityOptions.section.value.id)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.blocksList = res.data.data;
                } else {
                    this.blocksList = [];
                }
            }).catch(error => {
                console.log(error);
            });
        },
        updateComponentsListForVisibility: function() {
            this.itemVisibilityOptions.component.value = '';
            this.componentsList = [];

            axios.get(pathServer + 'building/ws/getComponentsByBlock/'+this.itemVisibilityOptions.block.value.id)
            .then(res => { 
                if (res.data.response == 'OK') {
                    this.componentsList = res.data.data;
                } else {
                    this.componentsList = [];
                }
            }).catch(error => {
                console.log(error);
            });
        },
        addComponentToItemVisibility: function() {
            if (this.itemVisibilityOptions.section.value && this.itemVisibilityOptions.block.value && this.itemVisibilityOptions.component.value) {
                this.itemVisibilityOptions.components.push({
                    id: this.itemVisibilityOptions.component.value.id,
                    text: this.itemVisibilityOptions.section.value.name + ' / ' + this.itemVisibilityOptions.block.value.name + ' / ' + this.itemVisibilityOptions.component.value.name
                });

                //Reset select
                this.itemVisibilityOptions.section.value = '';
                this.itemVisibilityOptions.block.value = '';
                this.blocksList = [];
                this.itemVisibilityOptions.component.value = '';
                this.componentsList = [];
            } else {
                alert('Selezionare un componente da aggiungere.');
            }
        },
        setItemVisibility: function() {
            if (!this.itemVisibilityOptions.visibility_by_component || this.itemVisibilityOptions.components.length > 0) {
                if (this.currentVisibilityItemType == 'section') {
                    this.currentVisibilityItem.visibility.visibility_by_component = this.itemVisibilityOptions.visibility_by_component;
                    this.currentVisibilityItem.visibility.components = this.itemVisibilityOptions.components;
                } else if (this.currentVisibilityItemType == 'element') {
                    this.currentVisibilityItem.visibility_by_component = this.itemVisibilityOptions.visibility_by_component;
                    this.currentVisibilityItem.components = this.itemVisibilityOptions.components;
                }
                $(this.$refs['modalItemVisibility']).modal('hide');
            } else {
                alert('Selezionare un criterio di visualizzazione (se si seleziona "Guidato dal componente" è necessario aggiungere almeno un componente).');
            }
           
        },
        removeComponentFromItemVisibility: function(index) {
            this.itemVisibilityOptions.components.splice(index, 1);
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