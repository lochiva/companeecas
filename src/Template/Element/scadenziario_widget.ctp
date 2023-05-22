<?php
/** 
* Companee :  scadenziario_widget   (https://www.companee.it)
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
  <div class="box-header ui-sortable-handle" style="cursor: move;">
    <i class="ion ion-clock"></i>

    <h3 class="box-title"><?= $label ?></h3>

    <div class="box-tools pull-right">

    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <ul class="todo-list ui-sortable">
      <?php foreach ($scadenze as $value): ?>
        <li>
              <span class="handle ui-sortable-handle">
                <i class="fa fa-ellipsis-v"></i>
                <i class="fa fa-ellipsis-v"></i>
              </span>
          <span class="text"><?= h($value->descrizione) ?></span>

          <small class="label pull-right pull-right label-<?= $value->label ?>" style="font-size:75%;">
            <i class="fa fa-clock-o"></i> <?= $value->data ?>
          </small>
        </li>
      <?php endforeach; ?>

    </ul>
    <br/>
    <div class="row">
      <div class="col-sm-3">
        <i class="fa fa-circle-o text-red"></i> Scaduto
      </div>
      <div class="col-sm-3">
        <i class="fa fa-circle-o text-yellow"></i> Settimana Attuale
      </div>

      <div class="col-sm-3">
        <i class="fa fa-circle-o text-aqua"></i> Oltre la settimana
      </div>
      <div class="col-sm-3">
        <i class="fa fa-circle-o text-green"></i> Fatto
      </div>
    </div>

  </div>
  <!-- /.box-body -->
  <div class="box-footer clearfix no-border">
    <a href="<?=$this->Url->build('/scadenzario/home/index') ?>" type="button" class="btn btn-default pull-right">Vai allo scadenziario </a>
  </div>
</div>