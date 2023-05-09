<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modal guest exit  (https://www.companee.it)
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

<div class="modal fade" id="modalGuestExit" ref="modalGuestExit" role="dialog" aria-labelledby="modalGuestExitLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Procedura di uscita per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div v-if="role != 'admin'">
					<p class="exit-trasnfer-warning">NOTA: Non usare questo strumento per trasferire un ospite ad un altro ente. Il trasferimento viene avviato da Prefettura.</p>
				</div>
				<form class="form-horizontal" id="formGuestExit">
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': exitProcedureData.exit_type_id.hasError}">
							<label :class="{'required': exitProcedureData.exit_type_id.required}" for="exitType">Motivazione</label>
							<select :disabled="guestExitRequestStatus == 2" v-model="exitProcedureData.exit_type_id.value" id="exitType" class="form-control" @change="updateExitRequirements()">
								<option v-for="type in exitTypes" :value="type.id">{{type.name}}</option>
							</select>
						</div>
					</div>
					<div v-if="guestExitRequestStatus == 2 && authorizeRequestExitData.file" class="form-group"> 
						<div class="col-sm-12">
							<button type="button" class="btn btn-primary" 
								@click="downloadExitDocument(authorizeRequestExitData.file)">
								<i class="fa fa-download"></i> Scarica documento di revoca
							</button>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': exitProcedureData.file.hasError}">
							<label :class="{'required': exitProcedureData.file.required}" for="exitFile">Documento</label>
							<input type="file" value="exitProcedureData.file.value" @input="exitProcedureData.file.value = $event.target.files[0]" id="exitFile" class="form-control exit-file">
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': exitProcedureData.note.hasError}">
							<label :class="{'required': exitProcedureData.note.required}" for="exitNote">Note</label>
							<textarea v-model="exitProcedureData.note.value" id="exitNote" class="form-control exit-note"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-danger" @click="executeExitProcedure()">Esegui uscita ospite</button>
			</div>
		</div>
	</div>
</div>