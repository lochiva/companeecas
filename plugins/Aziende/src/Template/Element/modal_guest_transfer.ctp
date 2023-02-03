<div class="modal fade" id="modalGuestTransfer" ref="modalGuestTransfer" role="dialog" aria-labelledby="modalGuestTransferLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Procedura di trasferimento per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div v-if="role != 'admin'">
					<p class="exit-trasnfer-warning">NOTA: Non usare questo strumento per trasferire un ospite ad un altro ente. Il trasferimento viene avviato da Prefettura.</p>
				</div>
				<form class="form-horizontal" id="formGuestTransfer">
					<div v-if="role == 'admin' || role == 'area_iv'" class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': transferProcedureData.azienda.hasError}">
							<label class="required" for="transferAzienda">Ente</label>
							<v-select class="fomr-control" id="transferAzienda" :clearable="false" :options="transferAziende" 
								:value="transferProcedureData.azienda.value" @search:focus="searchTransferAziende" @search="searchTransferAziende" 
								@input="setTransferAzienda" placeholder="Seleziona un ente - digita per filtrare" ref="selectTransferAzienda">
								<template #no-options="{ search, searching }">
									<template v-if="searching">
										Nessun ente trovato per <em>{{ search }}</em>.
									</template>
									<em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare un ente.</em>
								</template>
							</v-select>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': transferProcedureData.sede.hasError}">
							<label class="required" for="transferSede">Struttura</label>
							<v-select :disabled="transferProcedureData.azienda.value == ''" class="fomr-control" id="transferSede" :clearable="false" 
								:options="transferSedi" :value="transferProcedureData.sede.value" @search:focus="searchTransferSedi" @search="searchTransferSedi" 
								@input="setTransferSede" placeholder="Seleziona una struttura - digita per filtrare" ref="selectTransferSede">
								<template #no-options="{ search, searching }">
									<template v-if="searching">
										Nessuna struttura trovata per <em>{{ search }}</em>.
									</template>
									<em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una struttura.</em>
								</template>
							</v-select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12" :class="{'has-error': transferProcedureData.check_out_date.hasError}">
							<label :class="{'required': transferProcedureData.check_out_date.required}" for="checkOutDate"><?= __('Check-out') ?></label>
							<datepicker :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" 
								typeable="true" id="checkOutDate" v-model="transferProcedureData.check_out_date.value">
							</datepicker>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12">
							<label for="transferNote">Note</label>
							<textarea v-model="transferProcedureData.note.value" id="transferNote" class="form-control trasnfer-note"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-violet" @click="executeTransferProcedure()">Esegui trasferimento ospite</button>
			</div>
		</div>
	</div>
</div>