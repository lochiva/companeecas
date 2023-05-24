<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Modal Confirm Guest Transfer  (https://www.companee.it)
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

<div class="modal fade" id="modalConfirmGuestTransfer" ref="modalConfirmGuestTransfer" role="dialog" aria-labelledby="modalConfirmGuestTransferLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Conferma ingresso per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formConfirmGuestTransfer">
					<div class="form-group">
						<div class="col-md-12" :class="{'has-error': acceptTransferProcedureData.check_in_date.hasError}">
							<label :class="{'required': acceptTransferProcedureData.check_in_date.required}" for="checkOutDate"><?= __('Check-in') ?></label>
							<datepicker :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" 
								typeable="true" id="checkOutDate" v-model="acceptTransferProcedureData.check_in_date.value">
							</datepicker>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-violet" @click="acceptTransferProcedure()">Conferma ingresso ospite</button>
			</div>
		</div>
	</div>
</div>