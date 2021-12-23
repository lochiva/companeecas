$(document).ready(function(){

	//alert('ready');

	$('.modal').on('hidden.bs.modal', function (e) {
	    if($('.modal').hasClass('in')) {
	    $('body').addClass('modal-open');
	    }
	});


	$("#table-orders").tablesorter({
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
          }
        },
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
          ajaxUrl : pathServer + 'progest/ws/orders/table/' + idAzienda + '?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
								if(d[r][c] == null || d[r][c] == undefined){
									d[r][c] = '';
								}
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

});

//########################################################################################################################
//Gestione Cancella Buoni d'ordine
$(document).on('click','.delete',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare l\'ordine?')){
		deleteOrder($(this).attr('data-id'));
	}

});

//########################################################################################################################
//Gestione Edit Buoni D'ordini
$(document).on('click','.edit',function(e){

	disableInputModale();

	var idSede = $(this).attr('data-id');
	//alert('edit ' + idAzienda);
	loadInputModale(idSede);

	enableInputModale();

});

//########################################################################################################################
//Gestione Proroga Buoni D'ordini
$(document).on('click','.duplicate',function(e){

	disableInputModale();

	var idOrder = $(this).attr('data-id');
	clearModaleOrder();
	//alert('edit ' + idAzienda);
	loadInputModale(idOrder);

	//$('#myFormOrder [name="protocol_number"]').val('');
	//$('#myFormOrder [name="protocol_date"]').val('').datepicker('update');
	$('#myFormOrder [name="start_date"]').val('').datepicker('update');
	$('#myFormOrder [name="end_date"]').val('').datepicker('update');
	$('#myFormOrder [name="name"]').val('Proroga/Variazione: '+$('#myFormOrder [name="name"]').val());
	$('#myFormOrder [name="id_status"]').val(1);
	$('#myFormOrder [name="old_id"]').val($('#myFormOrder [name="id"]').val());
	$('.reset-id').val(0);
	$('.modal-duplicate').attr('data-id','');
  $('.modal-duplicate').hide();


	enableInputModale();

});

//########################################################################################################################
//Gestione Edit della Persona dalla modale dei Buoni d'ordine
$(document).on('click','.edit-person',function(e){

	disableInputModale();
	var idPerson = $('#idPerson').val();
	if(idPerson != undefined && idPerson != null ){
			loadInputModalePerson(idPerson);
	}else{
			alert('Devi selezionare prima una persona');
			$('.close-modal-person').click();
	}
	enableInputModale();

});

//########################################################################################################################
//Aggiunta adi
$(document).on('click','.inserimento-adi',function(e){

	disableInputModale();
	var today = moment();
	$('#myFormOrder [name="protocol_number"]').val(today.format('YYYYMMDDhmmss'));
	$('#myFormOrder [name="protocol_date"]').val(today.format('DD/MM/YYYY')).datepicker('update');
	$('#myFormOrder [name="name"]').val('ADI');
	$('#myFormOrder [name="note"]').val('ADI');
	$('#myFormOrder [name="id_person_type"], [name="id_invoice_type"]').val(10).trigger('change');
	$('#myFormOrder [name="self_sufficient"]').val(0);
	$('#myFormOrder [name="self_percent"]').val(100);
	$('#myFormOrder [name="paid_by_asl_percent"]').val(100);
	enableInputModale();

});

