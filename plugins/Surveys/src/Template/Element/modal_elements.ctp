<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Modal Elements  (https://www.companee.it)
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

<div class="modal fade modal-elements" id="modalElements" ref="modalElements" tabindex="-1" role="dialog" aria-labelledby="modalElementsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<input hidden id="clicked_index" value="" />
			<input hidden id="section_label" value="" />
			<div class="modal-header">
				<b>Elementi</b>
				<button type="button" class="close" style="padding: 10px 15px;" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<input hidden id="clicked_index" value="" />
				<input hidden id="section_label" value="" />

				<a v-for="(element, index) in elements" class="btn btn-app btn-element" @click="addQuestion({'question':element, 'index': index})" v-html="element.icon+'<br>'+element.label"> </a>
			</div>
		</div>
	</div>
</div>