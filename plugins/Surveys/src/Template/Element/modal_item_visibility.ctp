<div class="modal fade modal-elements" id="modalItemVisibility" ref="modalItemVisibility" tabindex="-1" role="dialog" aria-labelledby="modalItemVisibilityLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<b>Configurazione visiblità</b>
				<button type="button" class="close" style="padding: 10px 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-12">Seleziona criterio di visualizzazione</label>
						<div class="col-sm-12">
							<input type="radio" name="visibility_by_component" :value="false" v-model="itemVisibilityOptions.visibility_by_component" @change="changeItemVisibilityByComponent()"> Sempre presente
						</div>
						<div class="col-sm-12 margintop10">
							<input type="radio" name="visibility_by_component" :value="true" v-model="itemVisibilityOptions.visibility_by_component" @change="changeItemVisibilityByComponent()"> Guidata dal componente
							<div class="col-sm-12">
								<div class="col-sm-12 margintop5">
									<select class="form-control" :disabled="itemVisibilityOptions.section.disabled" v-model="itemVisibilityOptions.section.value" 
										@change="updateBlocksListForVisibility()">
										<option value="" disabled selected>Sezione</option>
										<option v-for="section in sectionsList" :value="{id: section.id, name: section.name}">{{section.name}}</option>
									</select>
								</div>
								<div class="col-sm-12 margintop5">
									<select class="form-control" :disabled="itemVisibilityOptions.block.disabled" v-model="itemVisibilityOptions.block.value" 
										@change="updateComponentsListForVisibility()">
										<option value="" disabled selected>Blocco</option>
										<option v-for="block in blocksList" :value="{id: block.id, name: block.name}">{{block.name}}</option>
									</select>
								</div>
								<div class="col-sm-12 margintop5">
									<select class="form-control" :disabled="itemVisibilityOptions.component.disabled" v-model="itemVisibilityOptions.component.value">
										<option value="" disabled selected>Componente</option>
										<option v-for="component in componentsList" :value="{id: component.id, name: component.name}">{{component.name}}</option>
									</select>
								</div>
								<div class="col-sm-12 margintop5">
									<button :disabled="!itemVisibilityOptions.visibility_by_component" type="button" class="btn btn-primary" @click="addComponentToItemVisibility()">Aggiungi</button>
								</div>
								<div class="col-sm-12 margintop10 components-list-container">
									<table class="table table-hover">
										<tr v-for="(component, index) in itemVisibilityOptions.components" class="component-list-row">
											<td>{{component.text}}</td>
											<td>
												<button type="button" class="btn btn-xs btn-danger pull-right" @click="removeComponentFromItemVisibility(index)">
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
        		<button type="button" class="btn btn-primary" @click="setItemVisibility()">Imposta</button>
			</div>
		</div>
	</div>
</div>