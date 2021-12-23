$(document).ready(function(){

	//alert('ready');

	$("#table-offers").tablesorter({
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

          filter_functions:{
              //1:function(e,n,f,i,$r){return e===f;},
              //2:provider,
          },

          filter_formatter : {

            '.filter-status': function($cell, indx) {
              return $.tablesorter.filterFormatter.select2( $cell, indx, {
                // *** select2 filter formatter options ***
                cellText : '',    // Text (wrapped in a label element)
                match    : true,  // adds "filter-match" to header & modifies search
                value    : [],    // initial select2 values

                // *** ANY select2 options can be included below ***
                // (showing default settings for this formatter code)
                multiple : true,  // allow multiple selections
                width    : '100%' // reduce this width if you add cellText
              });
            },

          },
    
          // option added in v2.16.0
          filter_selectSource : {
            // added as select2 options (you could also use select2 data option)
            '.filter-status': statuses
          }
        },
        sortReset: true,
        headers: { }
    }).tablesorterPager({

          // **********************************
          //  Description of ALL pager options
          // **********************************

          // target the pager markup - see the HTML block below
          container: $(".pager"),

          // use this format: "http:/mydatabase.com?page={page}&size={size}&{sortList:col}"
          // where {page} is replaced by the page number (or use {page+1} to get a one-based index),
          // {size} is replaced by the number of records to show,
          // {sortList:col} adds the sortList to the url into a "col" array, and {filterList:fcol} adds
          // the filterList to the url into an "fcol" array.
          // So a sortList = [[2,0],[3,0]] becomes "&col[2]=0&col[3]=0" in the url
          // and a filterList = [[2,Blue],[3,13]] becomes "&fcol[2]=Blue&fcol[3]=13" in the url

          ajaxUrl : pathServer + 'crm/ws/offers/table/'+idAzienda+'?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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

          // process ajax so that the following information is returned:
          // [ total_rows (number), rows (array of arrays), headers (array; optional) ]
          // example:
          // [
          //   100,  // total rows
          //   [
          //     [ "row1cell1", "row1cell2", ... "row1cellN" ],
          //     [ "row2cell1", "row2cell2", ... "row2cellN" ],
          //     ...
          //     [ "rowNcell1", "rowNcell2", ... "rowNcellN" ]
          //   ],
          //   [ "header1", "header2", ... "headerN" ] // optional
          // ]
          // OR
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
          output: '{startRow} to {endRow} ({totalRows})',

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

        if(parseInt(options.totalRows) == 0 && $('span#no-result').length == 0)
        {
            $('#'+$(this).attr("id")+' tbody').append('<tr><td colspan="'+$('#'+$(this).attr("id")).find('thead th').length+'"><span id="no-result">Nessun risultato trovato.</span></td></tr>');
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

});

$(document).on('click', '.new-offer', function(){
    if(idAzienda != 0){
		$('[name="id_azienda_dest"]').prop('disabled', true);
		$('[name="id_azienda_dest"]').append('<option value="'+idAzienda+'">'+nomeAzienda+'</option>');
		$('[name="id_azienda_dest"]').val(idAzienda).trigger('change');
    }

    $('[name="id_azienda_emit"]').trigger('change');

    $('#myModalOffer #reference_for_remarks').html('');
    $('#myModalOffer #reference_id_for_remarks').html('');
    $('#myModalOffer #label_notification_remarks').html('');

    $('#div-remarks').hide();
});


//########################################################################################################################
//Gestione Cancella Offerta
$(document).on('click','.delete-offer',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare l\'offerta ?')){
			deleteOffer($(this).attr('data-id'));
	}

});

//########################################################################################################################
//Gestione Edit Offerta
$(document).on('click','.edit-offer',function(e){

	disableInputModale();

	var idOffer = $(this).attr('data-id');
	//alert('edit ' + idScadenzario);
    loadInputModaleOffer(idOffer);
    loadStoricoStati(idOffer);

	enableInputModale();

	$('#myModalOffer #reference_for_remarks').html('offerte');
    $('#myModalOffer #reference_id_for_remarks').html(idOffer);
    $('#myModalOffer #label_notification_remarks').html('Offerta numero '+idOffer);

    //mostro badge remarks e conto numero note
    remarksNumberForBadge('offerte', idOffer);

    $('#div-remarks').show();

});

//########################################################################################################################
//Elimina stato offerta
$(document).on('click','.delete-status',function(e){
    var id_status = $(this).attr('data-id');
    var id_offer = $('input[name="id"]').val();

	if(confirm('Si è sicuri di voler eliminare lo stato?')){
        $.ajax({
            url : pathServer + "crm/ws/offers/deleteStatus/" + id_status,
            type: "GET",
            dataType: "json",
            success : function (data,stato) {
    
                if(data.response == "OK"){
                    loadStoricoStati(id_offer)
                }else{
                    alert(data.msg);
                }
    
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            }
        });
    
    }

});

