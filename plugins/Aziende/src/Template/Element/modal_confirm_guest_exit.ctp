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

					<div class="form-group"> 
						<div class="col-sm-12">
							<label>Documenti</label><br>
							<span v-if="!decreti" style="display:inline-block;" data-toggle="tooltip" data-placement="top" v-bind:title="exitData.type.modello_decreto < 1 ? 'Questa tipologia di uscita non prevede la generazione di un decreto' : '' ">
								<button  type="button" class="btn btn-primary exit-survey" @click="createInterview('decreto')" v-bind:disabled="exitData.type.modello_decreto < 1">
									Genera decreto
								</button>
							</span>
							<a v-else type="button" class="btn btn-primary" v-bind:href="decreti_url" target="_blank" data-toggle="tooltip" data-placement="top" title="Visualizzare, compilare e scaricare il decreto">
								Visualizza decreto
							</a>
							<div v-if="!notifiche" style="display:inline-block;" data-toggle="tooltip" data-placement="top" v-bind:title="exitData.type.modello_notifica < 1 ? 'Questa tipologia di uscita non prevede la generazione di una notifica' : '' ">
								<button type="button" class="btn btn-primary exit-survey" @click="createInterview('notifica')" v-bind:disabled="exitData.type.modello_notifica < 1">
									Genera notifica
								</button>
							</div>
							<a v-else type="button" class="btn btn-primary" v-bind:href="notifiche_url" target="_blank" data-toggle="tooltip" data-placement="top" title="Visualizzare, compilare e scaricare la notifica">
								Visualizza notifica
							</a>
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