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
								id="checkOutDate" v-model="confirmExitProcedureData.check_out_date.value">
							</datepicker>
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