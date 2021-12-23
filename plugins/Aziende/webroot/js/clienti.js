$(document).ready(function(){

	//alert('ready');

	$("#table-invoicepayable-attiva").tablesorter({
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
          ajaxUrl : pathServer + 'aziende/Ws/getClientiFatture/' + idCliente + '?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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

		}).bind('pagerChange', function(e, options){

				var tableId = e.currentTarget.id;
				var extId = '-'+idCliente;
				var pageSize = localStorage.getItem("tablesorter-pager-temp");

				if(pageSize != undefined && pageSize != null){
					pageSize = JSON.parse(pageSize);
					if(pageSize[tableId+extId] != undefined && pageSize[tableId+extId] != null){
						 $('#'+tableId).trigger('pageAndSize', pageSize[tableId+extId] );
						 delete pageSize[tableId+extId];
						 pageSize = JSON.stringify(pageSize);
						 localStorage.setItem("tablesorter-pager-temp",pageSize);
					}
				}
		});

});

$(document).on('click','.new-active-invoice',function(e){ 
	if(idCliente != 'all'){
		$('#myFormFatturaAttiva #idPayer').trigger("change");
	}
})

//########################################################################################################################
//Gestione Cancella Fattura
$(document).on('click','.delete-active-invoice',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare la fattura?')){
		deleteFatturaAttiva($(this).attr('data-id'));
	}

});

//########################################################################################################################
//Gestione Edit Fattura
$(document).on('click','.edit-active-invoice',function(e){

	disableInputModale();

	var idFattura = $(this).attr('data-id');

	loadInputModaleAttiva(idFattura);

	enableInputModale();

});

$(document).on('click','.clone',function(e){

	disableInputModale();

	var idFattura = $(this).attr('data-id');

	loadInputModaleAttiva(idFattura);
	$('.invoice-article').each(function(){
		$(this).find('[data-input="id"]').remove();
	});
	$('[name="id"]').val("");
	$('[name="num"]').val("");
	$('.datepicker').val('');
	$('.datepicker').datepicker('update');
	$('.attachment').html("");
	$('[name="metodo').val('');
	enableInputModale();

});

//########################################################################################################################
//Generazione fattura
$(document).on('click','.send-invoice',function(e){
	var idFattura = $(this).attr('data-id');

	$.ajax({
	    url : pathServer + "aziende/Ws/sendInvoiceAttiva/" + idFattura,
	    type: "POST",
	    dataType: "json",
		success: function (data,stato) {
	        if(data.response == "OK"){
				if($("#table-invoicepayable-attiva").length){
					reloadTableFattureAttive();
				}else{
					location.reload();
				}
	        }else{
	        	alert(data.msg);
	        }
		},
		error: function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

	return false;

});

function deleteFatturaAttiva(id){

	$.ajax({
	    url : pathServer + "aziende/Ws/deleteFatturaAttiva/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	if($("#table-invoicepayable-attiva").length){
					reloadTableFattureAttive();
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

function disableInputModale(){

	$('#myModalFatturaAttiva input[type="text"]').each(function(){

		$(this).attr('disabled',true);

	});

}

function enableInputModale(){

	$('#myModalFatturaAttiva input').each(function(){

		$(this).removeAttr('disabled');

	});
}

function loadInputModaleAttiva(idFattura){

	$.ajax({
	    url : pathServer + "aziende/Ws/loadFatturaAttiva/" + idFattura,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
				if(data.data.order != null){
						$("#myFormFatturaAttiva #idOrder").append('<option value="'+data.data.id_order+'">'+data.data.order.name+'</option>');
						$('#myFormFatturaAttiva #idOrder').val(data.data.id_order).trigger("change");
				}

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

				$("#myFormFatturaAttiva #idPayer").append('<option value="'+data.data.id_payer+'" >'+data.data.payer.denominazione+'</option>');

				if(idCliente == 'all'){
					$('#myFormFatturaAttiva #idPayer').val(data.data.id_payer).trigger("change");	
				}				

				if(data.data.metodo != ''){
					$('select[name="metodo"] option:contains('+data.data.metodo+')').prop('selected', true);
				}else{
					$('select[name="metodo"]').val('');
				}

				if(data.data.attachment != undefined && data.data.attachment != ""){
					$('.attachment').html('<a target="_blank" href="'+pathServer+'aziende/fornitori/getAttachment/'+data.data.attachment+'">Allegato</a>');
				}
				$('.inputNumber').trigger('focusout');
				
				if(data.data.invoices_articles.length > 0){
					var counter = 1;
					$('.invoice-article').first().find('[data-input="id"]').remove();
					var default_article = $('.invoice-article').first();
					$('.invoice-articles').html('');
					$.each(data.data.invoices_articles, function(key, article){ 
						var html = '<div class="invoice-article" data-counter="'+counter+'">';
						html += '<input hidden name="articoli['+counter+'][id]" data-input="id" value="'+article.id+'" />';
						html += default_article.html();
						html += '</div>';
						
						$('.invoice-articles').append(html);

						var new_article = $('.invoice-article').last();

						new_article.find('input, select').each(function(){
							var field = $(this).attr('data-input');
							if(field != 'id'){
								$(this).attr('name', 'articoli['+counter+']['+field+']');
								$(this).val(article[field]).trigger('change');
								if(field == 'name' || $(this).hasClass('inputNumber')){
									$(this).trigger('focusout');
								}
							}
						});

						new_article.find('.delete-article').attr('data-counter', counter);
						
						counter++;
					});
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

function reloadTableFattureAttive(){

	var pager = $("#table-invoicepayable-attiva")[0].config.pager;
	setTableSorterTempPager('table-invoicepayable-'+idCliente, [(pager.page+1),pager.size] );
	$("#table-invoicepayable-attiva").trigger("update");
    lastSortList=$("#table-invoicepayable-attiva")[0].config.sortList;
    $("#table-invoicepayable-attiva").trigger("sorton", false);
    $("#table-invoicepayable-attiva").trigger("sorton", [lastSortList]);

}

function clearModale(){
	if(idCliente == 'all'){
		$("#myFormFatturaAttiva #idPayer").html("");
	}
	$("#myFormFatturaAttiva #idOrder").html("");
	$('#myFormFatturaAttiva #idPayer').val('0').trigger("change");
	$('#myFormFatturaAttiva #idOrder').val('0').trigger("change");
	$('#myFormFatturaAttiva')[0].reset();
	$('[name="split_payment"]').attr("checked",false);
	$('.attachment').html('');
	$('[name="id"]').val("");
	$('.datepicker').datepicker('update');
	resetArticles();
}

function resetArticles(){
	var first_article = $('.invoice-article').first();
	$('.invoice-articles').html(first_article);
	$('.invoice-article input, .invoice-article select').each(function(){
		$(this).val('');
	});
	$('.invoice-article .article-title').html('Articolo 1');
}
