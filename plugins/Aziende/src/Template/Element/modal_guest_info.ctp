<div class="modal fade" id="modalGuestInfo" ref="modalGuestInfo" role="dialog" aria-labelledby="modalGuestInfoLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informazioni ospite {{infoGuest.name}} {{infoGuest.surname}}</h4>
            </div>
			<div class="modal-body container-info-guest">
				<div class="col-sm-12">
					<label>Check-In</label>: {{infoGuest.check_in_date}}
				</div>
				<div class="col-sm-12">
					<label>CUI</label>: {{infoGuest.cui}}
				</div>
				<div class="col-sm-12">
					<label>ID Vestantet</label>: {{infoGuest.vestanet_id}}
				</div>
				<div class="col-sm-12">
					<label>Data di nascita</label>: {{infoGuest.birthdate}}
				</div>
				<div class="col-sm-12">
					<label>Nazionalità</label>: {{infoGuest.country_birth_name}}
				</div>
				<div class="col-sm-12">
					<label>Sesso</label>: {{infoGuest.sex}}
				</div>
				<div class="col-sm-12">
					<label>Minore</label>: {{infoGuest.minor ? 'Sì' : 'No'}}
				</div>
			</div>
		</div>
	</div>
</div>