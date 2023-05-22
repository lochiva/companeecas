<?php
/** 
* Companee :  modale_background   (https://www.companee.it)
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
?>
<div class="modal fade" id="modalBackground" tabindex="-1" role="dialog" aria-labelledby="modalbackgroundLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Aggiungi sfondo</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="backgroundUpload">
                    <div class="form-group">
                        <label for="backgroundImage">Immagine di sfondo</label>
                        <input type="file" name="background_image" id="backgroundImage" /><br />
                        <span style="color:red;">(dimensioni minime dell'immagine: 1440 x 900)</span>
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button disabled="disabled" type="button" class="btn btn-primary add-background" title="Selezionare un'immagine per poter salvare">Salva</button>
            </div>
        </div>
    </div>
</div>