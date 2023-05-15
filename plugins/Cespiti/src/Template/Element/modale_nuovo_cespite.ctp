<?php
/**
* Cespiti is a plugin for manage attachment
*
* Companee :    Modale Nuovo Cespite (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
echo $this->Html->script('Cespiti.modale_nuovo_cespite'); ?>

<div class="modal fade" id="myModalCespite" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo Cespite</h4>
            </div>
            <div class="modal-body">
				<form class="form-horizontal">
					<div class="box-body">
						<input type="hidden" name="id_cespite" id="idCespite" >
						<div class="row">
							<div class="col-sm-4 col-sm-offset-2">
								<label class="control-label required" for="idAzienda">Fornitore</label>
								<select name="id_azienda" id="idAzienda" class="form-control select2 required" ></select>
							</div>
	            			<div class="col-sm-4">
								<label class="control-label required" for="idFatturaPassiva">Fattura</label><br />
								<select name="id_fattura_passiva" id="idFatturaPassiva" class="form-control select2 required" ></select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4 col-sm-offset-2">
								<label class="control-label required" for="num">Numero commercialista</label><br />
								<input type="text" name="num" id="num" class="form-control required" >
							</div>
							<div class="col-sm-2">
								<label class="control-label required" for="stato">Stato</label><br />
								<select name="stato" id="stato" class="form-control required" >
									<option value="0" selected >Attivo</option>
									<option value="1">Dismesso</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<label class="control-label required" for="descrizione">Descrizione</label><br />
								<input type="text" name="descrizione" id="descrizione" class="form-control required" >
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<label class="control-label" for="note">Note</label><br />
								<textarea name="note" id="note" rows="7" class="form-control" ></textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="salvaCespite" >Salva</button>
            </div>
		</div>
	</div>
</div>
