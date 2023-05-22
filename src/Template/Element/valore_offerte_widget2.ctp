<?php
/** 
* Companee :  valore_offerte_widget2   (https://www.companee.it)
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
      <canvas id="<?= $idOffer ?>"></canvas>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<script>

  var chartData = <?= $chartData ?>;
  var datasets = [];

  //console.log(chartData);

  $.each(chartData.datasets,function(index ,val ){
    data = [];
    $.each(val.data,function(index ,val ){
      data.push(val);
    });
    datasets.push({
      label: val.label,
      stack: val.stack,
      backgroundColor: val.backgroundColor,
      data: data
    });
  });

  var stackChartData = {
    labels: chartData.labels,
    datasets: datasets
  };

  console.log(stackChartData);

  $(document).ready(function(){
    //console.log("<?=$idOffer?>");
    var ctxOfferte = document.getElementById("<?=$idOffer?>").getContext("2d");
    window.myBar = new Chart(ctxOfferte, {
        type: 'bar',
        data: stackChartData,
        options: {
            title:{
                display:true,
                text:"* Accettata nel mese | ** Inviata nel mese"
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            },
            legend: {
                labels: {
                    usePointStyle: true
                }
            }
        }
    });

  });

</script>
