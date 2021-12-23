<?php
use Cake\Routing\Router;
?>
<script>
    (function ($) {
      $.fn.rotateTableCellContent = function (options) {
      /*
      Version 1.0
      7/2011
      Written by David Votrubec (davidjs.com) and
      Michal Tehnik (@Mictech) for ST-Software.com
      */
     
            var cssClass = ((options) ? options.className : false) || "vertical";
     
            var cellsToRotate = $('.' + cssClass, this);
     
            var betterCells = [];
            cellsToRotate.each(function () {
                var cell = $(this)
              , newText = cell.text()
              , height = cell.height()
              , width = cell.width()
              , newDiv = $('<div>', { height: width, width: height })
              , newInnerDiv = $('<div>', { text: newText, 'class': 'rotated' });
     
                newDiv.append(newInnerDiv);
     
                betterCells.push(newDiv);
            });
     
            cellsToRotate.each(function (i) {
                $(this).html(betterCells[i]);
            });
        };
        
    })(jQuery);

</script>
<script type="text/javascript">
  $(document).ready(function(){
     $('table').rotateTableCellContent();

      $('#yearID').change(function(){
            window.open(pathServer + 'consulenza/report/carico_lavoro/' + $('#yearID').val(),'_self');
      });

      $('#xls_export').click(function(){
          window.open(pathServer + 'consulenza/report/carico_lavoro/' + $('#yearID').val()+'/xls','_self');
      });            

  });
      
    
</script>

<section class="content-header">
  <h1>
    Carico di lavoro
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a><i class="fa fa-bar-chart"></i> Report</a></li>
    <li class="active">Carico lavoro</li>
  </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
        <div class="box">
                <div class="box-header row">
                  <div class="col-xs-9">
                    <h3  class="box-title" id="reportTitle" ><i class="fa fa-th"></i> Report Carico di Lavoro <?php echo $this->request->session()->read('Report.CaricoLavoro.year'); ?></h3>
                  </div>
                  <div class="col-xs-3 text-right">
                    <button class="btn btn-flat btn-default " id="xls_export" title="Esporta il report in formato xlsx per Excel"><img src="<?php echo Router::url('/'); ?>img/xls.png"></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="filter">
                    <label class="control-label pull-right">Anno 
                        <select class="form-control select2" id="yearID" >
                          <?php foreach ($years as $year) {
                            if($this->request->session()->read('Report.CaricoLavoro.year') && $this->request->session()->read('Report.CaricoLavoro.year')== $year){
                              echo '<option selected="selected" value="' . $year . '">' . $year . '</option>';
                            } else {
                              echo '<option value="' . $year . '">' . $year . '</option>';
                            }
                          }?>
                        </select>
                    </label>
                    <div class="clear"></div>
                </div>
                <div class="box-body box-table">
                  <table id="table-aziende" class="table table-bordered table-striped table-hover">
             <thead>
                <tr>
                  <th>OPERATORE</th>
                  <th class="vertical tableSelector">RIGHE CONTABILI</th>
                  <?php foreach($jobList as $job) { ?>
                    <th class="vertical tableSelector"><?=$job['name']?></th>
                  <?php }  ?>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($workLoad as $rData) { echo $this->element('workload_row',['rData'=>$rData, 'jobList'=> $jobList]); } ?>
              </tbody>
            </table>
                </div>
             </div>
      </div>
    </div>
</section>
