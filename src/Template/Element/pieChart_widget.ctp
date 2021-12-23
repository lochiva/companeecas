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
