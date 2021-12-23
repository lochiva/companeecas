<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>

<?php echo $this->Element('Consulenza.include'); ?>
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


$(function() {

      $("#datepicker").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});
      $("#datepicker2").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});

  });

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
        var lista_studi = '';
        <?php if(isset($azienda) && !empty($azienda['denominazione'])){
          $name = $azienda['denominazione'];
          $clienteID = $azienda['id'];
          echo "var cliente = '".addslashes($name)."';";
          echo "var clienteID = '".$clienteID."';";

        }else{
          echo "var cliente = 'Pinco Pallino';";
        }

         ?>
        var startDate = "";
        var endDate = "";
        $('#nomeCliente').html(cliente);
        $('#clienteID').val(clienteID);

        $('#officeID option:selected').each(function(index){

            if($(this).text() != 'undefined' && $(this).text() != '')
            {
              if(index > 0)
                lista_studi = lista_studi + ", ";

              lista_studi = lista_studi + $(this).text();
            }

        });
        if($("#datepicker").val() != '' && $("#datepicker2").val() != '' && $("#datepicker").val().length == 10 && $("#datepicker2").val().length == 10  ){
          startDate = $("#datepicker").val();
          endDate = $("#datepicker2").val();



          $('#reportTitle').html('<i  class="fa fa-th"></i> REPORT SINTETICO PER CAUSALE '+cliente+' DAL ' + startDate + ' AL '+ endDate +' '+ lista_studi + ' RIF '+$('#yearID option:selected').text());

        }else{

          $('#reportTitle').html('<i  class="fa fa-th"></i> REPORT SINTETICO PER CAUSALE '+cliente+' ' + $('#yearID option:selected').text() + ' ' + lista_studi);
        }


        var causaleID = $('#causaleID').val();
        var yearID = $('#yearID').val();
        var officeID = ($('#officeID').val() != null ? $('#officeID').val() : -1);

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
              headers: { 2: { filter: false},3: { filter: false },4: { filter: false } }
          }).tablesorterPager({

                // **********************************
                //  Description of ALL pager options
                // **********************************

                // target the pager markup - see the HTML block below
                container: $(".pager"),
                ajaxUrl : pathServer + 'consulenza/Ws/getReportScostamentiAzienda/'+yearID+'/'+clienteID+'/'+officeID+'/?{filterList:filter}&{sortList:column}&size={size}&page={page}&startDate='+startDate+'&endDate='+endDate+' ',

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


      $('#yearID').change(function(){
        //("#datepicker").val('');
        //$("#datepicker2").val('');
        setTable();
        setFilter();
      });

      $('#officeID').change(function(){
        setTable();
        setFilter();
      });

      $('#xls_export').click(function(){
          window.open(pathServer + 'consulenza/report/cliente/'+$('#yearID').val()+'/'+$('#clienteID').val()+'/'+($('#officeID').val() != null ? $('#officeID').val() : -1)+'/xls','_self');
      });


    });

</script>
<section class="content-header">
  <h1>
    Report sintetico per il cliente: <b id="nomeCliente">Cliente 1</b>
    <small>Report sintetico con causale</small>
  </h1>
  <input type="hidden" id="clienteID" value="" />
  <ol class="breadcrumb">
    <li><a href="<?php echo Router::url('/consulenza/report/clienti');?>"><i class="fa fa-pie-chart"></i> Report clienti</a></li>
    <li class="active">Report per cliente</li>
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
	                    <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Anno</label>
                          <select class="form-control select2" id="yearID" style="width: 100%;">
                            <?php foreach ($years as $year) {
                              if($this->request->session()->read('Report.ScostamentiClienti.year') && $this->request->session()->read('Report.ScostamentiClienti.year')== $year){
                                echo '<option selected="selected" value="' . $year . '">' . $year . '</option>';
                              } else {
                                echo '<option value="' . $year . '">' . $year . '</option>';
                              }
                            }?>
                          </select>
                        </div>
	                    </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Studio</label>
                          <select multiple class="form-control select2" id="officeID" style="width: 100%;">
                            <?php
                              // creo l'array con la lista degli studi selezionati
                              if($this->request->session()->read('Report.ScostamentiClienti.office'))
                                $selected_office = explode(",",$this->request->session()->read('Report.ScostamentiClienti.office'));
                              else
                                $selected_office = array();

                              foreach ($offices as $office) {
                                if(in_array($office->id, $selected_office)){
                                  echo '<option selected="selected" value="' . $office->id . '">' . $office->name . '</option>';
                                } else {
                                  echo '<option value="' . $office->id . '">' . $office->name . '</option>';
                                }

                            }?>
                          </select>
                      </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="col-md-9">
      <div class="box">
              <div class="box-header row">
                <div class="col-xs-9">
                  <h3  class="box-title" id="reportTitle" ></h3>
                </div>
                <div class="col-xs-3 text-right">
                  <button class="btn btn-flat btn-default " id="xls_export" title="Esporta il report in formato xlsx per Excel"><img src="<?php echo Router::url('/'); ?>img/xls.png"></button>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body box-table">
                <table id="table-report" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th data-toggle="tooltip" data-html="true" title="Causale lavoro">Causale</th>
                      <th data-toggle="tooltip" data-html="true" title="Stato del lavoro">Stato</th>
                      <th data-toggle="tooltip" data-html="true" title="Totale delle ore che sono state pianificate con il tasto 'genera' dalla pagina di impostazione del cliente. Vengono sommate sia le ore già messe in calendario sia quelle ancora in coda.  Se viene impostato un filtro dal - al il valore è sempre zero">Pianificato</th>
                      <th data-toggle="tooltip" data-html="true" title="Totale delle ore programmate in calendario ancora da svolgere, nel periodo indicato dal - al ">Programmato</th>
                      <th data-toggle="tooltip" data-html="true" title="Totale delle ore programmate  in calendario già completate, nel periodo indicato dal - al">Consuntivato</th>
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
