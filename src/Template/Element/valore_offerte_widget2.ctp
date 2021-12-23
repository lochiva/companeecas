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
