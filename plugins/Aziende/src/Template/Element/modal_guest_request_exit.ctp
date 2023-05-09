<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modal guest request exit  (https://www.companee.it)
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

<div class="modal fade" id="modalGuestRequestExit" ref="modalGuestRequestExit" role="dialog" aria-labelledby="modalGuestRequestExitLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Procedura di richiesta uscita per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formGuestRequestExit">
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': requestExitProcedureData.exit_type_id.hasError}">
							<label :class="{'required': requestExitProcedureData.exit_type_id.required}" for="exitType">Motivazione</label>
							<select v-model="requestExitProcedureData.exit_type_id.value" id="exitType" class="form-control" @change="updateRequestExitRequirements()">
								<option v-for="type in requestExitTypes" :value="type.id">{{type.name}}</option>
							</select>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': requestExitProcedureData.file.hasError}">
							<label :class="{'required': requestExitProcedureData.file.required}" for="exitFile">Documento</label>
							<input type="file" value="requestExitProcedureData.file.value" @input="requestExitProcedureData.file.value = $event.target.files[0]" id="requestExitFile" class="form-control request-exit-file">
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': requestExitProcedureData.note.hasError}">
							<label :class="{'required': requestExitProcedureData.note.required}" for="exitNote">Note</label>
							<textarea v-model="requestExitProcedureData.note.value" id="requestExitNote" class="form-control request-exit-note"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-olive" @click="requestExitProcedure()">Richiedi uscita ospite</button>
			</div>
		</div>
	</div>
</div>