function deleteOrder(id){

	$.ajax({
	    url : pathServer + "aziende/Ws/deleteOrder/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	reloadTableOrder();

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function disableInputModale(){

	$('#myModalOrder input').each(function(){

		$(this).attr('disabled',true);

	});

}

function enableInputModale(){

	$('#myModalOrder input').each(function(){

		$(this).removeAttr('disabled');

	});
}

function loadInputModale(idOrder){

	$.ajax({
	    url : pathServer + "progest/ws/orders/get/" + idOrder,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
						fireChangeOrders = false;
						if(idAzienda == 'all'){
							if(data.data.azienda != null){
								$("#idAzienda").append('<option value="'+data.data.id_azienda+'">'+data.data.azienda.denominazione+'</option>');
							}
							//loadContattiAzienda(data.data.id_azienda,data.data.id_contatto);
							$('#idAzienda').val(data.data.id_azienda).trigger("change");
						}else{
							$('#myFormOrder [name="id_contatto"]').val(data.data.id_contatto);
							$('#myFormOrder [name="id_azienda"]').val(data.data.id_azienda);
						}
						if(data.data.id_person > 0){
							loadPersona(data.data.id_person, data.data.id_richiedente);
						}
						// Funzione di riempimnento del form. In general.js
						fillFormGeneral(data.data, 'myFormOrder');

						window.fillingModal = true;
						fillLuoghiAutocomplete({provincia:data.data.fatturazione_provincia,comune:data.data.fatturazione_comune,
								cap:data.data.fatturazione_cap},{names:{provincia:'fatturazione_provincia',comune:'fatturazione_comune',cap:'fatturazione_cap'}});

						$('select').trigger('change');
						$('.inputNumber').trigger('focusout');
						if(data.data.ServicesOrders.length > 0){
								$.each(data.data.ServicesOrders, function(index,value){
										addService(value);
								});
						}
						if(data.data.ContactsOrders.length > 0){
								$.each(data.data.ContactsOrders, function(index,value){
										addContact(value);
								});
						}

						$('.modal-duplicate').attr('data-id',data.data.id);
	          $('.modal-duplicate').show();

						fireChangeOrders = true;
						window.fillingModal = false;

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadPersona(id ,idRichiedente)
{
	$.ajax({
	    url : pathServer + "progest/ws/people/get/"+id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
							window.fillingModal = true;
							var dataNascita = '--';
							if(moment(data.data.birthdate, moment.ISO_8601, true).isValid() ){
									dataNascita = moment(data.data.birthdate).format('DD/MM/YYYY');
							}
							$('#idPerson').append('<option value="'+id+'">'+data.data.surname+' '+data.data.name+' nato/a il '+dataNascita+'</option>');
							$('#idPerson').val(id).trigger("change");

							appendFamiliareSelect(data.data.familiari ,idRichiedente);
							window.fillingModal = false;

					}else{
							alert('E\' evvenuto un errore, persona non trovata!');
					}
			},
			error: function(richiesta,stato,errori){
					alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
			},
		});
}

function reloadTableOrder(){

	var pager = $("#table-orders")[0].config.pager;
	setTableSorterTempPager('table-orders', [(pager.page+1),pager.size] );
	$("#table-orders").trigger("update");
    lastSortList=$("#table-orders")[0].config.sortList;
    $("#table-orders").trigger("sorton", false);
    $("#table-orders").trigger("sorton", [lastSortList]);

}

function appendFamiliareSelect(familiari,idRichiedente){
	$('#idRichiedente').html('<option style="color:#999;" value="" selected>Seleziona un richiedente</option>');
	$.each(familiari, function(index,value){
			var parentela = $("#gradoParentela > option[value='"+value.id_grado_parentela+"']").text();
			$('#idRichiedente').append('<option value="'+htmlEntities(value.id)+'">'+htmlEntities(value.surname)+' '+htmlEntities(value.name)+
			' - '+parentela+'</option>');
	});
	if(idRichiedente !== undefined && idRichiedente != 0){
			$('#idRichiedente').val(idRichiedente).trigger('change');
	}
}

function loadFamiliari(idPerson,idRichiedente){
	$.ajax({
	    url : pathServer + "progest/ws/people/get/"+idPerson,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {
	        if(data.response == "OK"){

							appendFamiliareSelect(data.data.familiari,idRichiedente);

					}else{
							alert('E\' evvenuto un errore, persona non trovata!');
					}
			},
			error: function(richiesta,stato,errori){
					alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
			},
		});
}
