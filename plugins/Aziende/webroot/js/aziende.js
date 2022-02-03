$(document).ready(function(){

	//alert('ready');

	$("#table-aziende").tablesorter({
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
        headers: { 4: { filter: false, sorter:false} }
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
          ajaxUrl : pathServer + 'aziende/Ws/getAziende/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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

            }else{

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



		//inserisco valore del campo nell'input delle altre schede e nella colonna del valore locale
		$(document).on('click', '.btn-save-local', function(){
			var field = $(this).parent().parent().attr('data-field');
			var type = $(this).attr('data-type');

			var value = $('tr[data-field="'+field+'"] td[data-type="'+type+'"]').html();

			var sedeTipoId = $('#sedeTipoId').html();

			//inserisco valore nell'input corrispondente

			//conversione field -> inputId
			var inputId = {
				'nome': 'inputDenominazione',
				'referente': ['inputCognome', 'inputNome'],
				'piva': 'inputPiva',
				'cf': 'inputCF',
				'paese': 'inputNazione',
				'indirizzo': ['inputIndirizzo', 'inputNumCivico'],
				'citta': 'inputComune',
				'cap': 'inputCap',
				'provincia': 'inputProvincia',
				'telefono': 'inputTelefono',
				'fax': 'inputFax',
				'email': 'inputEmailInfo'
			};

			switch(field){
				case 'paese':
				case 'indirizzo':
				case 'cap':
					var containerId = $('#inputTipo').filter(function(){
											return this.value == sedeTipoId
										}).parent().parent().parent().parent().parent().attr('id');
					if(field == 'indirizzo'){
						var address = value.split(',');
						$('#myModalAzienda #'+containerId+' #'+inputId[field][0]).val(address[0]);
						$('#myModalAzienda #'+containerId+' #'+inputId[field][1]).val(address[1]);
					}else{
						$('#myModalAzienda #'+containerId+' #'+inputId[field]).val(value);
					}

					//inserisco valore nella colonna del valore locale
					$('tr[data-field="'+field+'"] td[data-type="locale"]').html(value);

					//disabilito button "salva in locale"
					$('tr[data-field="'+field+'"] .btn-save-local').each(function(){
						$(this).prop('disabled', true);
					});
					
					break;

				case 'citta':
				case 'provincia':
					var containerId = $('#inputTipo').filter(function(){
											return this.value == sedeTipoId
										}).parent().parent().parent().parent().parent().attr('id');
					if(value == ''){
						$('#myModalAzienda #'+containerId+' #'+inputId[field]).val(value).trigger('change');

						//inserisco valore nella colonna del valore locale
						$('tr[data-field="'+field+'"] td[data-type="locale"]').html(value);

						//disabilito button "salva in locale"
						$('tr[data-field="'+field+'"] .btn-save-local').each(function(){
							$(this).prop('disabled', true);
						});
					
					}else{
						var url = '';
						if(field == 'citta'){
							url = pathServer + "aziende/Ws/convertComune/" + value;
						}else if(field == 'provincia'){
							url = pathServer + "aziende/Ws/convertProvincia/" + value;
						}
						$.ajax({
							url : url,
							type: "GET",
							dataType: "json",
							success : function (res,stato) {
				
								if(res.response == "OK"){
									$('#myModalAzienda #'+containerId+' #'+inputId[field]).val(res.data).trigger('change');

									//inserisco valore nella colonna del valore locale
									$('tr[data-field="'+field+'"] td[data-type="locale"]').html(value);

									//disabilito button "salva in locale"
									$('tr[data-field="'+field+'"] .btn-save-local').each(function(){
										$(this).prop('disabled', true);
									});
					
								}else{
									alert(res.msg);
								}
				
							},
							error : function (richiesta,stato,errori) {
								alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
							}
						});
					}
					break;

				case 'referente':
					var fullName = value.split(" ");
					var name = fullName.pop();
					var surname = fullName.join(" ");
					$('#myModalAzienda #'+inputId[field][0]).val(surname);
					$('#myModalAzienda #'+inputId[field][1]).val(name);

					//inserisco valore nella colonna del valore locale
					$('tr[data-field="'+field+'"] td[data-type="locale"]').html(value);

					//disabilito button "salva in locale"
					$('tr[data-field="'+field+'"] .btn-save-local').each(function(){
						$(this).prop('disabled', true);
					});
					
					break;

				default:
					$('#myModalAzienda #'+inputId[field]).val(value);
					
					//inserisco valore nella colonna del valore locale
					$('tr[data-field="'+field+'"] td[data-type="locale"]').html(value);

					//disabilito button "salva in locale"
					$('tr[data-field="'+field+'"] .btn-save-local').each(function(){
						$(this).prop('disabled', true);
					});

					break;
			}
		});

		//chiamata per verificare i dati piva
		$('.verify-piva').click(function(e){
			e.preventDefault();

			var piva = $('#inputPiva').val();
			if(piva != ''){
				$.ajax({
				    url : pathServer + 'aziende/ws/verifyDatiPiva/' + piva,
				    type: "GET",
				    dataType: "json",
				    success : function (res) {
						if(res.res = 'OK'){
							if(res.data.valid){
								$("span#address_vat").html(res.data.address);
					            $("span#country_code_vat").html(res.data.countryCode);
					            $("span#name_vat").html(res.data.name);
					            $("span#number_vat").html(res.data.vatNumber);

								$('#overlayDatiPiva').show();
							}else{
								alert('Partita IVA non valida per transazioni transfrontaliere nell\'UE. \nPer maggiori info consultare:\n http://ec.europa.eu/taxation_customs/vies/vatRequest.html ')
							}
						}else{
							alert(res.msg);
						}
				    },
				    error : function (richiesta,stato,errori) {
				        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
				    }
				});
			}else{
				alert('Inserire una partita IVA per poter verificare i dati.');
			}
		});

		//chiusura overlay dati piva
		$('.close-overlay').click(function(){
			$('#overlayDatiPiva').hide();
		});

		//overlay draggabile
		$('#overlayDatiPiva').draggable({handle: '.draggable'});

		//sottotab sedi e contatti modale azienda riordinabili
		$( "#myModalAzienda #tab_2 .tabs-sedi" ).sortable({
			axis: "x",
			containment: $('#myModalAzienda #tab_2 .tabs-sedi'),
			tolerance: 'pointer',
			helper: 'clone',
			update: function (event, ui) {
				var data = $(this).sortable('serialize'); 
				$.ajax({
					data: data,
					type: 'POST',
					url: pathServer + 'aziende/ws/saveOrderSedi/',
				});
    		}
		});
		
		$( "#myModalAzienda #tab_3 .tabs-contatti" ).sortable({
			axis: "x",
			containment: $('#myModalAzienda #tab_3 .tabs-contatti'),
			tolerance: 'pointer',
			helper: 'clone',
			update: function (event, ui) {
				var data = $(this).sortable('serialize');
				$.ajax({
					data: data,
					type: 'POST',
					url: pathServer + 'aziende/ws/saveOrderContatti/',
				});
			}
		});
});

//########################################################################################################################
//Gestione Cancella Azienda
$(document).on('click','.delete',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare l\'azienda ' + $(this).attr('data-denominazione') + '?')){
		deleteAzienda($(this).attr('data-id'));
	}

});

