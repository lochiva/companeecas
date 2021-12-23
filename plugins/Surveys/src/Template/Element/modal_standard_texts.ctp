<div class="modal fade modal-elements" id="modalStandardTexts" ref="modalStandardTexts" tabindex="-1" role="dialog" aria-labelledby="modalStandardTextsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<b>Seleziona testo standard</b>
				<button type="button" class="close" style="padding: 10px 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<input hidden id="element_index" value="" />
				<div v-for="(text, index) in standardTexts">
					<input type="radio" name="standard_text" :value="index" v-model="selectedStandardText"/>
					<label>{{text.name}}</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        		<button type="button" class="btn btn-primary" @click="addElementStandardText">Carica</button>
			</div>
		</div>
	</div>
</div>