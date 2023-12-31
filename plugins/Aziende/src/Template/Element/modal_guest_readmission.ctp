<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Modal Guest Readmission  (https://www.companee.it)
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

<div class="modal fade" id="modalGuestReadmission" ref="modalGuestReadmission" role="dialog" aria-labelledby="modalGuestReadmissionLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title displayinline">Procedura di riammissione per ospite {{guestData.cui.value}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="formGuestReadmission">
					<div v-if="role == 'admin'" class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': readmissionProcedureData.azienda.hasError}">
							<label class="required" for="readmissionAzienda">Ente</label>
							<v-select class="fomr-control" id="readmissionAzienda" :clearable="false" :options="readmissionAziende" 
								:value="readmissionProcedureData.azienda.value" @search:focus="searchReadmissionAziende" @search="searchReadmissionAziende" 
								@input="setReadmissionAzienda" placeholder="Seleziona un ente - digita per filtrare" ref="selectReadmissionAzienda">
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
						<div class="col-sm-12" :class="{'has-error': readmissionProcedureData.sede.hasError}">
							<label class="required" for="readmissionSede">Struttura</label>
							<v-select :disabled="readmissionProcedureData.azienda.value == ''" class="fomr-control" id="readmissionSede" :clearable="false" 
								:options="readmissionSedi" :value="readmissionProcedureData.sede.value" @search:focus="searchReadmissionSedi" @search="searchReadmissionSedi" 
								@input="setReadmissionSede" placeholder="Seleziona una struttura - digita per filtrare" ref="selectReadmissionSede">
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
						<div class="col-sm-12">
							<label for="readmissionNote">Note</label>
							<textarea v-model="readmissionProcedureData.note.value" id="readmissionNote" class="form-control readmission-note"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-success" @click="executeReadmissionProcedure()">Esegui riammissione ospite</button>
			</div>
		</div>
	</div>
</div>