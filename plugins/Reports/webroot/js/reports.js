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

    //Tabella segnalazioni
    $('#table-reports').tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widthFixed: false,
        widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap'],
        widgetOptions: {
            filter_functions:{
                '.status-filter':{
                    'Aperto': function(e,n,f,i,$r){return e===f;},
                    'Chiuso': function(e,n,f,i,$r){return e===f;},
                    'Trasferimento': function(e,n,f,i,$r){return e===f},
                    'Trasferito': function(e,n,f,i,$r){return e===f}
                }
            }
        },
    }).tablesorterPager({
        container: $("#pager-reports"),

        ajaxUrl: pathServer + 'reports/ws/getReports/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

        // modify the url after all processing has been applied
        customAjaxUrl: function(table, url) {
            // manipulate the url string as you desire
        // url += '&cPage=' + window.location.pathname;
        // trigger my custom event
            var showTransferAccepted = $('#showTransferAccepted').is(':checked');
            url += '&showTransferAccepted='+showTransferAccepted;

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
            $('#'+$(this).attr("id")+' tbody').append('<tr><td colspan="'+$('#'+$(this).attr("id")).find('thead th').length+'" style="text-align:center;"><span id="no-result">Nessuna segnalazione trovata.</span></td></tr>');
        }
        calculateTableDropdownPosition();

    }).bind('pagerChange', function(e, options){

        var tableId = e.currentTarget.id;
        var pageSize = localStorage.getItem("tablesorter-pager-temp");

        if(pageSize != undefined && pageSize != null){
            pageSize = JSON.parse(pageSize);
            if(pageSize[tableId] != undefined && pageSize[tableId] != null){
                 $('#'+tableId).trigger('pageAndSize', pageSize[tableId] );
                 delete pageSize[tableId];
                 pageSize = JSON.stringify(pageSize);
                 localStorage.setItem("tablesorter-pager-temp",pageSize);
            }
        }
    });

    //Esportazione segnalazioni
    $('#export-reports').click(function(){
        $('#template-spinner').show();
        document.cookie = 'downloadStarted=0;path=/';
        var filters = $.tablesorter.getFilters( $('#table-reports') );
        window.location = pathServer + "reports/reports/exportReports/?filters="+filters;
        checkCookieForLoader('downloadStarted', '1');
    });

    //Cancellazione documento
    $(document).on('click', '.delete-report', function(e){
        e.preventDefault();
        if(confirm('Si è sicuri di voler eliminare la segnalazione? Questa operazione non sarà reversibile.')){
            var id = $(this).attr('data-id');
            $.ajax({
                url : pathServer + 'reports/ws/deleteReport/',
                type: "POST",
                data: {id: id},
                dataType: "json"
            }).done(function (res) {
                if(res.response == 'OK'){
                    $("#table-reports").trigger('update');
                }else{
                    alert(res.msg);
                }
            }).fail(function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    //Non triggerare confirm per lasciare pagina se cliccato salva
    /*$('.save-report-exit, .save-report-stay').click(function(){
        beforeunload = false;
    });*/
    
    $('.select-user-text').each(function(){
		$(this).hide();
    });
    
    //modale tooltip domande scheda
    $(document).on('click', '.question-tooltip', function(){
        var text_tooltip = $(this).parent().find('.text-question-tooltip').html(); 
        $('#modalTooltipQuestion #tooltipQuestion').html(text_tooltip);
    });


    if($('#table-documents').length > 0){
		//Tabella documenti
		$("#table-documents").tablesorter({
			theme: 'bootstrap',
			headerTemplate: '{content} {icon}',
			widgets : [ 'zebra', 'cssStickyHeaders' , 'columns', 'filter', 'uitheme' ],
			widgetOptions: {
				cssStickyHeaders_offset        : $('header:first').height(),
				cssStickyHeaders_addCaption    : false,
				// jQuery selector or object to attach sticky header to
				cssStickyHeaders_attachTo      : null,
				cssStickyHeaders_filteredToTop : false,
				cssStickyHeaders_zIndex        : 99000,
			},
			headers: {}
		}).tablesorterPager({
			container: $("#pager-documents"),
			ajaxUrl : pathServer + 'reports/ws/getDocuments/'+$('#reportId').val()+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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

			ajaxObject: {
				dataType: 'json'
			},

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

				}else{

				}
				$('#template-spinner').hide();
			},
			output: '{startRow} to {endRow} ({totalRows})',
			updateArrows: true,
			page: 0,
			size: 10,
			fixedHeight: false,
			removeRows: false,

			// css class names of pager arrows
			cssNext        : '.next',  // next page arrow
			cssPrev        : '.prev',  // previous page arrow
			cssFirst       : '.first', // go to first page arrow
			cssLast        : '.last',  // go to last page arrow
			cssPageDisplay : '.pagedisplay', // location of where the "output" is displayed
			cssPageSize    : '.pagesize', // page size selector - select dropdown that sets the "size" option
			cssErrorRow    : 'tablesorter-errorRow', // error information row

			// class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
			cssDisabled    : 'disabled' // Note there is no period "." in front of this class name

		}).bind("pagerComplete pagerInitialized",function(e, options){

			if(parseInt(options.totalRows) == 0 && $('span#no-result-documents').length == 0){
				$('#'+$(this).attr("id")+' tbody').append('<tr><td colspan="'+$('#'+$(this).attr("id")).find('thead th').length+'" class="text-center"><span id="no-result-documents">Nessun documento disponibile.</span></td></tr>');
			}
			calculateTableDropdownPosition();

			var hash = window.location.hash.substring(1);
			if(hash){
				document.getElementById(hash).scrollIntoView(); 
			}

		}).bind('pagerChange', function(e, options){

			var tableId = e.currentTarget.id;
			var pageSize = localStorage.getItem("tablesorter-pager-temp");

			if(pageSize != undefined && pageSize != null){
				pageSize = JSON.parse(pageSize);
				if(pageSize[tableId] != undefined && pageSize[tableId] != null){
					$('#'+tableId).trigger('pageAndSize', pageSize[tableId] );
					delete pageSize[tableId];
					pageSize = JSON.stringify(pageSize);
					localStorage.setItem("tablesorter-pager-temp",pageSize);
				}
			}
		});
	}

    $('#showTransferAccepted').change(function(){
        $('#table-reports').trigger('update');
    });

});

