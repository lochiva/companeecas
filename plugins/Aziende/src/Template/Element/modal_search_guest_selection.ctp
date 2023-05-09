<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    modal search guest selection  (https://www.companee.it)
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

<div class="modal fade" id="modalGuestSelection" tabindex="-1" role="dialog" aria-labelledby="modalGuestSelectionLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title title-inline">Seleziona ospite da visualizzare</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Questo ospite è presente in più strutture. Selezionare quale si desidera visualizzare.</p>
				<table class="table table-bordered table-hover table-striped" id="tableGuestsChoices">
					<thead>
						<th>Ente</th>
						<th>Struttura</th>
						<th>Ingresso</th>
						<th>Uscita</th>
						<th>Stato</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>