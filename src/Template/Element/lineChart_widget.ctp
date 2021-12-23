<?php
  if(!empty($chartData) && is_array($chartData)){
    $chartData = json_encode($chartData);
  }
?>
<script>
$(function () {
  <?php if(!empty($chartData)): ?>
      var chartData = <?= $chartData ?>;
  <?php else: ?>
      var url = '<?= $url ?>';
  <?php endif ?>
  //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    //
  var drawLineChart = function(data){
      var areaChartCanvas = $("#<?= $id ?>").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      var areaChart = new Chart(areaChartCanvas);
      var datasets = [];
      $.each(data.data,function(index ,val ){
            datasets.push(
              {
                label: index,
                fillColor: val.color,
                strokeColor: val.color,
                pointColor: val.color,
                pointStrokeColor: val.color,
                pointHighlightFill: "#fff",
                pointHighlightStroke: val.color,
                data: val.data
              }
            );
      });
      var areaChartData = {
        labels: data.labels,
        datasets: datasets
      };

      var areaChartOptions = {
        //Boolean - If we should show the scale at all
        showScale: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - Whether the line is curved between points
        bezierCurve: true,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.3,
        //Boolean - Whether to show a dot for each point
        pointDot: <?= (!empty($point) ? 'true' : 'false' ) ?>,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a color
        datasetFill: <?= (!empty($fill) ? 'true' : 'false' ) ?>,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: true,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true,

        multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"

      };

      //Create the line chart
      areaChart.Line(areaChartData, areaChartOptions);
  };

  if (typeof url === 'string') {
    $.ajax({
  	    url : url,
  	    type: "GET",
  	    dataType: "json",
  	    success : function (data,stato) {
          if(data.response == 'OK'){
              drawLineChart(data.data);
          }else{
            alert(data.msg);
          }
        },
        error : function(){

        }
      });
  }else{
    drawLineChart(chartData);
  }

});
</script>
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
  <!-- /.box-header -->
  <div class="box-body">
    <div class="chart-responsive">
      <canvas id="<?= $id ?>"></canvas>
    </div>
  </div>
  <!-- /.box-body -->

</div>