//########################################################################################################################
//Gestione Edit Azienda
$(document).on('click','.edit',function(e){

	disableInputModale();

	var idAzienda = $(this).attr('data-id');
	var parentTab = $(this).attr('data-parentTab');
	var childTab = $(this).attr('data-childTab');
	//alert('edit ' + idAzienda);
	if(parentTab != undefined ){
		loadInputModale(idAzienda,{parentTab:parentTab, childTab:childTab});
	}else{
		loadInputModale(idAzienda);
	}

	$('#myModalAzienda #reference_for_remarks').html('aziende');
	$('#myModalAzienda #reference_id_for_remarks').html(idAzienda);
	$('#myModalOffer #label_notification_remarks').html('Azienda numero '+idAzienda);
    
	//mostro badge remarks e conto numero note
	remarksNumberForBadge('aziende', idAzienda);
		
	//mostro badge attachments e conto numero allegati
	attachmentsNumberForBadge('aziende', idAzienda, 'button_attachment');

	$('#div-remarks').show();
	$('#div-attachments').show();

	enableInputModale();

});

//#########################################################################################################################
//Apertura modale nuova azienda
$(document).on('click', '#box-general-action', function(){
	$('#myModalAzienda #reference_for_remarks').html('');
	$('#myModalAzienda #reference_id_for_remarks').html('');
	$('#myModalAzienda #label_notification_remarks').html('');
	$('#div-remarks').hide();
	$('#div-attachments').hide();
});

