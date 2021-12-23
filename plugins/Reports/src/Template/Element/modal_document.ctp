<?php $this->Html->css('Reports.modal_document', ['block' => 'css']); ?>
<?php $this->Html->script('Reports.modal_document', ['block' => 'script']); ?>

<div class="modal fade" id="modalDocument" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Aggiungi documento</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="formDocument">
                    <input type="hidden" name="report_id" v-model="reportId" />
                    <div id="dropZone">
                        Trascina il file qui
                        <div id="clickZone">
                            oppure clicca qui
                            <input type="file" name="documents[]" id="inputDocument" multiple="multiple"/>
                        </div>
                    </div>
                    <div id="displayUploadedDocuments"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="saveDocument" >Salva</button>
            </div>
        </div>
    </div>
</div>