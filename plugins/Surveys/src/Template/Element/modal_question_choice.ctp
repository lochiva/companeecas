<div class="modal fade modal-questions" id="modalQuestions" ref="modalQuestions" tabindex="-1" role="dialog" aria-labelledby="modalQuestionsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="box-body">
				<input hidden id="clicked_index" value="" /> 
				<div v-if="surveyData.conditioningQuestions.length > 0">
					<p>Quale domanda condiziona la visibilità?</p>
					<div v-for="(question) in surveyData.conditioningQuestions" class="col-md-12" v-if="question.question_id !== currentQuestion.id"> 
						<input type="radio" v-model="connected_to" :value="question.question_id" :checked="connected_to == question.question_id" @change="show_on = ''"/> 
						<label v-html="question.question+' (sezione '+question.section+')'"></label>
					</div>
					<div v-for="(question) in surveyData.conditioningQuestions" v-show="connected_to == question.question_id" class="div-conditional-answer"> 
						<p>Quale risposta abilita?</p>
						<div v-if="question.type == 'yes_no'" class="col-sm-12">
							<input type="radio" v-model="show_on" value="yes" :checked="show_on == 'yes'" /> 
							<label>Sì</label> 
							<input type="radio" class="radio-no" v-model="show_on" value="no" :checked="show_on == 'no'" /> 
							<label>No</label>
						</div>
						<div v-if="question.type == 'single_choice'" class="col-sm-12">
							<div v-for="(option, index) in question.options"> 
								<input type="radio" v-model="show_on" :value="index" :checked="show_on == index" /> 
								<label>{{option.text}}</label> 
							</div>
						</div>
					</div>
					<div class="div-btn-condition">
						<button type="button" class="btn btn-primary pull-right" @click="setConditionedQuestion({'connected_to': connected_to, 'show_on': show_on})">Condiziona</button>
						<button type="button" class="btn btn-default pull-right btn-clear-condition" @click="clearConditionedQuestion()">Annulla</button>
					</div>
				</div>
				<div v-else class="text-center">
					<p>Nessuna domanda disponibile.</p>
					<div class="div-btn-condition">
						<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Annulla</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>