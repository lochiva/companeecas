<div class="box <?= $boxClass ?>">
  <div class="box-header with-border">
    <i class="ion ion-android-contacts"></i>
    <h3 class="box-title"><?= $label ?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-md-8">
        <div class="chart-responsive">
          <canvas id="<?= $id ?>" height="160" width="329" style="width: 329px; height: 160px;"></canvas>
        </div>
        <!-- ./chart-responsive -->
      </div>
      <!-- /.col -->
      <div class="col-md-4 pieChart-legend">
        <!--<ul class="chart-legend clearfix">
          <li><i class="fa fa-circle-o text-red"></i> Direttore</li>
          <li><i class="fa fa-circle-o text-green"></i> Impiegato</li>
          <li><i class="fa fa-circle-o text-yellow"></i> Uff. vendite</li>
          <li><i class="fa fa-circle-o text-aqua"></i> Uff. acquisti</li>
          <li><i class="fa fa-circle-o text-light-blue"></i> Titolare</li>
        </ul>-->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.box-body -->

</div>