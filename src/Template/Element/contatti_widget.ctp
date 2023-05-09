<?php
/** 
* Companee :    contatti_widget (https://www.companee.it)
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