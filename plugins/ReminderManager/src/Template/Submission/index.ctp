<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Index  (https://www.companee.it)
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

echo $this->Element('ReminderManager.include');


?>
<?php echo $this->Html->css('ReminderManager.submissions_list', ['block' => 'css']); ?>
<?php echo $this->Html->script('ReminderManager.functions', ['block' => 'script']); ?>
<script>

var urlChangestatusSubmission = '<?=Router::url('/reminder_manager/ws/changeStatusSubmission/')?>';
var urlSubmissionList = '<?=Router::url('/reminder_manager/submission/')?>';
var urlCloneSubmission = '<?=Router::url('/reminder_manager/ws/cloneSubmission/')?>';
// ###################################################################################################################################################
// Gestione stop

$(document).on('click', '.btn-stop', function(e){

  //alert('qui');
  e.preventDefault();
  var newStatus = 4;
  changeStatusSubmission(newStatus,$(this).attr('data-id'));

});

$(document).on('click', '.btn-restart', function(e){

  //alert('qui');
  e.preventDefault();
  var newStatus = 1;
  changeStatusSubmission(newStatus,$(this).attr('data-id'));

});

$(document).on('click', '.btn-clone', function(e){

  //alert('qui');
  e.preventDefault();
  cloneSubmission($(this).attr('data-id'));

});

$(document).ready(function(){

  // ##################################################################################################################################################################
  // Gestione date
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

  $("#datepicker").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});
  $("#datepicker2").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});

  // Fine gestione date
  // ##################################################################################################################################################################
  // Gestione table tablesorter

  setTable();

  // ##################################################################################################################################################################
  // Gestione change delle date

  $('#datepicker').datepicker().on('changeDate clearDate', function(e) {
    if($("#datepicker").val() != '' || $("#datepicker2").val() != '' ){
        setTable();
        setFilter();
    }
  });

  $("#datepicker2").datepicker().on('changeDate clearDate', function(e){
    if($("#datepicker").val() != '' || $("#datepicker2").val() != '' ){
      setTable();
      setFilter();
    }
  });

  // ##################################################################################################################################################################

});

//Funzioni
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

    // Per evitare di far spostare altri elementi dal tooltip
    $('[data-toggle=tooltip]').tooltip({container: 'body'});
    // costruisco la stringa degli studi
    var lista_studi = "";
    var startDate = "";
    var endDate = "";

    if(($("#datepicker").val() != '' && $("#datepicker").val().length == 10) || ($("#datepicker2").val() != '' && $("#datepicker2").val().length == 10) ){
      startDate = $("#datepicker").val();
      endDate = $("#datepicker2").val();
    }

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
              3:{
                'Salvato': function(e,n,f,i,$r){return e===f;},
                'Da Inviare': function(e,n,f,i,$r){return e===f;},
                'In Corso': function(e,n,f,i,$r){return e===f;},
                'Terminato': function(e,n,f,i,$r){return e===f;},
                'Sospeso': function(e,n,f,i,$r){return e===f;},
                'Errore': function(e,n,f,i,$r){return e===f;}
                }
                //2:provider,
            }
          },
          headers: {0: { filter: false }, 2: { filter: false },4: { filter: false } }
      }).tablesorterPager({

            // **********************************
            //  Description of ALL pager options
            // **********************************

            // target the pager markup - see the HTML block below
            container: $(".pager"),
            ajaxUrl : pathServer + 'reminder_manager/ws/getSubmissions/?{filterList:filter}&{sortList:column}&size={size}&page={page}&startDate='+startDate+'&endDate='+endDate+' ',

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

</script>

<section class="content-header">
  <h1>
    Promemoria Clienti
    <small>Elenco degli invii già eseguiti, da eseguire ed in corso.</small>
  </h1>
  <ol class="breadcrumb">
    <li><a><i class="fa  fa-envelope"></i> Promemoria</a></li>
    <li class="active">Home</li>
  </ol>
</section>

<section class="content">
    <div class="row">

        <div class="col-md-3">
          <div class="box box-solid">
              <div class="box-header with-border">
                    <i class="fa fa-filter"></i>
                    <h4 class="box-title">Filtri</h4>
                </div>
                <div class="box-body">
                	<div class="row">
                		<div class="col-lg-6 col-md-12">
		                     <div class="form-group">
		                        <label class="control-label">Dal</label>
		                        <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
                          <?php
                            $date = '';
                            if($this->request->session()->read('Report.ScostamentiClienti.startDate') ){
                              $date = $this->request->session()->read('Report.ScostamentiClienti.startDate');
                              echo '<input id="datepicker"  type="text"  class="form-control datepicker" value="'.$date.'" >';
                            } else {
                              echo '<input id="datepicker"  type="text"  class="form-control datepicker" >';
                            }
                          ?>

				                </div>
		                    </div>
		                </div>
		                <div class="col-lg-6 col-md-12">
		                     <div class="form-group">
		                        <label class="control-label">Al</label>
		                        <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
                          <?php
                            $date = '';
                            if($this->request->session()->read('Report.ScostamentiClienti.endDate') ){
                              $date = $this->request->session()->read('Report.ScostamentiClienti.endDate');
                              echo '<input id="datepicker2"  type="text"  class="form-control datepicker" value="'.$date.'" >';
                            } else {
                              echo '<input id="datepicker2"  type="text"  class="form-control datepicker" >';
                            }
                          ?>

				                </div>
		                    </div>
		                </div>


                    </div>
                </div>
            </div>
        </div>


      <div class="col-md-9">
      <div class="box box-primary">
              <div class="box-header row">
                <div class="col-xs-9">
                  <h3  class="box-title" id="reportTitle" >
                    Legenda Colori Invii
                  </h3>
                  <div class="legend">
                    <span class="badge bg-blue badge-list-submissions">Salvato</span>
                    <span class="badge bg-purple badge-list-submissions">Da Inviare</span>
                    <span class="badge bg-orange badge-list-submissions">In Corso</span>
                    <span class="badge bg-green badge-list-submissions">Terminato</span>
                    <span class="badge bg-maroon badge-list-submissions">Sospeso</span>
                    <span class="badge bg-red badge-list-submissions">Errore</span>
                  </div>
                </div>
                <div class="col-xs-3 text-right">
                <!--
                  <a class="btn btn-app" href="<?=Router::url('/reminder_manager/submission/newSchedini')?>">
                    <i class="fa fa-file-pdf-o"></i>
                    Schedini
                  </a>
                  -->
                  <a class="btn btn-app" href="<?=Router::url('/reminder_manager/submission/detail/generic')?>">
                    <i class="fa fa-newspaper-o"></i>
                    Generico
                  </a>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body box-table">
                <table id="table-report" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th data-toggle="tooltip" data-html="true" title="Data di creazione" style="width:15%;" >Data</th>
                      <th data-toggle="tooltip" data-html="true" title="Tipologia di invio" style="width:40%;">Tipo</th>
                      <th data-toggle="tooltip" data-html="true" title="Numero dei destinatari" style="width:10%;">Destinatari</th>
                      <th data-toggle="tooltip" data-html="true" title="% di completamento invio" style="width:15%;" >Completamento</th>
                      <th data-toggle="tooltip" data-html="true" title="Azioni" style="width:10%;">Azioni</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
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
              </div><!-- /.box-body -->
           </div><!-- /.box -->
    </div>
    </div>
</section>
