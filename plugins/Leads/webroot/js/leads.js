$(document).ready(function(){

    //Tabella ensembles
    $('#table-ensembles').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
                2:{
                    'Sì': function(e,n,f,i,$r){return e===f},
                    'No': function(e,n,f,i,$r){return e===f}
                }
            }
        },
    }).tablesorterPager({
        container: $("#pager-ensembles"),

        ajaxUrl: pathServer + 'leads/ws/getEnsembles/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

        // modify the url after all processing has been applied
        customAjaxUrl: function(table, url) {
            // manipulate the url string as you desire
        // url += '&cPage=' + window.location.pathname;
        // trigger my custom event
            $(table).trigger('changingUrl', url);
            // send the server the current page
            $('#template-spinner').show();
            return url;
        },

        // add more ajax settings here
        // see http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
        ajaxObject: {
            dataType: 'json'
        },
        // return [ total_rows, $rows (jQuery object; optional), headers (array; optional) ]
        ajaxProcessing: function(data){
    
            if (data && data.hasOwnProperty('rows')) {
                var r, row, c, d = data.rows,
                // total number of rows (required)
                total = data.total_rows,
                // array of header names (optional)
                headers = data.headers,
                // all rows: array of arrays; each internal array has the table cell data for that row
                rows = [],
                // len should match pager set size (c.size)
                len = d.length;
                // this will depend on how the json is set up - see City0.json
                // rows
                for ( r=0; r <= len; r++ ) {
                    row = []; // new row array
                    // cells
                    for ( c in d[r] ) {
                        if (typeof(c) === "string") {
                        row.push(d[r][c]); // add each table cell data to row array
                        }
                    }
                    rows.push(row); // add new row array to rows array
                }
                // in version 2.10, you can optionally return $(rows) a set of table rows within a jQuery object

                $('#template-spinner').hide();
                return [ total, rows, headers ];

            }

            $('#template-spinner').hide();
        },
        // output string - default is '{page}/{totalPages}'; possible variables: {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
        output: '{startRow} - {endRow} / {filteredRows} ({totalRows})',
        // apply disabled classname to the pager arrows when the rows at either extreme is visible - default is true
        updateArrows: true,
        // starting page of the pager (zero based index)
        page: 0,
        // Number of visible rows - default is 10
        size: 10,
        // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
        // table row set to a height to compensate; default is false
        fixedHeight: false,
        // remove rows from the table to speed up the sort of large tables.
        // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
        removeRows: false,
        // go to page selector - select dropdown that sets the current page
        cssGoto: '.gotoPage'
    }).bind("pagerComplete pagerInitialized",function(e, options){

        if(parseInt(options.totalRows) == 0 && $('span#no-result').length == 0){
            $('#'+$(this).attr("id")+' tbody').append('<tr><td colspan="'+$('#'+$(this).attr("id")).find('thead th').length+'" style="text-align:center;"><span id="no-result">Nessun risultato trovato.</span></td></tr>');
        }
        calculateTableDropdownPosition();

    });

    //Lista delle domande sortabile
    $('#questionsList').sortable();

    //All'update della lista setto l'ordine nel db
    $('#questionsList').on('sortupdate',function() {
        var data = $(this).sortable('serialize');
    
        $.ajax({
            url: pathServer + "leads/ws/setQuestionsOrdering",
            type: "POST",
            data: data
        }).done(function(res) {
            if(res.response == 'KO'){
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

    //Select tipologie domanda
    $('#formQuestion #inputType').select2({
        language: 'it',
        width: '100%',
        placeholder: 'Selezione una tipologia',
        closeOnSelect: true,
        ajax: {
            url: pathServer+'leads/ws/autocompleteQuestionType',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.data
                };
            },
            cache: true
        }
    });

    //Salvataggio ensemble
    $('#saveEnsemble').click(function(){
        if(formValidation('formEnsemble')){ 
            var formData = new FormData($('#formEnsemble')[0]);
            
            $.ajax({
                url: pathServer + "leads/ws/saveEnsemble",
                type: "POST",
                processData: false,
                contentType: false,
                dataType: "json",
                data: formData
            }).done(function(res) {
                if(res.response == 'OK'){
                    $('#modalEnsemble').modal('hide');
                    $('#table-ensembles').trigger('update');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //Modifica ensemble
    $(document).on('click', '.edit-ensemble', function(){
        var id_ensemble = $(this).attr('data-id');

        $.ajax({
            url: pathServer + "leads/ws/getEnsemble/"+id_ensemble,
            type: "GET",
            dataType: "json",
        }).done(function(res) {
            if(res.response == 'OK'){
                $('#modalEnsemble #idEnsemble').val(res.data.id);
                $('#modalEnsemble #inputName').val(res.data.name);
                $('#modalEnsemble #inputDescription').val(res.data.description);
                if(res.data.active){
                    $('#modalEnsemble #inputActive').prop('checked', true);
                }else{
                    $('#modalEnsemble #inputActive').prop('checked', false);
                }
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });        
    });

    //Cancellazione ensemble
    $(document).on('click', '.delete-ensemble', function(e){
        e.preventDefault();

        if(confirm('Si è sicuri di voler cancellare l\'ensemble?')){      
            var id_ensemble = $(this).attr('data-id');

            $.ajax({
                url: pathServer + "leads/ws/deleteEnsemble",
                type: "POST",
                dataType: "json",
                data: {id_ensemble: id_ensemble}
            }).done(function(res) {
                if(res.response == 'OK'){
                    $('#table-ensembles').trigger('update');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //Apertura modale domande
    $(document).on('click', '.manage-questions', function(){
        var id_ensemble = $(this).attr('data-id');

        $('#modalQuestions #idEnsemble').val(id_ensemble);

        loadQuestions(id_ensemble);
    });

    //Selezionando tipo domanda select mostro campo epr le opzioni
    $('#formQuestion #inputType').change(function(){
        if($(this).val() == 4){
            $('#options-select').show();
            $('#inputOptions').addClass('required');
        }else{
            $('#options-select').hide();
            $('#inputOptions').val('');
            $('#inputOptions').removeClass('required');
        }
    })

    //Salvataggio domanda
    $('#saveQuestion').click(function(){
        if(formValidation('formQuestion')){ 
            var formData = new FormData($('#formQuestion')[0]);
            
            $.ajax({
                url: pathServer + "leads/ws/saveQuestion",
                type: "POST",
                processData: false,
                contentType: false,
                dataType: "json",
                data: formData
            }).done(function(res) {
                if(res.response == 'OK'){
                    var id_ensemble = $('#formQuestion #idEnsemble').val();
                    loadQuestions(id_ensemble);
                    $('#cancelEditQuestion').hide();
                    clearModal('formQuestion');
                    $('#formQuestion #idEnsemble').val(id_ensemble);
                    $('#saveQuestion').html('Aggiungi');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //Modifica domanda
    $(document).on('click', '.edit-question', function(e){
        e.preventDefault();

        var id_question = $(this).attr('data-id');

        $('#saveQuestion').html('Modifica');

        $.ajax({
            url: pathServer + "leads/ws/getQuestion/"+id_question,
            type: "GET",
            dataType: "json",
        }).done(function(res) {
            if(res.response == 'OK'){ 
                $('#cancelEditQuestion').show();
                $('#formQuestion #idQuestion').val(res.data.id);
                $('#formQuestion #inputName').val(res.data.name);
                var option = '<option value="'+res.data.id_type+'">'+res.data.question_type.label+'</option>';
                $('#formQuestion #inputType').html(option);
                $('#formQuestion #inputType').val(res.data.id_type).trigger('change');
                $('#formQuestion #inputOptions').val(res.data.options);
                $('#formQuestion #inputInfo').val(res.data.info);
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });

    });

    //Scarica file
    $('#downloadFile').click(function(){
        var id = $(this).attr('data-id');
        $('#template-spinner').show();
        document.cookie = 'downloadStarted=0;path=/';    
		window.location = pathServer + 'leads/ws/downloadAnswerFile/' + id;
        checkCookieForLoader('downloadStarted', '1');
    });

    //Elimina file
    $('#deleteFile').click(function(){
        if(confirm('Attenzione! Si è sicuri di voler eliminare il file?')){
            var id = $(this).attr('data-id');
            $.ajax({
                url: pathServer + "leads/ws/deleteAnswerFile",
                type: "POST",
                dataType: "json",
                data: {id: id}
            }).done(function(res) {
                if(res.response == 'OK'){
                    alert(res.msg);
                    location.reload();
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    $('#cancelEditQuestion').click(function(){
        var id_ensemble = $('#formQuestion #idEnsemble').val();
        $(this).hide();
        clearModal('formQuestion');
        $('#formQuestion #idEnsemble').val(id_ensemble);
        $('#saveQuestion').html('Aggiungi');
    });

    //Cancellazione domanda
    $(document).on('click', '.delete-question', function(e){
        e.preventDefault();

        if(confirm('Si è sicuri di voler cancellare la domanda?')){      
            var id_question = $(this).attr('data-id');

            $.ajax({
                url: pathServer + "leads/ws/deleteQuestion",
                type: "POST",
                dataType: "json",
                data: {id_question: id_question}
            }).done(function(res) {
                if(res.response == 'OK'){
                    loadQuestions($('#formQuestion #idEnsemble').val(), true);
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });


    //Tabella interviste
    $('#table-interviews').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
        },
    }).tablesorterPager({
        container: $("#pager-interviews"),

        ajaxUrl: pathServer + 'leads/ws/getInterviews/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

        // modify the url after all processing has been applied
        customAjaxUrl: function(table, url) {
            // manipulate the url string as you desire
        // url += '&cPage=' + window.location.pathname;
        // trigger my custom event
            $(table).trigger('changingUrl', url);
            // send the server the current page
            $('#template-spinner').show();
            return url;
        },

        // add more ajax settings here
        // see http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
        ajaxObject: {
            dataType: 'json'
        },
        // return [ total_rows, $rows (jQuery object; optional), headers (array; optional) ]
        ajaxProcessing: function(data){
    
            if (data && data.hasOwnProperty('rows')) {
                var r, row, c, d = data.rows,
                // total number of rows (required)
                total = data.total_rows,
                // array of header names (optional)
                headers = data.headers,
                // all rows: array of arrays; each internal array has the table cell data for that row
                rows = [],
                // len should match pager set size (c.size)
                len = d.length;
                // this will depend on how the json is set up - see City0.json
                // rows
                for ( r=0; r <= len; r++ ) {
                    row = []; // new row array
                    // cells
                    for ( c in d[r] ) {
                        if (typeof(c) === "string") {
                        row.push(d[r][c]); // add each table cell data to row array
                        }
                    }
                    rows.push(row); // add new row array to rows array
                }
                // in version 2.10, you can optionally return $(rows) a set of table rows within a jQuery object

                $('#template-spinner').hide();
                return [ total, rows, headers ];

            }

            $('#template-spinner').hide();
        },
        // output string - default is '{page}/{totalPages}'; possible variables: {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
        output: '{startRow} - {endRow} / {filteredRows} ({totalRows})',
        // apply disabled classname to the pager arrows when the rows at either extreme is visible - default is true
        updateArrows: true,
        // starting page of the pager (zero based index)
        page: 0,
        // Number of visible rows - default is 10
        size: 10,
        // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
        // table row set to a height to compensate; default is false
        fixedHeight: false,
        // remove rows from the table to speed up the sort of large tables.
        // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
        removeRows: false,
        // go to page selector - select dropdown that sets the current page
        cssGoto: '.gotoPage'
    }).bind("pagerComplete pagerInitialized",function(e, options){

        if(parseInt(options.totalRows) == 0 && $('span#no-result').length == 0){
            $('#'+$(this).attr("id")+' tbody').append('<tr><td colspan="'+$('#'+$(this).attr("id")).find('thead th').length+'" style="text-align:center;"><span id="no-result">Nessun risultato trovato.</span></td></tr>');
        }
        calculateTableDropdownPosition();

    });

    //Apertura modale intervista
    $('#addInterview').click(function(){
        var id_azienda = $(this).attr('data-id');

        if (typeof id_azienda !== typeof undefined && id_azienda !== false) {
            var name = $(this).attr('data-name');

            var option = '<option value="'+id_azienda+'">'+name+'</option>';
            $('#formInterview #inputAzienda').html(option);
            $('#formInterview #inputAzienda').val(id_azienda).trigger('change');
            $('#formInterview #inputAzienda').prop('disabled', true);
        }else{
            $('#formInterview #inputAzienda').html('');
            $('#formInterview #inputAzienda').prop('disabled', false);
        }
    });

    //Select aziende
    $('#formInterview #inputAzienda').select2({
        language: 'it',
        width: '100%',
        placeholder: 'Selezione un\'azienda',
        closeOnSelect: true,
        minimumInputLength: 3,
        ajax: {
            url: pathServer+'aziende/ws/autocompleteAziende',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.data
                };
            },
            cache: true
        }
    });

    //Select contatti
    $('#formInterview #inputContact').select2({
        language: 'it',
        width: '100%',
        placeholder: 'Selezione un contatto',
        closeOnSelect: true
    });

    $('#formInterview #inputAzienda').change(function(){
        var id_azienda = $(this).val();
        if(id_azienda){
            $('#formInterview #inputContact').select2({
                language: 'it',
                width: '100%',
                placeholder: 'Selezione un contatto',
                ajax: {
                    url: pathServer+'leads/ws/autocompleteContatti/'+id_azienda,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.data
                        };
                    },
                    cache: true
                }
            });
        }else{
            $('#formInterview #inputContact').select2({
                language: 'it',
                width: '100%',
                placeholder: 'Selezione un contatto',
                closeOnSelect: true
            });
        }
    });

    //Select ensemble
    $('#formInterview #inputEnsemble').select2({
        language: 'it',
        width: '100%',
        placeholder: 'Selezione un ensemble',
        closeOnSelect: true,
        ajax: {
            url: pathServer+'leads/ws/autocompleteEnsemble',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.data
                };
            },
            cache: true
        }
    });

    //Salvataggio intervista
    $('#saveInterview').click(function(){

        var button = $(this);
        button.prop('disabled', true);

        if(formValidation('formInterview')){ 

            var formData = {
                id: $('#idInterview').val(),
                id_azienda: $('#formInterview #inputAzienda').val(),
                id_contatto: $('#formInterview #inputContact').val(),
                id_ensemble: $('#formInterview #inputEnsemble').val(),
                name: $('#formInterview #inputName').val()
            };
 
            $.ajax({
                url: pathServer + "leads/ws/saveInterview",
                type: "POST",
                dataType: "json",
                data: formData
            }).done(function(res) {
                if(res.response == 'OK'){
                    if($('#idInterview').val() == ''){
                        window.location = pathServer + 'admin/leads/interview/answers/'+res.data;
                    }else{
                        $('#modalInterview').modal('hide');
                        $('#table-interviews').trigger('update');
                    }
                }else{
                    alert(res.msg);
                    button.prop('disabled', false);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                button.prop('disabled', false);
            });
        }

        button.prop('disabled', false);
    });

    //Cancellazione intervista
    $(document).on('click', '.delete-interview', function(e){
        e.preventDefault();

        if(confirm('Si è sicuri di voler cancellare l\'intervista?')){      
            var id_interview = $(this).attr('data-id');

            $.ajax({
                url: pathServer + "leads/ws/deleteInterview",
                type: "POST",
                dataType: "json",
                data: {id_interview: id_interview}
            }).done(function(res) {
                if(res.response == 'OK'){
                    if($('#table-interviews').length > 0){
                        $('#table-interviews').trigger('update');
                    }else{
                        location.reload();
                    }
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //modale informazioni domanda
    $('.info-question').click(function(){
        var text_info = $(this).parent().find('.text-info-question').html();
        $('#modalInfoQuestion #infoQuestion').html(text_info);
    });

    //rimozione classe errore input al change
    $('input, select').on('keyup change', function(){
        if($(this).hasClass('required') && $(this).val() != '' || !$(this).hasClass('required')){
            $(this).parentsUntil('.input').parent().removeClass('has-error');
        }
    });

    // Chiusura modale ensemble
    $('#modalEnsemble').on('hidden.bs.modal', function(){
        clearModal('formEnsemble');
    });

    //Chiusura modale domande
    $('#modalQuestions').on('hidden.bs.modal', function(){
        clearModal('formQuestion');
        $('#questionsList').html('');
        $('#table-ensembles').trigger('update');
    });

    //Modifica intestazione intervista
    $(document).on('click', '.edit-headers-interview', function(){
        var interview_id = $(this).attr('data-id');

        $('#idInterview').val(interview_id);

        $.ajax({
            url: pathServer + "leads/ws/getInterview/"+interview_id,
            type: "GET",
            dataType: "json",
        }).done(function(res) {
            if(res.response == 'OK'){
                var azienda = new Option(res.data.azienda.denominazione, res.data.azienda.id, true, true);
                $("#inputAzienda").append(azienda).trigger('change');

                var contact = new Option(res.data.contatti.cognome+' '+res.data.contatti.nome, res.data.contatti.id, true, true);
                $("#inputContact").append(contact).trigger('change');

                var ensemble = new Option(res.data.ensemble.name, res.data.ensemble.id, true, true);
                $("#inputEnsemble").append(ensemble).trigger('change');

                $('#inputName').val(res.data.name);

                $('#modalInterview').modal('show');
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

    //Chiusura modale intervista
    $('#modalInterview').on('hidden.bs.modal', function(){
        clearModal('formInterview');
    });

});


function clearModal(formId)
{
    $('#'+formId)[0].reset();

    $('#'+formId+' .select2').each(function( index, value ) {
        $(this).val('').trigger('change');
    });

    $('#'+formId+' div.has-error').each(function(){
		$(this).removeClass('has-error');
	});
}

function loadQuestions(id_ensemble, del = false)
{
    $.ajax({
        url: pathServer + "leads/ws/getQuestions/"+id_ensemble,
        type: "GET",
        dataType: "json",
    }).done(function(res) {
        if(res.response == 'OK'){

            var html = ''
            res.data.forEach(function(question){
                html += '<div class="question" id="question-'+question.id+'">';
                html += '<div class="col-md-3">['+question.question_type.label+']</div>';
                html += '<div class="col-md-7">'+question.name+'</div>';
                html += '<div class="col-md-2 text-right">'
                html += '<a href="" class="edit-question" data-id="'+question.id+'"><i title="Modifica domanda" class="fa fa-pencil"></i></a>';
                html += '<a href="" class="delete-question" data-id="'+question.id+'"><i title="Cancella domanda" class="fa fa-trash"></i></a>';
                html += '</div>';
                html += '</div>';
            });
    
            $('#questionsList').html(html);

            //se le domande vengono caricate dopo una delete aggiorno l'ordine
            if(del){
                $('#questionsList').trigger('sortupdate');
            }
        }else{
            alert(res.msg);
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    });        
}

function checkCookieForLoader(name, value) {
    var cookie = getCookie(name);

    if (cookie == value) {
        $('#template-spinner').hide();
        document.cookie = 'downloadStarted=0;path=/';
    } else {
        setTimeout(function () { checkCookieForLoader(name, value); }, 300);
    }
}