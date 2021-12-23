<div class="modal fade" id="modalReopenReport" tabindex="-1" role="dialog" aria-labelledby="Modale riapertura caso" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-inline">Riapertura caso</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formReopenReport" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="required" for="reopenMotivation">Motivazione della riapertura</label>
                            <textarea class="form-control textarea-answer" name="motivation" id="reopenMotivation" v-model="reopenMotivation"></textarea>
                        </div>
                    </div>
                </form>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" @click="reopenReport()">Riapri caso</button>
            </div>
        </div>
    </div>
</div>