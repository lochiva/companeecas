<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Modal Data Sheet Options  (https://www.companee.it)
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

<div class="modal fade modal-elements" id="modalDataSheetOptions" ref="modalDataSheetOptions" tabindex="-1" role="dialog" aria-labelledby="modalDataSheetOptionsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<b>Inserisci scheda tecnica</b>
				<button type="button" class="close" style="padding: 10px 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<input hidden id="element_index" value="" />
					<div class="form-group">
						<label class="col-sm-12" for="selectDataSheet">Seleziona scheda tecnica</label>
						<div class="col-sm-12">
							<v-select class="fomr-control" id="selectDataSheet" :clearable="false" :options="searchedDataSheets" 
								v-model="dataSheetOptions.data_sheet" @search:focus="searchDataSheet" @search="searchDataSheet" 
								placeholder="Seleziona una scheda tecnica - digita per filtrare" ref="selectDataSheet">
								<template #no-options="{ search, searching }">
									<template v-if="searching">
										Nessuna scheda tecnica trovata per <em>{{ search }}</em>.
									</template>
									<em style="opacity: 0.5;" v-else>Inizia a scrivere per cercare una scheda tecnica.</em>
								</template>
							</v-select>
						</div>
					</div>
					<div class="form-group margintop30">
						<label class="col-sm-12">Seleziona criterio di visualizzazione</label>
						<div class="col-sm-12">
							<input type="radio" name="visibility_by_component" :value="false" v-model="dataSheetOptions.visibility_by_component" @change="changeDataSheetVisibilityByComponent()"> Sempre presente
						</div>
						<div class="col-sm-12 margintop10">
							<input type="radio" name="visibility_by_component" :value="true" v-model="dataSheetOptions.visibility_by_component" @change="changeDataSheetVisibilityByComponent()"> Guidato dal componente
							<div class="col-sm-12">
								<div class="col-sm-12 margintop5">
									<select class="form-control" :disabled="dataSheetOptions.section.disabled" v-model="dataSheetOptions.section.value" 
										@change="updateBlocksList()">
										<option value="" disabled selected>Sezione</option>
										<option v-for="section in sectionsList" :value="{id: section.id, name: section.name}">{{section.name}}</option>
									</select>
								</div>
								<div class="col-sm-12 margintop5">
									<select class="form-control" :disabled="dataSheetOptions.block.disabled" v-model="dataSheetOptions.block.value" 
										@change="updateComponentsList()">
										<option value="" disabled selected>Blocco</option>
										<option v-for="block in blocksList" :value="{id: block.id, name: block.name}">{{block.name}}</option>
									</select>
								</div>
								<div class="col-sm-12 margintop5">
									<select class="form-control" :disabled="dataSheetOptions.component.disabled" v-model="dataSheetOptions.component.value">
										<option value="" disabled selected>Componente</option>
										<option v-for="component in componentsList" :value="{id: component.id, name: component.name}">{{component.name}}</option>
									</select>
								</div>
								<div class="col-sm-12 margintop5">
									<button :disabled="!dataSheetOptions.visibility_by_component" type="button" class="btn btn-primary" @click="addComponentToDataSheet()">Aggiungi</button>
								</div>
								<div class="col-sm-12 margintop10 components-list-container">
									<table class="table table-hover">
										<tr v-for="(component, index) in dataSheetOptions.components" class="component-list-row">
											<td>{{component.text}}</td>
											<td>
												<button type="button" class="btn btn-xs btn-danger pull-right" @click="removeComponentFromDataSheet(index)">
													<i class="fa fa-trash"></i>
												</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        		<button type="button" class="btn btn-primary" @click="addElementDataSheet()">Inserisci</button>
			</div>
		</div>
	</div>
</div>