<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modal authorize guest request exit  (https://www.companee.it)
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
							<span>{{requestExitData.type.name}}</span>
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
						<div class="col-sm-12">
							<label>Documenti</label><br>
							<span v-if="!decreti" style="display:inline-block;" data-toggle="tooltip" data-placement="top" v-bind:title="requestExitData.type.modello_decreto < 1 ? 'Questa tipologia di uscita non prevede la generazione di un decreto' : '' ">
								<button  type="button" class="btn btn-primary exit-survey" @click="createInterview('decreto')" v-bind:disabled="requestExitData.type.modello_decreto < 1">
									Genera decreto
								</button>
							</span>
							<a v-else type="button" class="btn btn-primary" v-bind:href="decreti_url" target="_blank" data-toggle="tooltip" data-placement="top" title="Visualizzare, compilare e scaricare il decreto">
								Visualizza decreto
							</a>
							<div v-if="!notifiche" style="display:inline-block;" data-toggle="tooltip" data-placement="top" v-bind:title="requestExitData.type.modello_notifica < 1 ? 'Questa tipologia di uscita non prevede la generazione di una notifica' : '' ">
								<button type="button" class="btn btn-primary exit-survey" @click="createInterview('notifica')" v-bind:disabled="requestExitData.type.modello_notifica < 1">
									Genera notifica
								</button>
							</div>
							<a v-else type="button" class="btn btn-primary" v-bind:href="notifiche_url" target="_blank" data-toggle="tooltip" data-placement="top" title="Visualizzare, compilare e scaricare la notifica">
								Visualizza notifica
							</a>
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': authorizeRequestExitProcedureData.file.hasError}">
							<label :class="{'required': authorizeRequestExitProcedureData.file.required}" for="exitFile">Documento di revoca</label>
							<input type="file" value="authorizeRequestExitProcedureData.file.value" @input="authorizeRequestExitProcedureData.file.value = $event.target.files[0]" id="authorizeRequestExitFile" class="form-control authorize-request-exit-file">
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-12" :class="{'has-error': authorizeRequestExitProcedureData.note.hasError}">
							<label :class="{'required': authorizeRequestExitProcedureData.note.required}" for="exitNote">Note per l'ente</label>
							<textarea v-model="authorizeRequestExitProcedureData.note.value" id="authorizeRequestExitNote" class="form-control authorize-request-exit-note"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<button type="button" class="btn btn-gold" @click="authorizeRequestExitProcedure()">Autorizza richiesta uscita ospite</button>
			</div>
		</div>
	</div>
</div>