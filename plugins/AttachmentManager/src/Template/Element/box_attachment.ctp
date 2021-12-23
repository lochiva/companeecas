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