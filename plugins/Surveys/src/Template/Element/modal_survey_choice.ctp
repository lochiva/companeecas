<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Modal Survey Choice  (https://www.companee.it)
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

<div class="modal fade" id="modalSurveyChoice" tabindex="-1" role="dialog" aria-labelledby="modalSurveyChoiceLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title title-inline">Scegli questionario</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Questa sede ha a disposizione più di un questionario. Scegliere quale si desidera compilare per procedere.</p>
				<div id="surveyChoices"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
				<a href="" class="btn btn-primary" id="fillInterview">Compila</a>
			</div>
		</div>
	</div>
</div>