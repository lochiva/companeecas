<?php
/** 
* Companee :  infoBox_widget   (https://www.companee.it)
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
<!-- small box -->
<div class="small-box <?= $boxClass ?>">
  <div class="inner">
    <h3><?= $tot ?></h3>
    <p><?= $label ?></p>
  </div>
  <div class="icon">
    <i class="<?= $icon ?>"></i> 
  </div>
  <a href="<?=$this->Url->build($url) ?>" class="small-box-footer"><b><?= $label_link ?></b> <i class="fa fa-arrow-circle-right"></i></a>
</div>