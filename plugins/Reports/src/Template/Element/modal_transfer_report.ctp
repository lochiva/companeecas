<div class="modal fade" id="modalTransferReport" tabindex="-1" role="dialog" aria-labelledby="Modale trasferimento caso" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-inline">Trasferimento caso</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTransferReport" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="required" for="transferMotivation">Motivazione del trasferimento</label>
                            <textarea class="form-control textarea-answer" name="motivation" id="transferMotivation" v-model="transferMotivation"></textarea>
                        </div>
                    </div>
                </form>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" @click="transferReport()">Trasferisci caso</button>
            </div>
        </div>
    </div>
</div>