//#######################################################################################################################
//Alla chiusura della modale azienda
$(document).on('hidden.bs.modal', '#myModalAzienda', function () {    
    //Nascondo badge remarks
    $('#remarks_number').hide();
})

//#######################################################################################################################
//Select provincia e comune
$(document).on('change', '.select-provincia', function(){
	var provinciaId = $(this).val();
	var select_comune = $(this).parentsUntil('.form-sede').find('.select-comune');
	var comune_value = $(this).parentsUntil('.form-sede').find('.comune-value').val();

	$.ajax({
		url : pathServer+'ws/autocompleteComune/'+provinciaId,
		type: "GET",
		dataType: "json",
		success : function(response) { 
			select_comune.empty();
			select_comune.select2({
				language: 'it',
				width: '100%',
				placeholder: 'Seleziona un comune',
				closeOnSelect: true,
				dropdownParent: select_comune.parent(),
				data: response.data
			});
			select_comune.val(comune_value).trigger('change');
		},
		error : function (response){

		}
	});
});

$(document).on('change', '.select-provincia-contatto', function(){
	var provinciaId = $(this).val();
	var select_comune_cont = $(this).parentsUntil('.form-contatto').find('.select-comune-contatto');
	var comune_value_cont = $(this).parentsUntil('.form-contatto').find('.comune-value-contatto').val();

	$.ajax({
		url : pathServer+'ws/autocompleteComune/'+provinciaId,
		type: "GET",
		dataType: "json",
		success : function(response) { 
			select_comune_cont.empty();
			select_comune_cont.select2({
				language: 'it',
				width: '100%',
				placeholder: 'Seleziona un comune',
				closeOnSelect: true,
				dropdownParent: select_comune_cont.parent(),
				data: response.data
			});
			select_comune_cont.val(comune_value_cont).trigger('change');
		},
		error : function (response){

		}
	});
});

$(document).on('change', '.select-comune', function(){
	var comune_value = $(this).parentsUntil('.form-sede').find('.comune-value');
	var comune_des_value = $(this).parentsUntil('.form-sede').find('.comune-des-value');
	comune_value.val('').trigger('change');
	comune_value.val($(this).val()).trigger('change');
	if($(this).find('option:selected').length > 0){
		comune_des_value.val($(this).find('option:selected')[0].innerHTML).trigger('change');
	}
});

$(document).on('change', '.select-comune-contatto', function(){
	var comune_value_cont = $(this).parentsUntil('.form-contatto').find('.comune-value-contatto');
	var comune_des_value_cont = $(this).parentsUntil('.form-contatto').find('.comune-des-value-contatto');
	comune_value_cont.val('').trigger('change');
	comune_value_cont.val($(this).val()).trigger('change');
	if($(this).find('option:selected').length > 0){
		comune_des_value_cont.val($(this).find('option:selected')[0].innerHTML).trigger('change');
	}
});


