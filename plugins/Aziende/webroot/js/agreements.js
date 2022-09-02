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

    /*###################################################### MODALE ########################################################*/

    //Attivazione/disattivazione sede CHECK su Operativa
    $('.agreement-sede-active').change(function() {
        var id = $(this).attr('data-id');
        if ($(this).is(':checked')) {
            $('#inputSedeCapacity'+id).prop('readonly', false);
            if ($('input[name="capacity_increment"]:checked').val() > 0) {
                $('#inputSedeCapacityIncrement'+id).prop('readonly', false);
            }

            // Rendiconti
            $('#inputSedeCompany' + id).prop('disabled', false);
            $('#inputSedeCompany' + id + ' option[data-default=true]').prop('selected', true);
            if($('input[name=rendiconto]').prop('checked')) {
                $('#inputSedeCompany' + id).removeAttr('readonly');
            }

        } else {
            $('#inputSedeCapacity'+id).prop('readonly', true);
            $('#inputSedeCapacity'+id).prop('title', 'Convenzione non più attiva per questo centro');
            if ($('input[name="capacity_increment"]:checked').val() > 0) {
                $('#inputSedeCapacityIncrement'+id).prop('readonly', true);
                $('#inputSedeCapacityIncrement'+id).prop('title', 'Convenzione non più attiva per questo centro');
            }

            // Rendiconti
            $('#inputSedeCompany' + id).prop('disabled', true);
            $('#inputSedeCompany' + id).val('');
        }
    });

    //Associazione/disassociazione sede CHECK su Associata
    $('.agreement-sede-checked').change(function() {
        var id = $(this).attr('data-id');
        if ($(this).is(':checked')) {
            $('#inputSedeActive'+id).prop('disabled', false);
            $('#inputSedeActive'+id).prop('checked', true);
            $('#inputSedeCapacity'+id).prop('disabled', false);
            $('#inputSedeCapacity'+id).addClass('required');
            if ($('input[name="capacity_increment"]:checked').val() > 0) {
                $('#inputSedeCapacityIncrement'+id).prop('disabled', false);
            }
            //Attivo anche la scelta selezione dell'azienda per il rendiconto
            $('#inputSedeCompany' + id).prop('disabled', false);
            $('#inputSedeCompany' + id + ' option[data-default=true]').prop('selected', true);

            if($('input[name=rendiconto]').prop('checked')) {
                $('#inputSedeCompany' + id).removeAttr('readonly');
            }
            
        } else {
            $('#inputSedeActive'+id).prop('disabled', true);
            $('#inputSedeActive'+id).prop('checked', false);
            $('#inputSedeCapacity'+id).prop('disabled', true);
            $('#inputSedeCapacity'+id).removeClass('required');
            $('#inputSedeCapacity'+id).val('');
            if ($('input[name="capacity_increment"]:checked').val() > 0) {
                $('#inputSedeCapacityIncrement'+id).prop('disabled', true);
                $('#inputSedeCapacityIncrement'+id).val('');
            }
            //Disattivo anche la scelta selezione dell'azienda per il rendiconto 
            $('#inputSedeCompany' + id).prop('disabled', true);
            $('#inputSedeCompany' + id).val('');
        }

        //Aggiornamento totali
        computeTotalCapacity();
        computeMaxCapacityIncrement();
        computeTotalCapacityIncrement();
    });

    //Cambio percentuale incremento posti
    $('input[name="capacity_increment"]').change(function() {
        if ($(this).val() > 0) {
            $('.agreement-sede-checked:checked').each(function(index, element) {
                var id = $(element).attr('data-id');
                $('#inputSedeCapacityIncrement'+id).prop('disabled', false);
            });
        } else {
            $('.agreement-sede-checked:checked').each(function(index, element) {
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
        let forms = [
            { el: '#click_tab_1', form: 'formAgreement'},
            { el: '#click_tab_2', form: 'formRendiconto'}
        ];

        if(multipleFormValidation(forms)){
            $('#click_tab_1').trigger("click");
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

                // Aggiungo anche le aziende create nel tab per i rendiconti
                var rendiconti = $('input[name^=companies]');
                $(rendiconti).each(function () {
                    formData.append($(this).attr('name'), $(this).val());
                });
                
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
        disableRendiconti();
        $('#inputCapacityIncrement0').prop('checked', true);
        $('#div-attachments').hide();
        $('#deleteAgreement').hide();
        $('input[name=rendiconto]').prop('checked', false);

        let denominazione = $(this).data('denominazione');
        $("div[data-default=true] input[name='companies[0][name]']").val(denominazione);

        // Popolo le tendine accanto alle sedi
        $('select[id^=inputSedeCompany]').each(function () {
            $(this).append(
                [
                    $('<option>'),
                    $('<option>',
                        {
                            value: '',
                            text: denominazione,
                            'data-default': true,
                            selected: false
                        }
                    )
                ]
            );
        }); 
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

    // Checkbox ABILITA RENDICONTAZIONE ATI
    $('input[name=rendiconto]').change(function() {
        if($(this).prop('checked')) {
            enableRendiconti();
        } else {
            disableRendiconti();
        }
    });

    //Disabilita click in caso di readOnly
    $('select[id^=inputSedeCompany]').click( function () {
        if ($(this).attr('readonly')) {
            $(this).blur();
            window.focus;
        }
    });

});

$(document).on('click', '.edit-agreement', function(){
    $('#click_tab_2').parent().removeClass('hide');

    var id = $(this).attr('data-id');
    $.ajax( 
        {
            url : pathServer + "aziende/Ws/checkRendiconti/"+id,
            type: "GET",
            dataType: "json"
        }
    ).done(function (res) {
            if(res.response == "OK") {

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
            
                        // Popolo le tendine accanto alle sedi

                        $('select[id^=inputSedeCompany]').each(function () {
                            
                            $(this).append(
                                $('<option>',
                                    {
                                        disabled: true,
                                        selected: true
                                    }
                                )
                            );

                            for(let company of res.data.companies) {

                                $(this).append(
                                    $('<option>',
                                        {
                                            value: company.id,
                                            text: company.name,
                                            'data-default': company.isDefault
                                        }
                                    )
                                );
                            }
                        }); 
            
                        var countInactiveSedi = 0;
                        res.data.agreements_to_sedi.forEach(function(sede) {
                            $('#inputSedeActive'+sede.sede_id).prop('disabled', false);
                            $('#inputSedeCapacity'+sede.sede_id).prop('disabled', false);
                            $('#inputSedeCompany' + sede.sede_id).prop('disabled', false);
                            if (res.data.capacity_increment > 0) {
                                $('#inputSedeCapacityIncrement'+sede.sede_id).prop('disabled', false);
                            }
                            if (sede.active) {
                                $('#inputSedeActive'+sede.sede_id).prop('checked', true);
                                $('#inputSedeCheck'+sede.sede_id).prop('checked', true);
                                $('#inputSedeCheck'+sede.sede_id).prop('readonly', false);
                                $('#inputSedeCapacity'+sede.sede_id).val(sede.capacity);
                                $('#inputSedeCapacity'+sede.sede_id).addClass('required');
                                if (res.data.capacity_increment > 0) {
                                    $('#inputSedeCapacityIncrement'+sede.sede_id).val(sede.capacity_increment);
                                    $('#inputSedeCapacityIncrement'+sede.sede_id).prop('readonly', false);
                                }

                                // Imposto la selezione nelle opzioni delle tendine accanto alle sedi

                                let count = $('#inputSedeCompany' + sede.sede_id + ' option');

                                if(count.length > 1) {
                                    $('#inputSedeCompany' + sede.sede_id + ' option').each(function () {

                                        if(sede.agreement_company_id) {
                                            if($(this).val() == sede.agreement_company_id) {
                                                $(this).prop('selected', true);
                                            }

                                        } else {
                                            if($(this).data('default') == true) {
                                                $(this).prop('selected', true);
                                            }

                                        }

                                    }); 
                                } else {
                                    $('#inputSedeCompany' + sede.sede_id + ' option').each(function () {
                                        $(this).prop('selected', true);
                                    });
                                    $('#inputSedeCompany' + sede.sede_id).prop('readonly', true);
                                }

                            } else {
                                $('#inputSedeActive'+sede.sede_id).prop('checked', false);
                                $('#inputSedeCheck'+sede.sede_id).prop('checked', true);
                                $('#inputSedeCheck'+sede.sede_id).prop('readonly', true);
                                $('#inputSedeCapacity'+sede.sede_id).val(sede.capacity);
                                $('#inputSedeCapacity'+sede.sede_id).prop('readonly', true);
                                $('#inputSedeCapacity'+sede.sede_id).removeClass('required');
                                $('#inputSedeCapacity'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                                if (res.data.capacity_increment > 0) {
                                    $('#inputSedeCapacityIncrement'+sede.sede_id).val(sede.capacity_increment);
                                    $('#inputSedeCapacityIncrement'+sede.sede_id).prop('readonly', true);
                                    $('#inputSedeCapacityIncrement'+sede.sede_id).prop('title', 'Convenzione non più attiva per questo centro');
                                }
                                countInactiveSedi++;
                            }
                        });
            
                        // Se utente di ruolo ente e convenzione approvata, disabilito form e mostro messaggio
                        if (role == 'ente_ospiti' && res.data.approved) {
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
            
                        //Tab dei Rendiconti
                        res.data.companies.forEach((rendiconto) => {
                            if (rendiconto.isDefault) {
                                $("div[data-default=true] input[name='companies[0][id]']").val(rendiconto.id);
                                $("div[data-default=true] input[name='companies[0][name]']").val(rendiconto.name);
                            } else {
                                addRendiconto(rendiconto.name, rendiconto.id);
                            }
                            
                        });
                        if(role == 'admin' || role == 'area_iv' || role == 'ente_ospiti') {
                            createButton();
                        }
            
                        if(res.data.companies.length > 1) {
                            $('input[name=rendiconto]').prop('checked', true);
                            enableRendiconti();
                        } else {
                            $('input[name=rendiconto]').prop('checked', false);
                            disableRendiconti();
                        }
            
                        $('#modalAgreement').modal({
                            backdrop: false,
                            keyboard: false
                        });
                        $('#modalAgreement').modal('show');
                    }else{
                        alert(res.msg);
                    }
                }).fail(function(richiesta, stato, errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                });

            } else {
                alert(res.msg);
            }
        } 
    ).fail(function (richiesta, stato, errori) {
            alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        }
    );


});

$(document).on('hidden.bs.modal', '#modalAgreement', function() {
    clearModal();
});

function addRendiconto(denominazione, id) {
    let count = $('input[type=text][name^=companies]').length;
    let agrId = $('#agreementId').val();

    $('#rendiconti').append(  
            $('<div>',
                {
                    class: 'input-group margin-bottom input',
                    'data-id': id ? id : '',
                    'data-default': false
                }
            ).append(
                [
                    $('<input>', 
                        {
                            type: 'hidden',
                            value: id ? id : '',
                            name: 'companies['+count+'][id]'
                        }
                    ),
                    $('<input>',
                        {
                            class: 'form-control required',
                            placeholder: 'Azienda',
                            type: 'text',
                            name: 'companies['+count+'][name]',
                            value: denominazione ? denominazione : '',
                            onblur: agrId ? 'saveRendiconto(this)' : '',
                            required: true
                        }
                    ),
                    $('<a>',
                    {
                        class: 'btn btn-danger input-group-addon manage-rendiconto',
                        onclick: 'deleteRendiconto(this)'
                    }
                    ).append(
                        $('<i>',
                        { class: 'fa fa-remove'}
                        )
                    )
                ]
            )
    );

}

function enableRendiconti() {
    // Abilito i menù a tendina nella tab CONVENZIONE
    $('select[id^=inputSedeCompany]').each(function () {
        if( $(this).parents('tr').find('input[id^=inputSedeCheck]').prop('checked') && $(this).parents('tr').find('input[id^=inputSedeActive]').prop('checked') ) {
            $(this).attr('readonly', false);
            $(this).attr('disabled', false);
        } else {
            $(this).attr('readonly', true);
            $(this).attr('disabled', true);
        }
    }); 

    // Abilito i pulsanti di aggiunta e rimozione nel tab RENDICONTI
    $('.manage-rendiconto').each(function () {
        $(this).attr('disabled', false);
    });

    //Disattivo i campi di input delle aziende della rendicontazione
    $('div[data-default=false]').each( function() {
        $(this).find("input[type=text][name^=companies]").prop('disabled', false);
    });
}

function disableRendiconti() {
    // Disattivo i menù a tendina nella tab CONVENZIONE
    $('select[id^=inputSedeCompany]').each(function () {
        $(this).attr('readonly', true);
        
        if ($(this).parents('tr').find('input[id^=inputSedeCheck]').prop('checked') && $(this).parents('tr').find('input[id^=inputSedeActive]').prop('checked')) {
            $(this).val($(this).find('option[data-default=true]').val());
        }
        
    }); 

    // Disattivo i pulsanti di aggiunta e rimozione nel tab RENDICONTI
    $('.manage-rendiconto').each(function () {
        $(this).attr('disabled', true);
    });

    //Disattivo i campi di input delle aziende della rendicontazione
    $('div[data-default=false]').each( function() {
        $(this).find("input[type=text][name^=companies]").prop('disabled', true);
    });


}

function createInputForRendiconto(element) {
    addRendiconto(false, false);
    if(element) {
        $(element).remove();
    }
    if(role == 'admin' || role == 'area_iv' || role == 'ente_ospiti') {
        createButton();
    }
    
}

function emptyRendiconti() {
    $('div[data-default=false]').each( function() {
        $(this).remove();
    });

    $('input[name=rendiconto]').prop('checked', false);

    $('select[id^=inputSedeCompany]').each(function () {
        $(this).prop('disabled', true);
        $(this).html('')
    }); 

    $("div[data-default=true] input[name='companies[0][id]']").val('');
    $("div[data-default=true] input[name='companies[0][name]']").val('');

}

function deleteRendiconto(ele) {
    let id = $(ele).parent().data('id');
    
    $('select[id^=inputSedeCompany]').each( function (select) {

        $(select).children().each( function () {

            if($(option).val() == id) {
                if($(option).is('selected')) {
                    $(select).val($(select).find('option[data-default=true]').val());
                }
                $(option).remove();
            }
        } );

    } );
    $('select[id^=inputSedeCompany] option').each( function() {
        if($(this).val() == id) {
            $(this).remove();
        }
    } );

    $(ele).parent().remove();

    var rendiconti = $('input[name^=companies]');

    if((role == 'admin' || role == 'area_iv' || role == 'ente_ospiti') && rendiconti.length > 0) {
        createButton();
    }
}

function saveRendiconto(ele) {
    let url = pathServer;

    if($(ele).prev().val().length){
        
        url+= 'aziende/Ws/saveSingleCompany/' + $(ele).prev().val();
    } else {
        url+= 'aziende/Ws/saveSingleCompany';
    }

    if ( $(ele).val().length  > 0 ) {
        
        $.ajax({
            url : url,
            type: "POST",
            dataType: "json",
            data: {
                id: $(ele).prev().val(),
                name: $(ele).val(),
                agreement_id:  $('#agreementId').val()
            }
        }).done(function (res) {
            if(res.response == "OK"){
                $('select[id^=inputSedeCompany]').each( function() {
                    $(this).append(
                        $('<option>',
                            {
                                value: res.data.id,
                                text: res.data.name,
                                'data-default': false
                            }
                        )
                    );
                } );





            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta, stato, errori) {
            alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });

    }
}

// Creo un pulsante per aggiungere i rendiconti da inserire accanto all'ultimo pulsante della lista
function createButton() {

    let list = $('.btn.btn-success.input-group-addon.manage-rendiconto');

    if($("input[type=text][name^='companies']").length > 1) {
        if(list.length < 1) {
            $('.btn.btn-danger.input-group-addon.manage-rendiconto').last().after(
                $('<a>',
                    {
                        class: 'btn btn-success input-group-addon manage-rendiconto',
                        onclick: 'createInputForRendiconto(this)'
                    }
                ).append(
                    $('<i>',
                    { class: 'fa fa-plus'})
                )
            );
        }
    } else {
        $("input[name='companies[0][name]']").after(
            $('<a>',
                {
                    class: 'btn btn-success input-group-addon manage-rendiconto',
                    onclick: 'createInputForRendiconto(this)'
                }
            ).append(
                $('<i>',
                { class: 'fa fa-plus'})
            )
        );
    }

}

function clearModal(){
    $('#click_tab_1').trigger("click");
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
    $('#inputCapacityIncrement0').prop("checked", false);
    $('#inputCapacityIncrement0').prop("disabled", false);
    $('#inputCapacityIncrement0').removeClass('disabled-approved');
    $('#inputCapacityIncrement20').prop("checked", false);
    $('#inputCapacityIncrement20').prop("disabled", false);
    $('#inputCapacityIncrement20').removeClass('disabled-approved');
    $('#inputCapacityIncrement50').prop("checked", false);
    $('#inputCapacityIncrement50').prop("disabled", false);
    $('#inputCapacityIncrement50').removeClass('disabled-approved');

    $('.agreement-sede-active').each(function() {
        $(this).prop('checked', false);
        $(this).prop("disabled", true);
    });

    $('.agreement-sede-checked').each(function() {
        $(this).prop('checked', false);
        $(this).prop("disabled", false);
    });

    $('.agreement-sede-capacity').each(function() {
        $(this).val("");
        $(this).removeClass("required");
        $(this).prop("readonly", false);
        $(this).prop("disabled", true);
        $(this).prop("title", '');
        $(this).removeClass('disabled-approved');
    });

    $('.agreement-sede-capacity-increment').each(function() {
        $(this).val("");
        $(this).removeClass("required");
        $(this).prop("readonly", false);
        $(this).prop("disabled", true);
        $(this).prop("title", '');
        $(this).removeClass('disabled-approved');
    });

    $('.has-error').removeClass('has-error');

    emptyRendiconti();
    $('.manage-rendiconto').remove();
    $('#click_tab_2').parent().addClass('hide');

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
    $('#inputCapacityIncrement0').prop("disabled", true);
    $('#inputCapacityIncrement0').addClass('disabled-approved');
    $('#inputCapacityIncrement20').prop("disabled", true);
    $('#inputCapacityIncrement20').addClass('disabled-approved');
    $('#inputCapacityIncrement50').prop("disabled", true);
    $('#inputCapacityIncrement50').addClass('disabled-approved');

    $('.agreement-sede-checked').each(function() {
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