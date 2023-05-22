<?php
/** 
* Companee :  ordini_widget   (https://www.companee.it)
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
    <i class="ion ion-arrow-graph-up-right"></i>
    <h3 class="box-title"><?= $label ?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div>
  <div class="box-body">
    <div class="chart">
      <canvas id="<?= $id ?>" style="height: 335px; width: 669px;" height="670" width="1338"></canvas>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<script>

var ordersChart = <?= $chartData ?>;
var orderDataSets = [];

$.each(ordersChart.data,function(index ,val ){

  orderDataSets.push({
    label: index,
    backgroundColor: val.color,
    borderColor: val.color,
    fill: false,
    data: val.data
  });

});

var ordersChartData = {
  labels: ordersChart.labels,
  datasets: orderDataSets
};

var configOrdini = {
      type: 'line',
      data: ordersChartData,
      options: {
          responsive: true,
          title:{
              display:false,
              text:'Chart.js Line Chart'
          },
          tooltips: {
              mode: 'index',
              intersect: false,
          },
          hover: {
              mode: 'nearest',
              intersect: true
          },
          scales: {
              xAxes: [{
                  display: true,
                  scaleLabel: {
                      display: false,
                      labelString: 'Month'
                  }
              }],
              yAxes: [{
                  display: true,
                  scaleLabel: {
                      display: false,
                      labelString: 'Value'
                  }
              }]
          }
      }
  };

  $(document).ready(function(){
      //console.log("<?= $id ?>");
      var ctxOrdini = document.getElementById("<?= $id ?>").getContext("2d");
      new Chart(ctxOrdini, configOrdini);
  });

</script>
