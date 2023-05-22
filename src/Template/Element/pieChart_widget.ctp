<?php
/** 
* Companee :  pieChart_widget   (https://www.companee.it)
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
    <i class="<?= $icon ?>"></i>
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

  var roleChart = <?= $chartData ?>;
  var roleDataSets = [];
  var roleLabels = [];
  var backgroundColor = [];
  var data = [];
  var datasets = [];

  $.each(roleChart,function(index ,val ){

    roleLabels.push(val.label);
    backgroundColor.push(val.color);
    data.push(val.value);

  });

  datasets.push({
    data : data,
    backgroundColor : backgroundColor,
    label: 'Dataset 1'
  })

  var rolesChartData = {
    labels: roleLabels,
    datasets : datasets
  };

  var configRuoli = {
        type: 'doughnut',
        data: rolesChartData,
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: false,
                text: 'Chart.js Doughnut Chart'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

  $(document).ready(function() {
      //console.log("<?=$id?>");
      var ctxRuoli = document.getElementById("<?=$id?>").getContext("2d");
      window.myDoughnut = new Chart(ctxRuoli, configRuoli);
  });

</script>
