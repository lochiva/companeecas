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

});