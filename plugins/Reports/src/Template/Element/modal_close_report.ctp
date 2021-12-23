<div class="modal fade" id="modalCloseReport" tabindex="-1" role="dialog" aria-labelledby="Modale chiusura caso" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title title-inline">Chiusura caso</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCloseReport" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="required" for="closeDate">Data chiusura</label>
                            <datepicker :language="datepickerItalian" format="dd/MM/yyyy" :monday-first="true" input-class="form-control" name="date" id="closeDate" v-model="close_report.date"></datepicker>
                        </div>
                        <div class="col-md-12">
                            <label class="required" for="closeMotivation">Motivazione della chiusura</label>
                            <textarea class="form-control textarea-answer" name="motivation" id="closeMotivation" v-model="close_report.motivation"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="required" for="closeOutcome">Esito del caso</label>
                            <select class="form-control select-with-user-text" name="outcome" id="closeOutcome" v-model="close_report.outcome">
                                <option value="">-- Seleziona --</option>
                                <?php foreach($closingOutcomes as $outcome){ ?>
                                    <option value="<?= $outcome['id'] ?>"><?= $outcome['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" @click="closeReport()">Chiudi caso</button>
            </div>
        </div>
    </div>
</div>