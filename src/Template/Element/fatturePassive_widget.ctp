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
