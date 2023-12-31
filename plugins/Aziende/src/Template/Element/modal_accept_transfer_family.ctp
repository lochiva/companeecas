<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Modal Accept Transfer Family  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
?>

<div class="modal fade" id="acceptTransferFamily" ref="acceptTransferFamily" role="dialog" aria-labelledby="acceptTransferFamilyLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-confirm">Si desidera confermare l'ingresso di tutti gli ospiti associati alla famiglia?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary confirm-no" @click="acceptTransfer(0)" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary confirm-si" @click="acceptTransfer(1)" data-dismiss="modal">Sì</button>
      </div>
    </div>
  </div>
</div>