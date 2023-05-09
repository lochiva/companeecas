<?php
/** 
* Companee :  timeline_widget   (https://www.companee.it)
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
<ul class="timeline timeline-home">

    <!-- timeline time label -->
    <li class="time-label">
        <span class="bg-maroon">
            <b><?= $label ?></b>
        </span>
    </li>
    <!-- /.timeline-label -->

    <!-- timeline item -->
    <?php foreach ($movimenti as $value): ?>
      <li>
          <?= $this->Utils->userImage($value['id_user'],'user-image img-timeline') ?>
          <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i><?= $value['label']['data'] ?></span>
              <h3 class="timeline-header">
                <a href="<?=$this->Url->build('/registration/users/view/'.$value['id_user']) ?>">
                  <?= h($value['label']['user']) ?> </a>
                <?= h($value['label']['action']) ?></h3>
              <!--<div class="timeline-body">
                  <b>Documento:</b> Nome documento
              </div>-->
              <?php if (!empty($value['label']['link'])): ?>
                <div class="timeline-footer">
                    <a href="<?= h($value['label']['link']) ?>" class="btn btn-success btn-xs">visualizza il record</a>
                </div>
              <?php endif; ?>
          </div>
      </li>
    <?php endforeach; ?>

    <li>
      <i class="fa fa-clock-o bg-gray"></i>
    </li>
</ul>