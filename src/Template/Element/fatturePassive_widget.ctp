<?php
/** 
* Companee :  fatturePassive_widget   (https://www.companee.it)
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
?>
<div class="box <?= $boxClass ?>">
  <div class="box-header with-border">
    <i class="ion ion-stats-bars"></i>
    <h3 class="box-title"><?= $label ?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div>
  <div class="box-body">
    <div class="chart">
      <canvas id="<?= $id ?>"></canvas>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<script>

  var fattChart = <?= $chartData ?>;
    var fattDataSets = [];

  $.each(fattChart.data,function(index ,val ){

    fattDataSets.push({
      label: index,
      backgroundColor: val.color,
      borderColor: val.color,
      borderWidth: 1,
      data: val.data
    });

  });

  var fattChartData = {
    labels: fattChart.labels,
    datasets: fattDataSets
  };

  $(document).ready(function() {
      console.log("<?= $id ?>");
      var ctxFattPass = document.getElementById("<?=$id?>").getContext("2d");
      window.myBar = new Chart(ctxFattPass, {
          type: 'bar',
          data: fattChartData,
          options: {
              responsive: true,
              legend: {
                  position: 'top',
              },
              title: {
                  display: false,
                  text: 'Chart.js Bar Chart'
              }
          }
      });

  });

</script>
