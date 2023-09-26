$(function(){

	//alert('ready');

	$("#table-statements").tablesorter({
        theme: 'bootstrap',
        headerTemplate: '{content} {icon}',
        widgets : [ 'zebra', 'cssStickyHeaders' , 'columns', 'filter', 'saveSort', 'uitheme' ],
        widgetOptions: {
			cssStickyHeaders_offset        : $('header:first').height(),
			cssStickyHeaders_addCaption    : false,
			// jQuery selector or object to attach sticky header to
			cssStickyHeaders_attachTo      : null,
			cssStickyHeaders_filteredToTop : false,
			cssStickyHeaders_zIndex        : 99000,

			saveSort: true,
			filter_saveFilters : true,

			filter_functions:{
				//1:function(e,n,f,i,$r){return e===f;},
				//2:provider,
			},
			filter_formatter: {
				'.filters-reset': function ($cell, indx) {
          var $btn = $('<button type="button" class="btn btn-default btn-block btn-reset-filters-statements"><i class="fa fa-eraser"></i></button>').appendTo($cell);
          $cell.removeClass('tablesorter-filter');
          return $btn;

				},
			},
        },

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
          ajaxUrl : pathServer + 'aziende/Ws/getStatementCompanies/?{filterList:filter}&{sortList:column}&size={size}&page={page}',

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
          //headers = data.headers,
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
              return [ total, rows ];

            }else{

						}
						$('#template-spinner').hide();
          },
		  processAjaxOnInit: true,
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

		//resetto filtri al click
		$('.btn-reset-filters-statements').click(function () { $("#table-statements").trigger('filterReset').trigger('sortReset').trigger('update'); return false; });
});

$(document).on('click','.download-statement',function(e){
	e.preventDefault();

  $('#template-spinner').show();
  document.cookie = 'downloadStarted=0;path=/';

  window.location = pathServer + 'aziende/ws/downloadZipStatements/' + $(this).data('statement');

  checkCookieForLoader('downloadStarted', '1');
});

function checkCookieForLoader(name, value) {
  var cookie = getCookie(name);

  if (cookie == value) {
      $('#template-spinner').hide();
      document.cookie = 'downloadStarted=0;path=/';
  } else {
      setTimeout(function () { checkCookieForLoader(name, value); }, 300);
  }
}