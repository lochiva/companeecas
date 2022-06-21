$(document).ready(function(){

    //solo numeri in input number
    $('.number-integer, .number-decimal').keydown(function (e) {
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
             // Allow: Ctrl/cmd+V
             (e.keyCode == 86 && (e.ctrlKey === true || e.metaKey === true)) ||
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

    /*###################################################### OSPITI ########################################################*/

    //Tabella ospiti
    if ($('#table-guests').length > 0) {
        $('#table-guests').tablesorter({
            theme: 'bootstrap',
            headerTemplate: '{content} {icon}',
            widthFixed: false,
            widgets: ['zebra', 'cssStickyHeaders' , 'columns', 'filter', 'saveSort', 'uitheme'],
            widgetOptions: {
                saveSort: true,
                filter_saveFilters : true,

                filter_functions:{
                    '.filter-sex': {
                        'F': function(e,n,f,i,$r){return e===f},
                        'M': function(e,n,f,i,$r){return e===f}
                    },
                    '.filter-draft': {
                        'Sì': function(e,n,f,i,$r){return e===f},
                        'No': function(e,n,f,i,$r){return e===f}
                    },
                    '.filter-suspended': {
                        'Sì': function(e,n,f,i,$r){return e===f},
                        'No': function(e,n,f,i,$r){return e===f}
                    }
                },
                filter_selectSource: {
                    '.filter-status': statusesList
                },

                filter_formatter: {
                    '.filters-reset': function ($cell, indx) {
                        return $cell.html('<button type="button" class="btn btn-default btn-block btn-reset-filters-guests"><i class="fa fa-eraser"></i></button>');
                    },
                },
            },
        }).tablesorterPager({
            container: $("#pager-guests"),

            ajaxUrl: pathServer + 'aziende/ws/getGuests/'+sede_id+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

            // modify the url after all processing has been applied
            customAjaxUrl: function(table, url) {
                // manipulate the url string as you desire
                url += '&showOld=' + $('#showOld').is(':checked');
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

                /*if(data.creation_enabled){
                    $('.warning-out-of-spots').hide();
                    $('#newGuest').removeClass('disabled');
                }else{
                    $('.warning-out-of-spots').show();
                    $('#newGuest').addClass('disabled');
                }*/

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

        //resetto filtri al click
		$('.btn-reset-filters-guests').click(function () { 
            $("#table-guests").trigger('filterReset').trigger('sortReset'); 
            $('#showOld').prop('checked', false).trigger('change');
            return false; 
        });
    }

    $('#showOld').prop('checked', parseInt(localStorage.getItem('table-guests-show-old')));

    $('#showOld').change(function() {
        $('#table-guests').trigger('update');
        localStorage.setItem('table-guests-show-old', $('#showOld').is(':checked') ? 1 : 0);
    });
    
    $(document).on('click', '.delete-guest', function(){
        var guest_id = $(this).attr('data-id');

        if((confirm("Attenzione! Si è sicuri di voler eliminare l'ospite?"))){
            $.ajax({
                url: pathServer + "aziende/ws/deleteGuest",
                type: "POST",
                dataType: "json",
                data: {id: guest_id}
            }).done(function(res) {
                if(res.response == 'OK'){
                    alert(res.msg);
                    $('#table-guests').trigger('update');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    $('#searchGuest').select2({
		language: 'it',
		width: '100%',
		placeholder: 'Cerca un ospite',
		closeOnSelect: true,
		dropdownParent: $("#divSearchGuest"),
		ajax: {
			url: pathServer+'aziende/ws/autocompleteGuests',
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
    
    $('#viewGuest').click(function(){ 
        if($('#searchGuest').val() == '' || $('#searchGuest').val() == null){
            alert('Selezionare un ospite per procedere.');
            return false;
        }

        var ids = $('#searchGuest').val().split('|');
        var sede_id = ids[0].split(',');
        var guest_id = ids[1];

        if(sede_id.length > 1){
            $.ajax({
                url: pathServer + "aziende/ws/getSediForSearchGuest/"+guest_id,
                type: "GET",
                dataType: "json",
            }).done(function(res) {
                if(res.response == 'OK'){
                    var html = '';
                    res.data.forEach(function(guest){  
                        html += '<tr class="clickable" onclick="window.location = \''+pathServer+'aziende/guests/guest?sede='+guest.sede_id+'&guest='+guest.id+'\';">';
                        html += '<td>'+guest.a.denominazione+'</td>';
                        html += '<td>'+guest.s.indirizzo+' '+guest.s.num_civico+', '+guest.c.des_luo+' ('+guest.c.s_prv+')'+'</td>';
                        html += '<td>'+ new Date(guest.check_in_date).toLocaleDateString('it-IT', {year: 'numeric', month: '2-digit', day: '2-digit'});+'</td>';
                        html += '<td>'+ (guest.check_out_date != null ? new Date(guest.check_out_date).toLocaleDateString('it-IT', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '')+'</td>';
                        html += '<td>'+guest.gs.name+'</td>';
                        html += '</tr>';
                    })

                    $('#tableGuestsChoices tbody').html(html);

                    $('#modalGuestSelection').modal('show');
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }else{
            window.location = pathServer+'aziende/guests/guest?sede='+sede_id+'&guest='+guest_id;
        }
    });

    //popolo hidden per box allegati
	if($('#boxAttachments').length > 0){
        $('#contextForAttachment').html('guests');
        var url = new URL(window.location.href);
        var guestId = url.searchParams.get("guest");
		$('#idItemForAttachment').html(guestId);
    }

    //Non triggare confirm per lasciare pagina se cliccato salva
    $('.save-guest-exit, .save-guest-stay').click(function(){
        beforeunload = false;
    });


    /*###################################################### NOTIFICHE OSPITI ########################################################*/

    //Tabella ospiti
    if ($('#table-guests-notifications').length > 0) {
        $('#table-guests-notifications').tablesorter({
            theme: 'bootstrap',
            headerTemplate: '{content} {icon}',
            widthFixed: false,
            widgets: ['zebra', 'cssStickyHeaders' , 'columns', 'filter', 'uitheme'],
            widgetOptions: {
                filter_functions:{
                    '.filter-done': {
                        'Sì': function(e,n,f,i,$r){return e===f},
                        'No': function(e,n,f,i,$r){return e===f}
                    }
                },
                filter_selectSource: {}
            },
        }).tablesorterPager({
            container: $("#pager-guests-notifications"),

            ajaxUrl: pathServer + 'aziende/ws/getGuestsNotifications/'+ente_type+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

            // modify the url after all processing has been applied
            customAjaxUrl: function(table, url) {
                // manipulate the url string as you desire
                url += '&all=' + $('#showAllNotifications').is(':checked');
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

                /*if(data.creation_enabled){
                    $('.warning-out-of-spots').hide();
                    $('#newGuest').removeClass('disabled');
                }else{
                    $('.warning-out-of-spots').show();
                    $('#newGuest').addClass('disabled');
                }*/

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

    //mostra tutte le notifiche
    $('#showAllNotifications').change(function() {
        $('#table-guests-notifications').trigger('update');
    });

    //segna tutte le notifiche come gestite
    $('#markAllNotificationsDone').click(function() {
        if (confirm('Si è sicuri di voler marcare tutte le notifiche come "gestite"?')) {
            var url = pathServer + 'aziende/ws/saveAllGuestsNotificationsDone/'+ente_type;
            //Filtri
            var filterList = $.tablesorter.getFilters($('#table-guests-notifications'));
            var filters = [];
            $.each(filterList, function(i, v) {
                if (v) {
                    filters.push('filter[' + i + ']=' + encodeURIComponent(v));
                }
            });
            url += '?'+(filters.length ? filters.join('&') : 'filter');
            //Ordinamento
            var sortList = $('#table-guests-notifications')[0].config.sortList;
            var columns = [];
            $.each(sortList, function(i, v) {
                columns.push('column[' + v[0] + ']=' + v[1]);
            });
            url += '&'+(columns.length ? columns.join('&') : 'column');
            //Numero righe per pagina
            var size = $('#table-guests-notifications')[0].config.pager.size;
            url += '&size='+size;
            //Numero pagina
            var page = $('#table-guests-notifications')[0].config.pager.page;
            url += '&page='+page;
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
            }).done(function(res) {
                if (res.response == 'OK') {
                    $('#table-guests-notifications').trigger('update');
    
                    //Aggiorna conteggio notifiche
                    if (ente_type == 1) {
                        $.ajax({
                            url : pathServer + "aziende/ws/getGuestsNotificationsCount/1",
                            type: "GET",
                            dataType: "json"
                        }).done(function(res) {
                            if(res.response == 'OK'){
                                var count = res.data;
                                if(count > 0){
                                    $('.guests_notify_count_label').html(count);
                                } else {
                                    $('.guests_notify_count_label').html('');
                                }
                            }
                        }).fail(function(richiesta,stato,errori){
                            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                        });
                    }
                } else {
                    alert(res.msg);
                }
            }).fail(function(richiesta, stato, errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
            });
        }
    });

    //salvataggio check gestito
    $(document).on('change', '.inline-check-done', function(e) {
        var field = $(this);

        if (field.is(':checked')) {
            var value = 1;
        } else {
            var value = 0;
        }

        if (value == 0 || (value == 1 && confirm('Si è sicuro di voler segnare come "gestita" la notifica?'))) {
            data = {
                id: field.attr('data-id'),
                value: value
            };
    
            $.ajax({
                url: pathServer + 'aziende/ws/saveGuestNotificationDone',
                type: "POST",
                dataType: 'json',
                data: data
            }).done(function(res) {
                if (res.response == 'OK') {
                    $('#table-guests-notifications').trigger('update');
    
                    //Aggiorna conteggio notifiche
                    if (ente_type == 1) {
                        $.ajax({
                            url : pathServer + "aziende/ws/getGuestsNotificationsCount/1",
                            type: "GET",
                            dataType: "json"
                        }).done(function(res) {
                            if(res.response == 'OK'){
                                var count = res.data;
                                if(count > 0){
                                    $('.guests_notify_count_label').html(count);
                                } else {
                                    $('.guests_notify_count_label').html('');
                                }
                            }
                        }).fail(function(richiesta,stato,errori){
                            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                        });
                    }
                } else {
                    field.prop('checked', !value);
                    alert(res.msg);
                }
            }).fail(function(richiesta, stato, errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
            });
        } else {
            field.prop('checked', !value);
        }
    });

});