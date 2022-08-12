<div class="modal fade" id="modalAuthorizeGuestRequestExit" ref="modalAuthorizeGuestRequestExit" role="dialog" aria-labelledby="modalAuthorizeGuestRequestExitLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Autorizza richiesta di uscita per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formAuthorizeGuestRequestExit">
					<div class="form-group"> 
						<div class="col-sm-12">
							<label>Motivazione</label><br>
							<span>{{requestExitData.type}}</span>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12">
							<button v-if="requestExitData.file" type="button" class="btn btn-primary" 
								@click="downloadExitDocument(requestExitData.file)">
								<i class="fa fa-download"></i> Scarica documento
							</button>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12">
							<label>Note dall'ente</label><br>
							<span>{{requestExitData.note}}</span>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': requestExitProcedureData.file.hasError}">
							<label :class="{'required': requestExitProcedureData.file.required}" for="exitFile">Documento revoca</label>
							<input type="file" value="requestExitProcedureData.file.value" @input="requestExitProcedureData.file.value = $event.target.files[0]" id="requestExitFile" class="form-control request-exit-file">
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': requestExitProcedureData.note.hasError}">
							<label :class="{'required': requestExitProcedureData.note.required}" for="exitNote">Note per l'ente</label>
							<textarea v-model="requestExitProcedureData.note.value" id="requestExitNote" class="form-control request-exit-note"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-gold">Autorizza richiesta uscita ospite</button>
			</div>
		</div>
	</div>
</div>