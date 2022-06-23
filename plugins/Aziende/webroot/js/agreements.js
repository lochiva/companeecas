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

    //Attivazione/disattivazione sede
    $('.agreement-sede-check').change(function() {
        var id = $(this).attr('data-id');
        if ($(this).is(':checked')) {
            $('#inputSedeCapacity'+id).prop('disabled', false);
            $('#inputSedeCapacity'+id).addClass('required');
            if ($('input[name="capacity_increment"]:checked').val() > 0) {
                $('#inputSedeCapacityIncrement'+id).prop('disabled', false);
            }
        } else {
            $('#inputSedeCapacity'+id).prop('disabled', true);
            $('#inputSedeCapacity'+id).removeClass('required');
            $('#inputSedeCapacity'+id).val('');
            $('#inputSedeCapacityIncrement'+id).prop('disabled', true);
            $('#inputSedeCapacityIncrement'+id).val('');
        }

        //Aggiornamento totali
        computeTotalCapacity();
        computeMaxCapacityIncrement();
        computeTotalCapacityIncrement();
    });

    //Cambio percentuale incremento posti
    $('input[name="capacity_increment"]').change(function() {
        if ($(this).val() > 0) {
            $('.agreement-sede-check:checked').each(function(index, element) {
                var id = $(element).attr('data-id');
                $('#inputSedeCapacityIncrement'+id).prop('disabled', false);
            });
        } else {
            $('.agreement-sede-check:checked').each(function(index, element) {
                var id = $(element).attr('data-id');
                $('#inputSedeCapacityIncrement'+id).prop('disabled', true);
                $('#inputSedeCapacityIncrement'+id).val('');
            });
        }

        //Aggiornamento totali
        computeMaxCapacityIncrement();
        computeTotalCapacityIncrement();
    })

    //Cambio posti da convenzione sede
    $('.agreement-sede-capacity').change(function() {
        //Aggiornamento totali
        computeTotalCapacity();
        computeMaxCapacityIncrement();
        computeTotalCapacityIncrement();
    });

    //Cambio posti da incremento sede
    $('.agreement-sede-capacity-increment').change(function() {
        //Aggiornamento totali
        computeTotalCapacityIncrement();
    });

    $('#saveAgreement').click(function(){
        if(formValidation('formAgreement')){
            //Validazione campi
            var valid = true;
            var firstElem = '';
            var errorMsg = '';

            //Validazione CIG
            $('#inputCig').parentsUntil('.form-group').parent().removeClass('has-error');
            var cig = $('#inputCig').val();
            if (cig.length > 0) {
                var regex = new RegExp('[0-9]{7}[0-9A-F]{3}|[V-Z]{1}[0-9A-F]{9}|[A-U]{1}[0-9A-F]{9}');
                if (!regex.test(cig)) {
                    errorMsg += "Il CIG inserito non è valido.\n";
                    valid = false;
                    if (firstElem == '') {
                        firstElem = $('#inputCig');
                    }
                }
            }

            if (valid) {
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
            } else {
                $(firstElem).parentsUntil('.form-group').parent().addClass('has-error');
                $(firstElem).focus();
                alert(errorMsg)
            }
        }
    });

    // Preset modale all'apertura per nuova convenzione
    $('#newAgreement').click(function() {
        $('#inputCapacityIncrement0').prop('checked', true);
        $('#div-attachments').hide();
        $('#deleteAgreement').hide();
    });

    $('#deleteAgreement').click(function(){
        var approved = $('#formAgreement #approved').val();
        if (approved == 0) {
            if(confirm('Si è sicuri di voler cancellare la convenzione?')){
                var id = $('#agreementId').val();
                $.ajax({
                    url : pathServer + "aziende/Ws/deleteAgreement",
                    type: "POST",
                    dataType: "json",
                    data: {id: id}
                }).done(function (res) {
                    if(res.response == "OK"){
                        $('#table-agreements').trigger('update');
                        $('#modalAgreement').modal('hide');
                    }else{
                        alert(res.msg);
                    }
                }).fail(function(richiesta, stato, errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                });
            }
        } else {
            alert("Attenzione! La convenzione è in stato approvato pertanto non può essere cancellata");
        }
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
            $('#formAgreement #approved').val(Number(res.data.approved));
            if (role == 'admin') {
                $('#inputApproved').prop('checked', res.data.approved);
            }
            $('#inputProceduraAffidamento').val(res.data.procedure_id);
            $('#inputDateAgreement').datepicker('setDate', res.data.date_agreement);
            $('#inputDateAgreementExpiration').datepicker('setDate', res.data.date_agreement_expiration);
            $('#inputDateExtensionExpiration').datepicker('setDate', res.data.date_extension_expiration);
            $('#inputGuestDailyPrice').val(res.data.guest_daily_price).trigger('change');
            $('#inputCig').val(res.data.cig);
            $('#inputCapacityIncrement'+res.data.capacity_increment).prop('checked', true);
            var countInactiveSedi = 0;
            res.data.agreements_to_sedi.forEach(function(sede) {
                if (sede.active) {
                    $('#inputSedeCheck'+sede.sede_id).prop('checked', true);
                    $('#inputSedeCapacity'+sede.sede_id).val(sede.capacity);
                    $('#inputSedeCapacity'+sede.sede_id).prop('disabled', false);
                    $('#inputSedeCapacity'+sede.sede_id).addClass('required');
                    if (res.data.capacity_increment > 0) {
                        $('#inputSedeCapacityIncrement'+sede.sede_id).val(sede.capacity_increment);
                        $('#inputSedeCapacityIncrement'+sede.sede_id).prop('disabled', false);
                    }
                } else {
                    $('#inputSedeCheck'+sede.sede_id).prop('checked', true);
                    $('#inputSedeCheck'+sede.sede_id).prop('disabled', true);
                    $('#inputSedeCheck'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                    $('#inputSedeCapacity'+sede.sede_id).val(sede.capacity);
                    $('#inputSedeCapacity'+sede.sede_id).prop('disabled', true);
                    $('#inputSedeCapacity'+sede.sede_id).removeClass('required');
                    $('#inputSedeCapacity'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                    $('#inputSedeCapacityIncrement'+sede.sede_id).prop('disabled', false);
                    $('#inputSedeCapacityIncrement'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                    countInactiveSedi++;
                }
            })

            // Se utente di ruolo ente e convenzione approvata, disabilito form e mostro messaggio
            if (role == 'ente' && res.data.approved) {
                $('.approved-message').show();
                disableApprovedModal();
            }

            // Se convenzione non ha sedi attive abilito tasto di cancellazione
            if (res.data.agreements_to_sedi.length == countInactiveSedi) {
                $('#deleteAgreement').show();
                $('#deleteAgreement').prop('disabled', false);
                $('#deleteAgreement').attr('title', '');
            } else {
                $('#deleteAgreement').show();
                $('#deleteAgreement').prop('disabled', true);
                $('#deleteAgreement').attr('title', 'Non è possibile cancellare una convenzione che ha delle strutture collegate');
            }

            //Mostro tasto allegati
            $('#idItemForAttachment').html(res.data.id);
            $('#attachmentReadOnly').html(res.data.approved);
            $('#div-attachments').show();

            //mostro badge attachments e conto numero allegati
	        attachmentsNumberForBadge('agreements', res.data.id, 'button_attachment');

            //Calcolo totali posti
            computeTotalCapacity();
            computeMaxCapacityIncrement();
            computeTotalCapacityIncrement();

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
    $('#inputCig').val("");
    $('#inputCig').prop("disabled", false);
    $('#inputCig').removeClass('disabled-approved');
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

    $('.agreement-sede-capacity-increment').each(function() {
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
    $('#inputCig').prop("disabled", true);
    $('#inputCig').addClass('disabled-approved');
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

    $('.agreement-sede-capacity-increment').each(function() {
        $(this).prop("disabled", true);
        $(this).addClass('disabled-approved');
    });
}

function computeTotalCapacity() {
    var tot = 0;
    $('.agreement-sede-capacity').each(function(index, element) {
        var val = $(element).val().length > 0 ? $(element).val() : 0;
        tot += parseInt(val);
    }); 
    $('#totalCapacity').html(tot);
}

function computeMaxCapacityIncrement() {
    var totalCapacity = $('#totalCapacity').html().length > 0 ? $('#totalCapacity').html() : 0;
    var increment = $('input[name="capacity_increment"]:checked').val().length > 0 ? $('input[name="capacity_increment"]:checked').val() : 0;
    var max = parseInt(totalCapacity) * parseInt(increment) / 100;
    $('#maxCapacityIncrement').html(Math.round(max));
}

function computeTotalCapacityIncrement() {
    var tot = 0;
    $('.agreement-sede-capacity-increment').each(function(index, element) {
        var val = $(element).val().length > 0 ? $(element).val() : 0;
        tot += parseInt(val);
    });
    $('#totalCapacityIncrement').html(tot);
    var maxCapacityIncrement = parseInt($('#maxCapacityIncrement').html());
    if (tot == maxCapacityIncrement) {
        $('#totalCapacityIncrement').removeClass('warning-capacity-increment');
        var increment = $('input[name="capacity_increment"]:checked').val().length > 0 ? $('input[name="capacity_increment"]:checked').val() : 0;
        if (increment > 0) {
            $('#incrementCorrectMessage').show();
        } else {
            $('#incrementCorrectMessage').hide();
        }
        $('#incrementErrorExcessMessage').hide();
        $('#incrementErrorDeficitMessage').hide();
    } else {
        $('#totalCapacityIncrement').addClass('warning-capacity-increment');
        $('#incrementCorrectMessage').hide();
        var diff = maxCapacityIncrement - tot;
        if (diff > 0) {
            $('#incrementErrorDeficitMessage').show();
            $('#incrementErrorDeficitMessage .number').html(Math.abs(diff));
            $('#incrementErrorExcessMessage').hide();
        } else {
            $('#incrementErrorExcessMessage').show();
            $('#incrementErrorExcessMessage .number').html(Math.abs(diff));
            $('#incrementErrorDeficitMessage').hide();
        }
    }
}