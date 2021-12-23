<?php $this->Html->css('Leads.leads', ['block' => 'css']); ?>
<?php $this->Html->script('Leads.leads', ['block' => 'script']); ?>

<div class="modal fade" id="modalEnsemble" tabindex="-1" role="dialog" aria-labelledby="modalEnsembleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Ensemble</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEnsemble" class="form-horizontal">
            <input hidden name="id" id="idEnsemble" value="" />
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label required" for="inputName">Nome</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" id="inputName" class="form-control required">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label" for="inputDescription">Descrizione</label>
                    <div class="col-sm-9">
                        <textarea type="text" name="description" id="inputDescription" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-3 control-label" for="inputActive">Attivo</label>
                    <div class="col-sm-9">
                        <input type="checkbox" name="active" id="inputActive" class="input-checkbox">
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
        <button type="button" class="btn btn-primary" id="saveEnsemble">Salva</button>
      </div>
    </div>
  </div>
</div>