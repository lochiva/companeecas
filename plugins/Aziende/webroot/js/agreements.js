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

    //Formattazione campi decimal (costi)
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

    /*###################################################### OSPITI ########################################################*/

    //Tabella ospiti
    if ($('#table-agreements').length > 0) {
        $('#table-agreements').tablesorter({
            theme: 'bootstrap',
            headerTemplate: '{content} {icon}',
            widthFixed: false,
            widgets: ['zebra', 'cssStickyHeaders' , 'columns', 'filter', 'uitheme'],
            widgetOptions: {
                filter_functions:{},
                filter_selectSource: {}
            },
        }).tablesorterPager({
            container: $("#pager-agreements"),

            ajaxUrl: pathServer + 'aziende/ws/getAgreements/'+azienda_id+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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

    $('.agreement-sede-check').change(function() {
        var id = $(this).attr('data-id');
        if ($(this).is(':checked')) {
            $('#inputSedeCapacity'+id).prop('disabled', false);
            $('#inputSedeCapacity'+id).addClass('required');
        } else {
            $('#inputSedeCapacity'+id).prop('disabled', true);
            $('#inputSedeCapacity'+id).removeClass('required');
            $('#inputSedeCapacity'+id).val('');
        }
    });

    $('#saveAgreement').click(function(){
        if(formValidation('formAgreement')){
            var formData= new FormData($('#formAgreement')[0]);
            
            $.ajax({
                url : pathServer + "aziende/Ws/saveAgreement",
                type: "POST",
                processData: false,
                contentType: false,
                dataType: "json",
                data: formData
            }).done(function (res) {
                if(res.response == "OK"){
                    $('#table-agreements').trigger('update');
                    $('#modalAgreement').modal('hide');
                    if (res.data) {
                        alert('Attenzione! Una o più strutture non sono associate ad una convenzione.');
                        //Aggiorna conteggio notifiche
                        if (role == 'admin') {
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
                    }
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta, stato, errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
            });
        }
    });

    // Preset modale all'apertura per nuova convenzione
    $('#newAgreement').click(function() {
        $('#inputCapacityIncrement0').prop('checked', true);
    });
});

$(document).on('click', '.edit-agreement', function(){
    var id = $(this).attr('data-id');
    $.ajax({
        url : pathServer + "aziende/Ws/getAgreement/"+id,
        type: "GET",
        dataType: "json"
    }).done(function (res) {
        if(res.response == "OK"){
            $('#agreementId').val(res.data.id);
            if (role == 'admin') {
                $('#inputApproved').prop('checked', res.data.approved);
            }
            $('#inputProceduraAffidamento').val(res.data.procedure_id);
            $('#inputDateAgreement').datepicker('setDate', res.data.date_agreement);
            $('#inputDateAgreementExpiration').datepicker('setDate', res.data.date_agreement_expiration);
            $('#inputDateExtensionExpiration').datepicker('setDate', res.data.date_extension_expiration);
            $('#inputGuestDailyPrice').val(res.data.guest_daily_price).trigger('change');
            $('#inputCapacityIncrement'+res.data.capacity_increment).prop('checked', true);
            res.data.agreements_to_sedi.forEach(function(sede) {
                if (sede.active) {
                    $('#inputSedeCheck'+sede.sede_id).prop('checked', true);
                    $('#inputSedeCapacity'+sede.sede_id).val(sede.capacity);
                    $('#inputSedeCapacity'+sede.sede_id).prop('disabled', false);
                    $('#inputSedeCapacity'+sede.sede_id).addClass('required');
                } else {
                    $('#inputSedeCheck'+sede.sede_id).prop('checked', true);
                    $('#inputSedeCheck'+sede.sede_id).prop('disabled', true);
                    $('#inputSedeCheck'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                    $('#inputSedeCapacity'+sede.sede_id).val(sede.capacity);
                    $('#inputSedeCapacity'+sede.sede_id).prop('disabled', true);
                    $('#inputSedeCapacity'+sede.sede_id).removeClass('required');
                    $('#inputSedeCapacity'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                }
            })

            // Se utente di ruolo ente e convenzione approvata, disabilito form e mostro messaggio
            if (role == 'ente' && res.data.approved) {
                $('.approved-message').show();
                disableApprovedModal();
            }

            $('#modalAgreement').modal('show');
        }else{
            alert(res.msg);
        }
    }).fail(function(richiesta, stato, errori) {
        alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
    });
});

$(document).on('hidden.bs.modal', '#modalAgreement', function() {
    clearModal();
});

function clearModal(){
    $('.approved-message').hide();
	$('#agreementId').val("");
    $('#inputProceduraAffidamento').val("");
    $('#inputProceduraAffidamento').prop("disabled", false);
    $('#inputProceduraAffidamento').removeClass('disabled-approved');
    $('#inputDateAgreement').val("");
    $('#inputDateAgreement').prop("disabled", false);
    $('#inputDateAgreement').removeClass('disabled-approved');
    $('#inputDateAgreementExpiration').val("");
    $('#inputDateAgreementExpiration').prop("disabled", false);
    $('#inputDateAgreementExpiration').removeClass('disabled-approved');
    $('#inputDateExtensionExpiration').val("");
    $('#inputDateExtensionExpiration').prop("disabled", false);
    $('#inputDateExtensionExpiration').removeClass('disabled-approved');
    $('#inputGuestDailyPrice').val("");
    $('#inputGuestDailyPrice').prop("disabled", false);
    $('#inputGuestDailyPrice').removeClass('disabled-approved');
    $('#inputCapacityIncrement20').prop("checked", false);
    $('#inputCapacityIncrement20').prop("disabled", false);
    $('#inputCapacityIncrement20').removeClass('disabled-approved');
    $('#inputCapacityIncrement50').prop("checked", false);
    $('#inputCapacityIncrement50').prop("disabled", false);
    $('#inputCapacityIncrement50').removeClass('disabled-approved');

    $('.agreement-sede-check').each(function() {
        $(this).prop('checked', false);
        $(this).prop("disabled", false);
        $(this).prop("title", '');
    });

    $('.agreement-sede-capacity').each(function() {
        $(this).val("");
        $(this).removeClass("required");
        $(this).prop("disabled", true);
        $(this).prop("title", '');
        $(this).removeClass('disabled-approved');
    });
}

function disableApprovedModal() {
    $('#inputProceduraAffidamento').prop("disabled", true);
    $('#inputProceduraAffidamento').addClass('disabled-approved');
    $('#inputDateAgreement').prop("disabled", true);
    $('#inputDateAgreement').addClass('disabled-approved');
    $('#inputDateAgreementExpiration').prop("disabled", true);
    $('#inputDateAgreementExpiration').addClass('disabled-approved');
    $('#inputDateExtensionExpiration').prop("disabled", true);
    $('#inputDateExtensionExpiration').addClass('disabled-approved');
    $('#inputGuestDailyPrice').prop("disabled", true);
    $('#inputGuestDailyPrice').addClass('disabled-approved');
    $('#inputCapacityIncrement20').prop("disabled", false);
    $('#inputCapacityIncrement20').removeClass('disabled-approved');
    $('#inputCapacityIncrement50').prop("disabled", false);
    $('#inputCapacityIncrement50').removeClass('disabled-approved');

    $('.agreement-sede-check').each(function() {
        $(this).prop("disabled", true);
    });

    $('.agreement-sede-capacity').each(function() {
        $(this).prop("disabled", true);
        $(this).addClass('disabled-approved');
    });
}