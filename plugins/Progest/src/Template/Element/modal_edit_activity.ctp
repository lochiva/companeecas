<script>
	$(document).ready(function(){
		$('.color-picker').colorpicker();

		$('.editActivityButton').on('click', function(){

			var name = $(this).attr('data-name');
			var order = $(this).attr('data-order');
			var id = $(this).attr('data-activityid');
			var note = $(this).attr('data-note');

			if(note == 1){
				$('#editNoteNo').prop("checked", false);
				$('#editNoteSi').prop("checked", true);
			}else{
				$('#editNoteSi').prop("checked", false);
				$('#editNoteNo').prop("checked", true);
			}

			$('#editName').val(name);
			$('#editOrder').val(order);
			$('#editActivityId').val(id);

		});
	});
</script>

<style>
	h3{padding-left: 10px}
	.inputActivity{padding: 10px;}
	.buttonActivity{float: right; margin-bottom: 10px; margin-right: 10px;}
</style>
<div class="modal fade" id="myModalEditActivity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<div class="box-body">
				<h3>Modifica attività</h3>
				<form id="editActivityForm" method="POST" action="<?= $this->Url->build(['controller' => 'Activities', 'action' => 'edit', $id_service]) ?>">
					<input id="editActivityId" name="id_activity" value="" hidden="hidden"  />
					<div class="inputActivity col-md-4">
						<label for="inputName">Nome</label><br />
						<input type="text" name="name" class="form-control" id="editName" value=""/>
					</div>
					<div class="inputActivity col-md-4">
						<label for="inputOrder">Ordinamento</label><br />
						<input type="number" name="order_value" class="form-control" id="editOrder" value=""/>
					</div>
					<div class="inputActivity col-md-4">
						<label for="inputNote">Note</label><br />
						<input type="radio" name="noteSi" value="si" id="editNoteSi" checked=""/> Sì
		  				<input type="radio" name="noteNo" value="no" id="editNoteNo" checked=""/> No
					</div>
					<div class="buttonActivity">
						<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
						<input id="saveAddActivity" type="submit" class="btn btn-primary"  value="Salva"/>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
