$(document).ready(function(){

    /*###################################################### NOTIFICHE  ########################################################*/

    //Tabella 
    if ($('#table-statements-notifications').length > 0) {
        $('#table-statements-notifications').tablesorter({
            theme: 'bootstrap',
            headerTemplate: '{content} {icon}',
            widthFixed: false,
            widgets: ['zebra', 'cssStickyHeaders' , 'columns', 'filter', 'uitheme', 'formatter'],
            widgetOptions: {

                filter_selectSource  : {
                    6 : ['0|No', '1|Sì']
                  },

                filter_selectSourceSeparator : '|',

            },
            headers: { 
                6: { sorter: false },
                7: { sorter: false, filter: false }
            }
        }).tablesorterPager({
            container: $("#pager-statements-notifications"),

            ajaxUrl: pathServer + 'aziende/ws/getStatementsNotifications/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
        $('#table-statements-notifications').trigger('update');
    });

    //segna tutte le notifiche come gestite
    $('#markAllNotificationsDone').click(function() {
        if (confirm('Si è sicuri di voler marcare tutte le notifiche come "gestite"?')) {
            var url = pathServer + 'aziende/ws/saveAllstatementsNotificationsDone/';
            //Filtri
            var filterList = $.tablesorter.getFilters($('#table-statements-notifications'));
            var filters = [];
            $.each(filterList, function(i, v) {
                if (v) {
                    filters.push('filter[' + i + ']=' + encodeURIComponent(v));
                }
            });
            url += '?'+(filters.length ? filters.join('&') : 'filter');
            //Ordinamento
            var sortList = $('#table-statements-notifications')[0].config.sortList;
            var columns = [];
            $.each(sortList, function(i, v) {
                columns.push('column[' + v[0] + ']=' + v[1]);
            });
            url += '&'+(columns.length ? columns.join('&') : 'column');
            //Numero righe per pagina
            var size = $('#table-statements-notifications')[0].config.pager.size;
            url += '&size='+size;
            //Numero pagina
            var page = $('#table-statements-notifications')[0].config.pager.page;
            url += '&page='+page;
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
            }).done(function(res) {
                if (res.response == 'OK') {
                    $('#table-statements-notifications').trigger('update');
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
                url: pathServer + 'aziende/ws/saveStatementsNotificationsDone',
                type: "POST",
                dataType: 'json',
                data: data
            }).done(function(res) {
                if (res.response == 'OK') {
                    $('#table-statements-notifications').trigger('update');
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