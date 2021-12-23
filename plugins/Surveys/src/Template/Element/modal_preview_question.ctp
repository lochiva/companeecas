<?php
use Cake\Routing\Router;
?>

<div class="modal fade modal-questions" id="modalPreviewQuestion" ref="modalPreviewQuestion" tabindex="-1" role="dialog" aria-labelledby="modalPreviewQuestionLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title title-inline">Anteprima domanda</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>			
			<div class="box-body">

				 <!-- TESTO LIBERO -->
				 <div v-if="questionToPreview.type == 'free_text'" v-html="questionToPreview.value" class="answer-div" ></div>

				<!-- IMMAGINE -->
				<div v-if="questionToPreview.type == 'image' && questionToPreview.path != ''" class="answer-div" >
					<img :src="'<?=Router::url('/surveys/ws/viewImage/');?>'+questionToPreview.path" class="element-image" >
					<p v-html="questionToPreview.caption"></p>
				</div>

				<!-- RISPOSTA BREVE -->
				<div v-if="questionToPreview.type == 'short_answer' && questionToPreview.question != ''" class="answer-div">
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<input type="text" class="form-control" v-model="questionToPreview.answer" />
				</div>

				<!-- RISPOSTA APERTA -->
				<div v-if="questionToPreview.type == 'free_answer' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<textarea class="textarea-answer" v-model="questionToPreview.answer"></textarea>
				</div>

				<!-- DATA -->
				<div v-if="questionToPreview.type == 'date' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<datepicker :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" v-model="questionToPreview.answer"></datepicker>
				</div>

				<!-- NUMERO -->
				<div v-if="questionToPreview.type == 'number' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<input type="number" class="form-control number-integer" v-model="questionToPreview.answer" />
				</div>

				<!-- RADIO SI/NO -->
				<div v-if="questionToPreview.type == 'yes_no' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<input type="radio" value="yes" v-model="questionToPreview.answer" :checked="questionToPreview.answer == 'yes'" /> Sì 
					<input type="radio" class="radio-no" value="no" v-model="questionToPreview.answer" :checked="questionToPreview.answer == 'no'" /> No
				</div>

				<!-- SCELTA SINGOLA -->
				<div v-if="questionToPreview.type == 'single_choice' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<table v-if="questionToPreview.view_mode == 'list'" class="table-question-choice">
						<tr v-for="(option, index) in questionToPreview.options"> 
							<td class="td-question-check">
								<input type="radio" :value="index" class="question-check-option" v-model="questionToPreview.answer.check" @change="emptyExtensionSingle(questionToPreview.answer.extensions)" />
								<label class="question-choice-label" v-html="option.text"></label>
							</td>
							<td>
								<input v-if="option.extended" :disabled="!(questionToPreview.answer.check === index && option.extended)" type="text" class="form-control input-extended-answer" v-model="questionToPreview.answer.extensions[index]" />
							</td>
						</tr>  
					</table>
					<select v-if="questionToPreview.view_mode == 'select'" class="form-control answer-select" v-model="questionToPreview.answer.check" @change="emptyExtensionSingle(questionToPreview.answer.extensions)">
						<option value=""></option>
						<option v-for="(option, index) in questionToPreview.options" :value="index" v-html="option.text"></option>
					</select>
					<input v-if="questionToPreview.view_mode == 'select'" v-show="typeof questionToPreview.options[questionToPreview.answer.check] != 'undefined' && questionToPreview.options[questionToPreview.answer.check].extended" type="text" class="form-control answer-select-extended" v-model="questionToPreview.answer.extensions[questionToPreview.answer.check]" />
				</div>

				<!-- SCELTA MULTIPLA -->
				<div v-if="questionToPreview.type == 'multiple_choice' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<table class="table-question-choice" :class="{'multiple-choice-scroll': questionToPreview.scroll}">
						<tr v-for="(option, index) in questionToPreview.options">
							<td class="td-question-check">
								<input type="checkbox" class="question-check-option" v-model="questionToPreview.answer[index].check" @change="emptyExtensionMultiple(questionToPreview.answer[index])" />
								<label v-html="option.text"></label>
							</td>
							<td>
								<input v-if="option.extended" :disabled="!questionToPreview.answer[index].check" type="text" class="form-control input-extended-answer" v-model="questionToPreview.answer[index].extended"/>    
							</td>
						</tr>
						<tr v-if="questionToPreview.other">
							<td class="td-question-check">
								<input type="checkbox" class="question-check-option" v-model="questionToPreview.other_answer_check" @change="emptyAnswer(questionToPreview)" :checked="questionToPreview.other_answer_check" />
								<label class="multiple-choice-other-label">Altro</label>
							</td>
							<td>
								<input :disabled="!questionToPreview.other_answer_check" type="text" class="form-control" v-model="questionToPreview.other_answer" />
							</td>
						</tr>
					</table>
				</div>

				<!-- TABELLA -->
				<div v-if="questionToPreview.type == 'table' && questionToPreview.question != ''" class="answer-div" >
					<div class="question-text">   
                        <span v-if="questionToPreview.required" class="question-required">*&nbsp;</span><p v-html="questionToPreview.question"></p>
						&nbsp;
						<a v-if="questionToPreview.tooltip != ''" class="question-tooltip" data-toggle="modal" data-target="#modalTooltipQuestion"><i class="fa fa-info-circle"></i></a>
						<span hidden class="text-question-tooltip" v-html="questionToPreview.tooltip"></span>
					</div>
					<table class="table table-bordered table-question-table">
						<thead>
							<tr>
								<th v-for="header in questionToPreview.headers" v-html="header"></th>
								<th class="question-table-actions-col">
									<button type="button" class="btn btn-info btn-xs" @click="addRowTable({'headers': questionToPreview.headers, 'answer': questionToPreview.answer})" title="Aggiungi riga">
										<i class="fa fa-plus"></i>
									</button>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(answer, index) in questionToPreview.answer"> 
								<td v-for="(a, i) in answer" >
									<input type="text" class="form-control" v-model="answer[i]" />
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-danger btn-xs" @click="removeRowTable({'answer': questionToPreview.answer, 'index': index})" title="Rimuovi riga">
										<i class="fa fa-trash"></i>
									</button>
								</td>
							</tr>
						</tbody> 
					</table>
				</div>

			</div>
		</div>
	</div>
</div>