//#######################################################################################################################
//Svuoto tabella storico stati alla chiusura della modale
$(document).on('hidden.bs.modal', '#myModalOffer', function () {
    $('#table-storico-stati tbody').html('');
    $('#click_tab_1').trigger('click');
    
    //Nascondo badge remarks
    $('#remarks_number').hide();
})



function deleteOffer(id){

	$.ajax({
	    url : pathServer + "crm/ws/offers/delete/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	if($("#table-offers").length){
					reloadTableOffers();
				}else{
					location.reload();
				}

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function reloadTableOffers()
{
	var pager = $("#table-offers")[0].config.pager;
	setTableSorterTempPager('table-offers', [(pager.page+1),pager.size] );
	$('#table-offers').trigger('pagerUpdate');
    lastSortList=$("#table-offers")[0].config.sortList;
    $("#table-offers").trigger("sorton", false);
    $("#table-offers").trigger("sorton", [lastSortList]);
}

function disableInputModale(){

	$('#myModalOffer input').each(function(){

		$(this).attr('disabled',true);

	});

}

function enableInputModale(){

	$('#myModalOffer input').each(function(){

		$(this).removeAttr('disabled');

	});
}

function loadInputModaleOffer(idOffer){

	$.ajax({
	    url : pathServer + "crm/ws/offers/get/" + idOffer,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
						fireChangeOffers = false;
						if(data.data.emittente != null){
								$('[name="id_azienda_emit"]').val(data.data.emittente.id).trigger("change");
						}
						if(data.data.azienda_dest != null){
                            $('[name="id_azienda_dest"]').select2('data', {id:data.data.azienda_dest.id, text:data.data.azienda_dest.denominazione});
						}
						/* Soluzione 1
						if(data.data.sede_dest != null){
								$('[name="id_sede_dest"]').append('<option value="' + data.data.sede_dest.id+ '">' + data.data.sede_dest.indirizzo +' '+data.data.sede_dest.num_civico+'</option>');
								$('[name="id_sede_dest"]').val(data.data.sede_dest.id).trigger("change");
						}
						if(data.data.contatto_dest != null){
								$('[name="id_contatto_dest"]').append('<option value="' + data.data.contatto_dest.id+ '">' + data.data.contatto_dest.nome +' '+data.data.contatto_dest.cognome+'</option>');
								$('[name="id_contatto_dest"]').val(data.data.contatto_dest.id).trigger("change");
						}
						if(data.data.contatto_emit != null){
								$('[name="id_contatto_emit"]').append('<option value="' + data.data.contatto_emit.id+ '">' + data.data.contatto_emit.nome +' '+data.data.contatto_emit.cognome+'</option>');
								$('[name="id_contatto_emit"]').val(data.data.contatto_emit.id).trigger("change");
						}*/
						loadContattiAzienda(data.data.id_azienda_dest,'[name="id_contatto_dest"]',data.data.id_contatto_dest);
						loadContattiAzienda(data.data.id_azienda_emit,'[name="id_contatto_emit"]',data.data.id_contatto_emit);
						loadSediAzienda(data.data.id_azienda_dest,'[name="id_sede_dest"]',data.data.id_sede_dest);

						$.each(data.data , function(index,data){

									if(moment(data, moment.ISO_8601, true).isValid() ){
											$('[name="'+index+'"]').val(moment(data).format('DD/MM/YYYY'));
											$('[name="'+index+'"]').datepicker('update');
									}else if($('[name="'+index+'"]').attr('type') == 'checkbox'){
											if(data == true || data == 1){
													$('[name="'+index+'"]').attr("checked",true);
											}
									}else{
										$('[name="'+index+'"]').val(data);
									}

						});
						$('#numOffer').show();
						$('.labelIdOffer').html(data.data.id);
						$('.inputNumber').trigger('focusout');
						fireChangeOffers = true;
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadStoricoStati(idOffer){

    $('#table-storico-stati tbody').html('<tr><td colspan="3" style="text-align:center;">Caricamento...</td></tr>');

	$.ajax({
	    url : pathServer + "crm/ws/offers/getStoricoStati/" + idOffer,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
                var html = '';
                var i = 0;
                data.data.forEach(function(stato){
                    html += '<tr>';
                    html += '<td><span class="badge offerStatus-'+stato.nome+'">'+stato.nome+'</span></td>';
                    html += '<td>'+stato.data+'</td>';
                    if(i > 0){
                        html += '<td style="text-align:center;"><a class="btn btn-xs btn-danger delete-status" data-id="'+stato.id+'" ><i data-toggle="tooltip" href="#" class="fa fa-trash" data-original-title="Elimina stato"></i></a></td>';
                    }else{
                        html += '<td></td>';
                    }
                    html += '<tr>';
                    i++;
                });

                if(html == ''){
                    html = '<tr><td colspan="3" style="text-align:center;">Nessuno stato trovato nello storico per questa offerta.</td></tr>';
                }

                $('#table-storico-stati tbody').html(html);
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

