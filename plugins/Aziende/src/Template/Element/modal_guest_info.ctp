<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modal guest info  (https://www.companee.it)
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