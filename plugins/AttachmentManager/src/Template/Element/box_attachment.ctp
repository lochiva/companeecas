<?php
/**
* Attachment manager is a plugin for manage attachment
*
* Companee :    Box Attachment   (https://www.companee.it)
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

<?php $this->Html->css('AttachmentManager.box_attachment', ['block' => 'css']); ?>
<?php $this->Html->script('AttachmentManager.box_attachment', ['block' => 'script']); ?>

<div id="boxAttachments" class="box box-brown">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file text-brown"></i> Allegati <span class="box-attachments-number"></span></h3>
            <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
            </div>
    </div>
    <div class="box-body attachments-list">
        <ul class="products-list product-list-in-box">               
        </ul>
    </div>
    <div class="box-footer text-right">
        <span hidden id="contextForAttachment"></span>
        <span hidden id="idItemForAttachment"></span>
        <?= $this->element('AttachmentManager.button_attachment', ['id' => 'button_attachment_box']); ?>
    </div>
</div>