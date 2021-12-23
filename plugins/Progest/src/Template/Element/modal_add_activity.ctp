<style>
	h3{padding-left: 10px}
	.inputActivity{padding: 10px;}
	.buttonActivity{float: right; margin-bottom: 10px; margin-right: 10px;}
</style>
<div class="modal fade" id="myModalAddActivity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<div class="box-body">
				<h3>Aggiungi attività</h3>
				<form name="addActivityForm" method="POST" action="<?= $this->Url->build(['controller' => 'Activities', 'action' => 'add', $id_service]) ?>">
					<div class="inputActivity col-md-4">
						<label for="inputName">Nome</label><br />
						<input type="text" name="name" class="form-control"/>
					</div>
					<div class="inputActivity col-md-4">
						<label for="inputOrder">Ordinamento</label><br />
						<input type="number" name="order_value" class="form-control"/>
					</div>
					<div class="inputActivity col-md-4">
						<label for="inputNote">Note</label><br />
						<input type="radio" name="noteSi" value="si"/> Sì
		  				<input type="radio" name="noteNo" value="no"/> No
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
