$.fn.datepicker.dates['it'] = {
    days: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"],
    daysShort: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
    today: "Today",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};

$(document).ready(function(){
    
    $('.datepicker').datepicker({
        language: 'it',
        autoclose: true,
        todayHighlight: true,
    });

    //solo numeri in input number
    $(document).on('keydown', '.number-integer, .number-decimal', function (e) {
        if($(this).hasClass('number-integer')){
            // Allow: backspace, delete, tab, escape and enter 
            var accepted = [46, 8, 9, 27, 13]
        }else{
            // Allow: backspace, delete, tab, escape, enter, . and ,
            var accepted = [46, 8, 9, 27, 13, 110, 190, 188]
        }
        
        if ($.inArray(e.keyCode, accepted) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {

                if (($.inArray(e.keyCode, [110, 190]) !== -1 && (this.value.split('.').length === 2 || this.value.split(',').length === 2)) || 
                    (e.keyCode == 188 && (this.value.split(',').length === 2 || this.value.split('.').length === 2))) {
                    return false;
                }else{
                    return;
                }
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    //Formattazione campi decimal (costi orari)
    $(document).on('change', '.number-decimal', function(event) {
        var val = $(this).val().replace(',', '.');
        var valParsed = parseFloat(val);
        if(valParsed == val || valParsed + '.00' == val){
            var value = parseFloat($(this).val().replace(',', '.')).toFixed(2);
        }else{
            var value = $(this).val();
        }
        
        $(this).val(value.replace('.', ',')).trigger('keyup');
    });

    //Tabella questionari
    $('#table-surveys').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
                '.status-filter':{
                    'Pubblicato': function(e,n,f,i,$r){return e===f;},
                    'Pubblicato (congelato)': function(e,n,f,i,$r){return e===f;},
                    'Bozza': function(e,n,f,i,$r){return e===f},
                    'Annullato': function(e,n,f,i,$r){return e===f}
                }
            }
        },
    }).tablesorterPager({
        container: $("#pager-surveys"),

        ajaxUrl: pathServer + 'surveys/ws/getSurveys/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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

    //Annulla survey
    $(document).on('click', '.delete-survey', function(){
        if(confirm('Attenzione! Si è sicuri di voler annullare il questionario?')){
            var id = $(this).attr('data-id');
            $.ajax({
                url: pathServer + "surveys/ws/deleteSurvey",
                type: "POST",
                dataType: "json",
                data: {id: id}
            }).done(function(res) {
                if(res.response == 'OK'){
                    alert(res.msg);
                    $('#table-surveys').trigger('update');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    if($('#table-interviews').length > 0){
    //Tabella interviste
    $('#table-interviews').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
                '.status-filter':{
                    'Compilazione': function(e,n,f,i,$r){return e===f;},
                    'Firmata': function(e,n,f,i,$r){return e===f;},
                },
                '.valid-filter':{
                    'Sì': function(e,n,f,i,$r){return e===f;},
                    'No': function(e,n,f,i,$r){return e===f;},
                }
            }
        },
    }).tablesorterPager({
        container: $("#pager-interviews"),

        ajaxUrl: pathServer + 'surveys/ws/getInterviews/'+id_survey+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
    }

    //Tabella enti gestori
    if($('#table-managing-entities').length > 0){
    $('#table-managing-entities').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
            }
        },
    }).tablesorterPager({
        container: $("#pager-managing-entities"),

        ajaxUrl: pathServer + 'surveys/ws/getManagingEntities/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
    }

    //Tabella strutture enti gestori
    if($('#table-structures').length > 0){
    $('#table-structures').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
            }
        },
    }).tablesorterPager({
        container: $("#pager-structures"),

        ajaxUrl: pathServer + 'surveys/ws/getStructures/'+idManagingEntity+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
    }

    if($('#table-interviews-user').length > 0){
    //Tabella interviste
    $('#table-interviews-user').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
                '.status-filter':{
                    'Compilazione': function(e,n,f,i,$r){return e===f;},
                    'Firmata': function(e,n,f,i,$r){return e===f;},
                },
                '.valid-filter':{
                    'Sì': function(e,n,f,i,$r){return e===f;},
                    'No': function(e,n,f,i,$r){return e===f;},
                }
            }
        },
    }).tablesorterPager({
        container: $("#pager-interviews-user"),

        ajaxUrl: pathServer + 'surveys/ws/getInterviewsUser/'+id_managing_entity+'/'+id_structure+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
    }

    $('#addInterview').click(function(){
        var entity = $(this).attr('data-entity');
        var structure = $(this).attr('data-structure');

        $.ajax({
            url: pathServer + "surveys/ws/verifySurveysStructure/"+entity+'/'+structure,
            type: "GET",
            dataType: "json",
        }).done(function(res) {
            if(res.response == 'OK'){
                if(res.data.length > 1){
                    var html = '<div style="display: grid;">';
                    html += '<input hidden id="managingEntity" value="'+entity+'">';
                    html += '<input hidden id="structure" value="'+structure+'">';
                    res.data.forEach(function(survey){
                        html += '<div class="col-md-12" style="margin-bottom: 10px;">';     
                        html += '<input type="radio" name="survey_choices" value="'+survey.id+'"> '+survey.title;
                        html += '</div>';
                    })
                    html += '</div>';
                    $('#surveyChoices').html(html);

                    $('input[name="survey_choices"]').first().trigger('click');

                    $('#modalSurveyChoice').modal('show');
                }else{
                    window.location.href = pathServer + 'surveys/surveys/answers/?survey='+res.data[0].id+'&managentity='+entity+'&structure='+structure;
                }
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

    $(document).on('change', 'input[name="survey_choices"]', function(){
        var entity = $('#managingEntity').val();
        var structure = $('#structure').val();

        var href = pathServer + 'surveys/surveys/answers/?survey='+$(this).val()+'&managentity='+entity+'&structure='+structure;

        $('#fillInterview').attr('href', href);
    });

    $(document).on('hidden.bs.modal', function(){ 
        $(this).find('.click_tab_1').trigger("click");
        $(this).find('#clicked_index').val('');
    });

    //modale tooltip domanda
    $(document).on('click', '.question-tooltip', function(){
        var text_tooltip = $(this).parent().find('.text-question-tooltip').html(); 
        $('#modalTooltipQuestion #tooltipQuestion').html(text_tooltip);
    });


    //tasti azione per elemento e domanda sull'hover
    $(document).on('mouseover', '.element-div, .question-div', function() { 
        $(this).find('.action-buttons').show();
    });

    $(document).on('mouseout', '.element-div, .question-div', function() { 
        $(this).find('.action-buttons').hide();
    });

    //tasto delete ente gestore sull'hover
    $(document).on('mouseover', '.partner-structures', function() { 
        $(this).find('.delete-inspec-partner').show();
    });

    $(document).on('mouseout', '.partner-structures', function() { 
        $(this).find('.delete-inspec-partner').hide();
    });

    //Scarica ispezione in pdf
	$(document).on('click','.interview-pdf', function(){
		var interview = $(this).attr('data-id');
		$('#template-spinner').show();
		document.cookie = 'downloadStarted=0;path=/';
		window.location = pathServer + "surveys/surveys/interviewPdf/"+interview;
        checkCookieForLoader('downloadStarted', '1');
    });
    
    //Clonazione questionario
    $(document).on('click', '.survey-clone', function(){
        var survey_id = $(this).attr('data-id');

        $.ajax({
            url: pathServer + "surveys/ws/cloneSurvey",
            type: "POST",
            dataType: "json",
            data: {survey_id: survey_id}
        }).done(function(res) {
            if(res.response == 'OK'){
                $('#table-surveys').trigger('update');
                alert(res.msg);
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

    //Clonazione ispezione
    $(document).on('click', '.interview-clone', function(){
        var interview_id = $(this).attr('data-id');

        $.ajax({
            url: pathServer + "surveys/ws/cloneInterview",
            type: "POST",
            dataType: "json",
            data: {interview_id: interview_id}
        }).done(function(res) {
            if(res.response == 'OK'){
                if($('#table-interviews').length > 0){
                    $('#table-interviews').trigger('update');
                }
                if($('#table-interviews-user').length > 0){
                    $('#table-interviews-user').trigger('update');
                }

                alert(res.msg);
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

    if($('#table-chapters').length > 0){
        //Tabella capitoli
        $('#table-chapters').tablesorter({
            theme: 'bootstrap',
            headerTemplate: '{content} {icon}',
            widthFixed: false,
            widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
            widgetOptions: {

            },
            headers: { 2: { filter: false, sorter:false} }
        }).tablesorterPager({
            container: $(".pager-chapters"),
    
            ajaxUrl: pathServer + 'surveys/ws/getChapters/?{filterList:filter}&{sortList:column}&size={size}&page={page}',
    
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
    }

    // Chiusura modale capitolo
    $('#modalChapter').on('hidden.bs.modal', function(){
        $('#formChapter')[0].reset();
        $('#chapterContent').html('');
    });

    //Salvataggio capitolo
    $('#saveChapter').click(function(){
        if(formValidation('formChapter')){ 
            var formData = new FormData($('#formChapter')[0]);
            formData.append('content', $('#chapterContent').val());
            
            $.ajax({
                url: pathServer + "surveys/ws/saveChapter",
                type: "POST",
                processData: false,
                contentType: false,
                dataType: "json",
                data: formData
            }).done(function(res) {
                if(res.response == 'OK'){
                    $('#table-chapters').trigger('update');
                    $('#modalChapter').modal('hide');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //Modifica capitolo
    $(document).on('click', '.edit-chapter', function(){
        var chapter_id = $(this).attr('data-id');

        $.ajax({
            url: pathServer + "surveys/ws/getChapter/" + chapter_id,
            type: "GET",
            dataType: "json",
        }).done(function(res) {
            if(res.response == 'OK'){
                $('#modalChapter #chapterId').val(res.data.id);
                $('#modalChapter #chapterName').val(res.data.name);
                $('#modalChapter #chapterOrdering').val(res.data.ordering);
                $('#modalChapter #chapterContent').html(res.data.content);

                $('#modalChapter').modal('show');
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });        
    });

    //Cancellazione capitolo
    $(document).on('click', '.delete-chapter', function(e){
        e.preventDefault();

        if(confirm('Si è sicuri di voler eliminare il capitolo?')){      
            var chapter_id = $(this).attr('data-id');

            $.ajax({
                url: pathServer + "surveys/ws/deleteChapter",
                type: "POST",
                dataType: "json",
                data: {id: chapter_id}
            }).done(function(res) {
                if(res.response == 'OK'){
                    $('#table-chapters').trigger('update');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //Non triggare confirm per lasciare pagina se cliccato salva
    $('.save-survey-exit, .save-survey-stay, .save-interview-exit, .save-interview-stay, .interview-pdf').click(function(){
        beforeunload = false;
    });

});