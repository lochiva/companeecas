$(document).ready(function(){

	//alert('ready');

	$("#table-sedi").tablesorter({
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
        headers: { 8: { filter: false, sorter:false} }
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
          ajaxUrl : pathServer + 'aziende/Ws/getSedi/' + idAzienda + '?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
    
    //Select2 provincia e comune
    $('#inputProvincia').select2({
      language: 'it',
      width: '100%',
      placeholder: 'Seleziona una provincia',
      closeOnSelect: true,
      dropdownParent: $('#inputProvincia').parent(),
    });
    
    $('#inputProvincia').val("").trigger('change');

});

//########################################################################################################################
//Gestione Cancella Sede
$(document).on('click','.delete',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare la struttura?')){
		deleteSede($(this).attr('data-id'));
	}

});

//########################################################################################################################
//Gestione Edit Azienda
$(document).on('click','.edit',function(e){

	disableInputModale();

	var idSede = $(this).attr('data-id');
	//alert('edit ' + idAzienda);
	loadInputModale(idSede);

});

//#######################################################################################################################
//Gestione select provincia e comune
$(document).on('change', '#inputProvincia', function(){
	var provinciaId = $(this).val();

	$.ajax({
		url : pathServer+'ws/autocompleteComune/'+provinciaId,
		type: "GET",
		dataType: "json",
		success : function(response) { 
			$('#inputComune').empty();
			$('#inputComune').select2({
				language: 'it',
				width: '100%',
				placeholder: 'Seleziona un comune',
				closeOnSelect: true,
				dropdownParent: $('#inputComune').parent(),
				data: response.data
      });

      $('#inputComune').val($('#comuneValue').val()).trigger('change');
		},
		error : function (response){

		}
	});
});

$(document).on('change', '#inputComune', function(){
	$('#comuneValue').val($(this).val());
});


