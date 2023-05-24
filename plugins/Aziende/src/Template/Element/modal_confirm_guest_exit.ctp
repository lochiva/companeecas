<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Modal Confirm Guest Exit  (https://www.companee.it)
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

<div class="modal fade" id="modalConfirmGuestExit" ref="modalConfirmGuestExit" role="dialog" aria-labelledby="modalConfirmGuestExitLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Conferma uscita per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formConfirmGuestExit">
					<div class="form-group">
						<div class="col-md-12" :class="{'has-error': confirmExitProcedureData.check_out_date.hasError}">
							<label :class="{'required': confirmExitProcedureData.check_out_date.required}" for="checkOutDate"><?= __('Check-out') ?></label>
							<datepicker :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" 
								typeable="true" id="checkOutDate" v-model="confirmExitProcedureData.check_out_date.value">
							</datepicker>
						</div>
					</div>
					<div v-if="exitData.file" class="form-group">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary" @click="downloadExitDocument(exitData.file)"><i class="fa fa-download"></i> Scarica documento</button>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-danger" @click="confirmExitProcedure()">Conferma uscita ospite</button>
			</div>
		</div>
	</div>
</div>