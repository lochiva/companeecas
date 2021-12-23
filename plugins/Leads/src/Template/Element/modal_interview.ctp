<div class="modal fade" id="modalInterview" role="dialog" aria-labelledby="modalInterviewLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Intervista</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formInterview" class="form-horizontal">
            <input hidden name="id" id="idInterview" value="" />
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label required" for="inputAzienda">Azienda</label>
                    <div class="col-sm-8">
                        <select name="id_azienda" id="inputAzienda" class="select2 form-control required">
                          <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label required" for="inputContact">Contatto</label>
                    <div class="col-sm-8">
                        <select name="id_contatto" id="inputContact" class="select2 form-control required">
                          <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label required" for="inputEnsemble">Ensemble</label>
                    <div class="col-sm-8">
                        <select name="id_ensemble" id="inputEnsemble" class="select2 form-control required">
                          <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label required" for="inputName">Nome</label>
                    <div class="col-sm-8">
                        <input name="name" id="inputName" class="form-control required" value=""/>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        <button type="button" class="btn btn-primary" id="saveInterview">Salva</button>
      </div>
    </div>
  </div>
</div>