//Scarica documento
$(document).on('click', '.download-document', function(e){
	e.preventDefault();
	var id = $(this).attr('data-id');
	$('#template-spinner').show();
	document.cookie = 'downloadStarted=0;path=/';    
	window.location = pathServer + 'reports/ws/downloadDocument/' + id;
	checkCookieForLoader('downloadStarted', '1');
});

//Cancellazione documento
$(document).on('click', '.delete-document', function(e){
	e.preventDefault();
	if(confirm('Si è sicuri di voler eliminare il documento? Questa operazione non sarà reversibile.')){
		var id = $(this).attr('data-id');
		$.ajax({
			url : pathServer + 'reports/ws/deleteDocument/',
			type: "POST",
			data: {id: id},
			dataType: "json"
		}).done(function (res) {
			if(res.response == 'OK'){
				$("#table-documents").trigger('update');
			}else{
				alert(res.msg);
			}
		}).fail(function (richiesta,stato,errori) {
			alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
		});
	}
});

//Pdf segnalazione
$(document).on('click', '.download-report-pdf', function(e){
	e.preventDefault();
	var id = $(this).attr('data-id');
	$('#template-spinner').show();
	document.cookie = 'downloadStarted=0;path=/';    
	window.location = pathServer + 'reports/reports/reportPdf/' + id;
	checkCookieForLoader('downloadStarted', '1');
});