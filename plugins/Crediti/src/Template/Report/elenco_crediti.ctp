<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Elenco Crediti  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
use Cake\Core\Configure;
?>

<?php echo $this->Element('Crediti.include'); ?>
<script>

$.fn.datepicker.dates['it'] = {
    days: ["Domenica", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};


function isDate(date){
    var date = new Date(date);
    var res = Date.parse(date);
    if(res == "Invalid Date" || isNaN(res)){
      return false;
    }
    return true;
}

function setFilter(){
  $("#table-report").trigger("update");
}

    function setTable(){


        var lista_studi = "";
        var startDate = "";
        var endDate = "";


        $("#table-report").tablesorter({
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
              headers: {3:{sorter:false}, 4: { filter: false},5: { filter: false },6: { filter: false } }
          }).tablesorterPager({

                // **********************************
                //  Description of ALL pager options
                // **********************************

                // target the pager markup - see the HTML block below
                container: $(".pager"),
                ajaxUrl : pathServer + 'crediti/Ws/getCredits/?{filterList:filter}&{sortList:column}&size={size}&page={page}&startDate='+startDate+'&endDate='+endDate+' ',

                // modify the url after all processing has been applied
                customAjaxUrl: function(table, url) {
                    // manipulate the url string as you desire
                // url += '&cPage=' + window.location.pathname;
                // trigger my custom event
                $(table).trigger('changingUrl', url);
                // send the server the current page
                      $('#load-data').show();
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

    }

    $(document).ready(function(){

      setTable();



    });

</script>
<section class="content-header">
    <h1>
        Crediti
        <small>Elenco crediti attuali</small>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-bank"></i>Crediti</a></li>
        <li class="active">Elenco</li>
    </ol>
</section>

<section class="content">
    <div class="row">
      <div class="col-md-12">
      <div class="box">
        <div class="box-table-aziende box-body box-table">

                <div id="pager-aziende" class="pager col-sm-6">
                    <form>
                        <i class="first glyphicon glyphicon-step-backward"></i>
                        <i class="prev glyphicon glyphicon-backward"></i>
                        <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                        <i class="next glyphicon glyphicon-forward"></i>
                        <i class="last glyphicon glyphicon-step-forward"/></i>
                        <select class="pagesize">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                        </select>
                    </form>
                </div>
                <div class="col-sm-6" id="box-general-action">
                  <h4 class="sum-crediti">Totale: <?= $sommaCrediti ?> </h4>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body box-table">
                <table id="table-report" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th>Famiglia</th>
                      <th>Codice Cliente</th>
                      <th>Ragione Sociale</th>
                      <th>Numero documento</th>
                      <th>Data emissione</th>
                      <th>Data scadenza</th>
                      <th>Importo</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
                      <div id="pager-aziende" class="pager col-sm-6 pager-left">
                          <form>
                              <i class="first glyphicon glyphicon-step-backward"></i>
                              <i class="prev glyphicon glyphicon-backward"></i>
                              <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                              <i class="next glyphicon glyphicon-forward"></i>
                              <i class="last glyphicon glyphicon-step-forward"/></i>
                              <select class="pagesize">
                                  <option selected="selected" value="10">10</option>
                                  <option value="20">20</option>
                                  <option value="30">30</option>
                                  <option value="40">40</option>
                              </select>
                          </form>
                      </div>
              </div><!-- /.box-body -->
           </div><!-- /.box -->
    </div>
    </div>
</section>
