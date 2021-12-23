<div class="modal fade modal-elements" id="modalElements" ref="modalElements" tabindex="-1" role="dialog" aria-labelledby="modalElementsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<input hidden id="clicked_index" value="" />
			<input hidden id="section_label" value="" />
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a class="click_tab_1" href="#tab_questions" data-toggle="tab"><b>Domande</b></a></li>
					<li><a class="click_tab_2" href="#tab_elements" data-toggle="tab"><b>Elementi generici</b></a></li>
					<li class="pull-right"><button type="button" class="close" style="padding: 10px 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="tab_questions">
						<div class="box-body">
							<a v-for="(question) in questions" class="btn btn-app btn-element" @click="addQuestion({'question':question})" v-html="question.icon+'<br>'+question.label"> </a>
						</div>
					</div>

					<div class="tab-pane" id="tab_elements">
						<div class="box-body">
							<a v-for="(element, index) in elements" :disabled="element.type == 'standard_text' && standardTexts.length == 0" class="btn btn-app btn-element" @click="addQuestion({'question':element, 'index': index})" v-html="element.icon+'<br>'+element.label"> </a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>