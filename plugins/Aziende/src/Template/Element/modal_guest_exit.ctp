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
				<form class="form-horizontal" id="formGuestExit">
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': exitProcedureData.exit_type_id.hasError}">
							<label :class="{'required': exitProcedureData.exit_type_id.required}" for="exitType">Motivazione</label>
							<select v-model="exitProcedureData.exit_type_id.value" id="exitType" class="form-control" @change="updateExitNote()">
								<option v-for="type in exitTypes" :value="type.id">{{type.name}}</option>
							</select>
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