function deleteSede(id){

	$.ajax({
	    url : pathServer + "aziende/Ws/deleteSede/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	reloadTableSedi();

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

	$('#myModalSede input[type="text"]').each(function(){

		$(this).attr('disabled',true);

	});

}

function enableInputModale(){

	$('#myModalSede input').each(function(){

		$(this).removeAttr('disabled');

	});
}

function loadInputModale(idSede){

	$.ajax({
	    url : pathServer + "aziende/Ws/loadSede/" + idSede,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	$('[name="id"]').val(idSede);
            $('[name="id_azienda"]').val(idAzienda);
            if (role == 'admin') {
                $('#inputApproved').prop('checked', data.data.approved);
            }
            $('[name="code_centro"]').val(data.data.code_centro);
            $('[name="id_tipo_ministero"]').val(data.data.id_tipo_ministero);
            $('[name="id_tipo_capitolato"]').val(data.data.id_tipo_capitolato);
            $('[name="id_tipologia_centro"]').val(data.data.id_tipologia_centro);
            $('[name="id_tipologia_ospiti"]').val(data.data.id_tipologia_ospiti);
            $('[name="indirizzo"]').val(data.data.indirizzo);
            $('[name="num_civico"]').val(data.data.num_civico);
            $('[name="cap"]').val(data.data.cap);
            $('#comuneValue').val(data.data.comune);
            $('[name="provincia"]').val(data.data.provincia).trigger('change');
            $('[name="nazione"]').val(data.data.nazione);
            $('[name="referente"]').val(data.data.referente);
            $('[name="telefono"]').val(data.data.telefono);
            $('[name="cellulare"]').val(data.data.cellulare);
            $('[name="fax"]').val(data.data.fax);
            $('[name="email"]').val(data.data.email);
            $('[name="skype"]').val(data.data.skype);
            $('[name="n_posti_struttura"]').val(data.data.n_posti_struttura);
            $('[name="n_posti_effettivi"]').val(data.data.n_posti_effettivi);
            $('[name="n_posti_convenzione"]').val(data.data.n_posti_convenzione);
            $('[name="id_procedura_affidamento"]').val(data.data.id_procedura_affidamento);
            $('[name="operativita"]').val(data.data.operativita);

            enableInputModale();

            // Se utente di ruolo ente e convenzione approvata, disabilito form e mostro messaggio
            if (role == 'ente' && data.data.approved) {
                $('.approved-message').show();
                disableApprovedModal();
            }

            $('#inputCapienzaConvenzione').prop('disabled', true);
            $('#inputProceduraAffidamento').prop('disabled', true);

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function reloadTableSedi(){

	var pager = $("#table-sedi")[0].config.pager;
	setTableSorterTempPager('table-sedi', [(pager.page+1),pager.size] );
	$("#table-sedi").trigger("update");
    lastSortList=$("#table-sedi")[0].config.sortList;
    $("#table-sedi").trigger("sorton", false);
    $("#table-sedi").trigger("sorton", [lastSortList]);

}

function clearModale(){

  $('.approved-message').hide();
	$('[name="id"]').val("");
	$('[name="id_azienda"]').val(idAzienda);
  $('[name="code_centro"]').val("");
  $('[name="code_centro"]').prop("disabled", false);
  $('[name="code_centro"]').removeClass('disabled-approved');
  $('[name="id_tipo_ministero"]').val("");
	$('[name="id_tipo_ministero"]').prop("disabled", false);
  $('[name="id_tipo_ministero"]').removeClass('disabled-approved');
  $('[name="id_tipo_capitolato"]').val("");
  $('[name="id_tipo_capitolato"]').prop("disabled", false);
  $('[name="id_tipo_capitolato"]').removeClass('disabled-approved');
  $('[name="id_tipologia_centro"]').val(1);
  $('[name="id_tipologia_centro"]').prop("disabled", false);
  $('[name="id_tipologia_centro"]').removeClass('disabled-approved');
  $('[name="id_tipologia_ospiti"]').val("");
  $('[name="id_tipologia_ospiti"]').prop("disabled", false);
  $('[name="id_tipologia_ospiti"]').removeClass('disabled-approved');
  $('[name="indirizzo"]').val("");
	$('[name="indirizzo"]').prop("disabled", false);
  $('[name="indirizzo"]').removeClass('disabled-approved');
  $('[name="num_civico"]').val("");
	$('[name="num_civico"]').prop("disabled", false);
  $('[name="num_civico"]').removeClass('disabled-approved');
  $('[name="cap"]').val("");
	$('[name="cap"]').prop("disabled", false);
  $('[name="cap"]').removeClass('disabled-approved');
  $('[name="provincia"]').val("").trigger('change');
	$('[name="provincia"]').prop("disabled", false);
  $('[name="provincia"]').removeClass('disabled-approved');
  $('#comuneValue').val("");
  $('#comuneValue').prop("disabled", false);
  $('#comuneValue').removeClass('disabled-approved');
  $('[name="comune"]').val("").trigger('change');
	$('[name="comune"]').prop("disabled", false);
  $('[name="comune"]').removeClass('disabled-approved');
  $('[name="nazione"]').val("");
	$('[name="nazione"]').prop("disabled", false);
  $('[name="nazione"]').removeClass('disabled-approved');
  $('[name="referente"]').val("");
  $('[name="referente"]').prop("disabled", false);
  $('[name="referente"]').removeClass('disabled-approved');
  $('[name="telefono"]').val("");
	$('[name="telefono"]').prop("disabled", false);
  $('[name="telefono"]').removeClass('disabled-approved');
  $('[name="cellulare"]').val("");
	$('[name="cellulare"]').prop("disabled", false);
  $('[name="cellulare"]').removeClass('disabled-approved');
  $('[name="fax"]').val("");
	$('[name="fax"]').prop("disabled", false);
  $('[name="fax"]').removeClass('disabled-approved');
  $('[name="email"]').val("");
	$('[name="email"]').prop("disabled", false);
  $('[name="email"]').removeClass('disabled-approved');
  $('[name="skype"]').val("");
	$('[name="skype"]').prop("disabled", false);
  $('[name="skype"]').removeClass('disabled-approved');
  $('[name="n_posti_struttura"]').val("");
  $('[name="n_posti_struttura"]').prop("disabled", false);
  $('[name="n_posti_struttura"]').removeClass('disabled-approved');
  $('[name="n_posti_effettivi"]').val("");
  $('[name="n_posti_effettivi"]').prop("disabled", false);
  $('[name="n_posti_effettivi"]').removeClass('disabled-approved');
  $('[name="n_posti_convenzione"]').val("");
  $('[name="n_posti_convenzione"]').removeClass('disabled-approved');
  $('[name="id_procedura_affidamento"]').val("");
  $('[name="id_procedura_affidamento"]').removeClass('disabled-approved');
  $('[name="operativita"]').val(1);
  $('[name="operativita"]').prop("disabled", false);
  $('[name="operativita"]').removeClass('disabled-approved');

}

function disableApprovedModal() {
  $('[name="code_centro"]').prop("disabled", true);
  $('[name="code_centro"]').addClass('disabled-approved');
	$('[name="id_tipo_ministero"]').prop("disabled", true);
  $('[name="id_tipo_ministero"]').addClass('disabled-approved');
  $('[name="id_tipo_capitolato"]').prop("disabled", true);
  $('[name="id_tipo_capitolato"]').addClass('disabled-approved');
  $('[name="id_tipologia_centro"]').prop("disabled", true);
  $('[name="id_tipologia_centro"]').addClass('disabled-approved');
  $('[name="id_tipologia_ospiti"]').prop("disabled", true);
  $('[name="id_tipologia_ospiti"]').addClass('disabled-approved');
	$('[name="indirizzo"]').prop("disabled", true);
  $('[name="indirizzo"]').addClass('disabled-approved');
	$('[name="num_civico"]').prop("disabled", true);
  $('[name="num_civico"]').addClass('disabled-approved');
	$('[name="cap"]').prop("disabled", true);
  $('[name="cap"]').addClass('disabled-approved');
	$('[name="provincia"]').prop("disabled", true);
  $('[name="provincia"]').addClass('disabled-approved');
  $('#comuneValue').prop("disabled", true);
  $('#comuneValue').addClass('disabled-approved');
	$('[name="comune"]').prop("disabled", true);
  $('[name="comune"]').addClass('disabled-approved');
	$('[name="nazione"]').prop("disabled", true);
  $('[name="nazione"]').addClass('disabled-approved');
  $('[name="referente"]').prop("disabled", true);
  $('[name="referente"]').addClass('disabled-approved');
	$('[name="telefono"]').prop("disabled", true);
  $('[name="telefono"]').addClass('disabled-approved');
	$('[name="cellulare"]').prop("disabled", true);
  $('[name="cellulare"]').addClass('disabled-approved');
	$('[name="fax"]').prop("disabled", true);
  $('[name="fax"]').addClass('disabled-approved');
	$('[name="email"]').prop("disabled", true);
  $('[name="email"]').addClass('disabled-approved');
	$('[name="skype"]').prop("disabled", true);
  $('[name="skype"]').addClass('disabled-approved');
  $('[name="n_posti_struttura"]').prop("disabled", true);
  $('[name="n_posti_struttura"]').addClass('disabled-approved');
  $('[name="n_posti_effettivi"]').prop("disabled", true);
  $('[name="n_posti_effettivi"]').addClass('disabled-approved');
  $('[name="n_posti_convenzione"]').addClass('disabled-approved');
  $('[name="id_procedura_affidamento"]').addClass('disabled-approved');
  $('[name="operativita"]').prop("disabled", true);
  $('[name="operativita"]').addClass('disabled-approved');
}
