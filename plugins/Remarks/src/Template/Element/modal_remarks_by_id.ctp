<?php $this->Html->css('Remarks.remarks', ['block' => 'css']); ?>
<?php $this->Html->script('Remarks.remarks', ['block' => 'script']); ?>
<?php $this->Html->script('Remarks.tinymce/jquery.tinymce.min', ['block' => 'script']);?>
<?php $this->Html->script('Remarks.tinymce', ['block' => 'script']); ?>

<div id="disabled_background"></div>
<div class="overlay-remarks" id="overlay_remarks" >
    <div id="remarks-loader" hidden><i class="fa fa-spinner fa-pulse fa-3x fa-fw"  ></i></div>
    <div class="overlay-remarks-header">
        <button class="close close-overlay-remarks" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="overlay-remarks-title">Note</h4>
    </div>
    <div class="overlay-remarks-body">
        <span hidden id="reference_remarks"></span>
        <span hidden id="reference_id_remarks"></span>
        <span hidden id="reference_remarks"></span>
        <span hidden id="label_notification"></span>
        <input type="checkbox" id="show_deleted_remarks" > Mostra anche note cancellate
        <div class="row old-remarks-row">
            <div class="col-md-12">
                <div id="old_remarks"></div>
            </div>
        </div>
        <div class="row new-remark-row">
            <div class="col-md-12" >
                <textarea id="new_remark" class="editor-html"></textarea>
            </div>
        </div>
        <div class="row buttons-row">
            <section class='col-md-3 rating-widget'>
                <div class='rating-stars'>
                    <ul id='stars'>
                        <li class='star' title='Scarso' data-value='1'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Mediocre' data-value='2'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Buono' data-value='3'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Ottimo' data-value='4'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Eccellente' data-value='5'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                    </ul>
                </div>
            </section>
            <div class="col-md-2 remark-visibility">
                <input type="radio" id="visibility_public" class="radio-visibility" name="visibility" value="0" /> <i title="Pubblica" class="fa fa-globe visibility-icon"></i>
                <input type="radio" id="visibility_private" class="radio-visibility" name="visibility" value="1" /> <i title="Privata" class="fa fa-lock visibility-icon"></i>
            </div>
            <div class="col-md-4 remark-attachment">
                <?= $this->Form->create(null, array( 'enctype' => 'multipart/form-data', 'id'=>'remark_attachment_upload')); ?>
                    <input type="file" id="remark_attachment" class="fix-inputfile-firefox" name="remark_attachment" />
                <?= $this->Form->end(); ?>
                <div id="div_check_attachment"></div>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-default close-overlay-remarks" >Chiudi</button>
                <button type="button" class="btn btn-primary" id="save_remark" >Salva nota</button>
            </div>
        </div>
        <?= $this->Form->create(null, array( 'enctype' => 'multipart/form-data', 'style'=>'width:0px;height:0px;overflow:hidden', 'id'=>'tinymce_upload_form')) ?>
            <input name="uploadedfile" type="file" id="tinymce_upload" class="" >
        <?= $this->Form->end(); ?>
    </div>
</div>
