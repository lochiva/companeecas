<?php
/** 
* Companee :  infoBox-contatti_widget   (https://www.companee.it)
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

<div class="small-box <?= $boxClass ?>">
    <div class="inner">
      <h3><?= $tot['contatti'] ?></h3>
      <p><?= $label ?></p>
    </div>
    <div class="icon">
      <i class="ion ion-android-contacts"></i>
    </div>
    <a href="<?=$this->Url->build('/aziende/contatti/index/all') ?>" class="small-box-footer"><b>Gestione Contatti</b> <i class="fa fa-arrow-circle-right"></i></a>
</div>