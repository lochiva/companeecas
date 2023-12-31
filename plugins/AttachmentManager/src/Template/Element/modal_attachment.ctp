<?php
/**
* Attachment manager is a plugin for manage attachment
*
* Companee :    Modal Attachment   (https://www.companee.it)
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
<?php $this->Html->css('AttachmentManager.modal_attachment', ['block' => 'css']); ?>
<?php $this->Html->script('AttachmentManager.modal_attachment', ['block' => 'script']); ?>

<div id="disabled_background_attachment"></div>
<div class="overlay-attachment" id="overlay_attachment" >
    <div id="attachment-loader" hidden><i class="fa fa-spinner fa-pulse fa-3x fa-fw"  ></i></div>
    <div class="overlay-attachment-header">
        <button class="close close-overlay-attachment" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="overlay-attachment-title">Aggiungi allegato</h4>
    </div>
    <div class="overlay-attachment-body">
        <form enctype="multipart/form-data" id="formAttachment">
            <input hidden name="context" id="contextAttachment">
            <input hidden name="id_item" id="idItemAttachment">
            <div id="dropZone">
                Trascina il file qui
                <div id="clickZone">
                    oppure clicca qui
                    <input type="file" name="attachments[]" id="inputAttachment" multiple="multiple"/>
                </div>
            </div>
        </form>
        <div id="displayUploadedAttachments"></div>
        <br />
        <h4 class="text-center"><b>Allegati caricati</b></h4>
        <div id="displaySavedAttachments"></div>
    </div>
    <div class="overlay-attachment-footer">
        <button type="button" class="btn btn-default close-overlay-attachment" >Chiudi</button>
        <button type="button" class="btn btn-primary" id="saveAttachment" >Salva</button>
    </div>
</div>