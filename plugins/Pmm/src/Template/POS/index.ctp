<?php

use Cake\Routing\Router;

echo $this->Element('Pmm.include');

?>

<script type="text/javascript">

	$(document).ready(function(){

		$('table#table-pos').tablesorter({
		  theme: 'bootstrap',
          headerTemplate: '{content} {icon}',
          widgets : [ 'zebra', 'columns', 'filter', 'uitheme' ],
          headers: {
          	9: { filter: false, sorter: false}
          }}).tablesorterPager({

                // **********************************
                //  Description of ALL pager options
                // **********************************

                // target the pager markup - see the HTML block below
                container: $(".pager"),
                ajaxUrl : '<?= Router::url(['plugin' => 'Pmm','controller' => 'Ajax','action' => 'getPOS']) ?>?{filterList:filter}&{sortList:column}&size={size}&page={page}',

                // modify the url after all processing has been applied
                customAjaxUrl: function(table, url) {
                    // manipulate the url string as you desire
                // url += '&cPage=' + window.location.pathname;
                // trigger my custom event
                $(table).trigger('changingUrl', url);
                // send the server the current page
                      $('#load-data').show();
											$("#loading-spinner-table").css('z-index',10);
                    return url;
                },

                // add more ajax settings here
                // see http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
                ajaxObject: {
                  dataType: 'json'
                },

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
                     $('#load-data').hide();
										 $("#loading-spinner-table").css('z-index',-10);
                    return [ total, rows, headers ];

                  }
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
          });



	});

</script>
<?= $this->element('loading_spinner_table') ?>
<section class="content-header">
    <h1>
        ELENCO POS
        <small> POS con visualizzazione del numero di adesione in stato PMM </small>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-home"></i>POS</a></li>
        <li class="active">Elenco</li>
    </ol>
</section>

<div class="pull-left col-lg-4">
    <?= $this->element('pager') ?>
</div>

<section class="content">
    <div class="row">
      <div class="col-md-12">
      <div class="box">
        <div class="box-body box-table" style="overflow:auto;">
          <table id="table-pos" class="table-bordered table-striped table-hover">
          	<thead>
          		<tr>
								<th>Ente aggregatore</th>
          			<th>POS</th>
          			<th>Adesioni</th>
          			<th>Indirizzo</th>
          			<th>Città</th>
          			<th>Provincia</th>
          			<th>Riferimento Tel.\Cell.</th>
          			<th>Cell.</th>
          			<th>Tel.</th>
          			<th>Azioni</th>
          		</tr>
          	</thead>
          	<tbody>

          	</tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>