//########################################################################################################################
//Gestione invio dati anagrafica a fatture in cloud
$(document).on('click','.send-anagrafica',function(e){

	var idAzienda = $(this).attr('data-id');

	var confirmation = confirm('Sei sicuro di voler inviare l\'anagrafica a Fatture in Cloud?');

	if(confirmation){
		$.ajax({
		    url : pathServer + "aziende/Ws/sendAnagrafica/" + idAzienda,
		    type: "GET",
		    dataType: "json",
		    success : function (data,stato) {

		        if(data.response == "OK"){

		        	reloadTableAziende();

					alert(data.msg);

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

$(document).on('click','.delete-contatto',function(e){	
	e.preventDefault();
	
	if(confirm('Si è sicuri di voler eliminare il contatto?')){
		deleteContatto($(this).attr('data-id'));
	}
});

$(document).on('click','.delete-sede',function(e){	
	e.preventDefault();
	
	if(confirm('Si è sicuri di voler eliminare la struttura?')){
		deleteSede($(this).attr('data-id'));
	}
});

$(document).on('click','#box-general-action', function(){
	clearModale();
});

function deleteAzienda(id){

	$.ajax({
	    url : pathServer + "aziende/Ws/deleteAzienda/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	reloadTableAziende();

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function afterSaveModalAziende(){

	if($('#table-aziende').length > 0){

		$('.close').click();
		reloadTableAziende();

	}else if($('.box-dati-aziendali').length > 0){

		$.ajax({
			url : pathServer + "aziende/Ws/sendNoticeCompaneeAdminEdit",
			type: "POST",
			dataType: "json",
			success : function (data,stato) {
			},
			error : function (richiesta,stato,errori) {
				alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
			}
		});

		alert('Dati salvati correttamente.');

	}else{
		$('.close').click();
		location.reload();
	}
	
}

function reloadTableAziende(){
	var pager = $("#table-aziende")[0].config.pager;
	setTableSorterTempPager('table-aziende', [(pager.page+1),pager.size] );
	$("#table-aziende").trigger("update");

}

function disableInputModale(){

	$('#myModalAzienda input[type="text"]').each(function(){

		$(this).attr('disabled',true);

	});

}

function enableInputModale(){

	$('#myModalAzienda input').each(function(){

		$(this).removeAttr('disabled');

	});
}

function loadInputModale(idAzienda,tabs){
	angular.element('#myModalAzienda').scope().vm.loadAzienda(idAzienda,tabs);
	/*$.ajax({
	    url : pathServer + "aziende/Ws/loadAzienda/" + idAzienda,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	$('[name="Denominazione"]').val(data.data.denominazione);
				$('[name="Nome"]').val(data.data.nome);
				$('[name="Cognome"]').val(data.data.cognome);
				$('[name="Telefono"]').val(data.data.telefono);
				$('[name="Fax"]').val(data.data.fax);
				$('[name="idAzienda"]').val(data.data.id);
	        	$('[name="emailInfo"]').val(data.data.email_info);
				$('[name="emailContabilita"]').val(data.data.email_contabilita);
				$('[name="emailSolleciti"]').val(data.data.email_solleciti);
				$('[name="codicePaese"]').val(data.data.cod_paese);
				$('[name="piva"]').val(data.data.piva);
				$('[name="cf"]').val(data.data.cf);
				$('[name="pec"]').val(data.data.pec);
				$('[name="sito_web"]').val(data.data.sito_web);
				if(data.data.cliente == 1){
					$('[name="checkCliente"]').prop('checked',true);
				}else{
					$('[name="checkCliente"]').prop('checked',false);
				}
				if(data.data.fornitore == 1){
					$('[name="checkFornitore"]').prop('checked',true);
				}else{
					$('[name="checkFornitore"]').prop('checked',false);
				}
				if(data.data.interno == 1){
					$('[name="checkInterno"]').prop('checked',true);
				}

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});*/

}

function clearModale(){
	angular.element('#myModalAzienda').scope().vm.resetModel();
	/*$('[name="Denominazione"]').val("");
	$('[name="Nome"]').val("");
	$('[name="Cognome"]').val("");
	$('[name="Telefono"]').val("");
	$('[name="Fax"]').val("");
	$('[name="idAzienda"]').val("");
	$('[name="emailInfo"]').val("");
	$('[name="emailContabilita"]').val("");
	$('[name="emailSolleciti"]').val("");
	$('[name="codicePaese"]').val("IT");
	$('[name="piva"]').val("");
	$('[name="cf"]').val("");
	$('[type="checkbox"]').removeAttr('checked');
	$('[name="pec"]').val("");
	$('[name="sito_web"]').val("");*/

}
