<?php $this->Html->css('Leads.leads', ['block' => 'css']); ?>
<?php $this->Html->script('Leads.leads', ['block' => 'script']); ?>

<div class="modal fade" id="modalQuestions" tabindex="-1" role="dialog" aria-labelledby="modalQuestionsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Gestione domande</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formQuestion" class="form-horizontal">
            <input hidden name="id_ensemble" id="idEnsemble" value="" />
            <input hidden name="id" id="idQuestion" value="" />
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-2 control-label required" for="inputName">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" id="inputName" maxlength="255" class="form-control required">
                    </div>
                </div>
            </div> 
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-2 control-label" for="inputInfo">Informazioni</label>
                    <div class="col-sm-10">
                        <textarea type="text" name="info" id="inputInfo" maxlength="255" class="form-control"></textarea>
                    </div>
                </div>
            </div> 
            <div class="form-group">
                <div class="input">
                    <label class="col-sm-2 control-label required" for="inputType">Tipo</label>
                    <div class="col-sm-6">
                        <select name="id_type" id="inputType" class="select2 form-control required">
                          <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="options-select" class="form-group" style="display: none;">
                <div class="input">
                    <label class="col-sm-2 control-label required" for="inputOptions">Scelte ( ; come separatore)</label>
                    <div class="col-sm-10">
                        <textarea name="options" id="inputOptions" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-default" id="cancelEditQuestion">Annulla</button>
                    <button type="button" class="btn btn-primary" id="saveQuestion">Aggiungi</button>
                </div>
            </div>
            <div class="clear-both"></div>
        </form>
        <hr class="body-separator" />
        <div id="questionsList">
          
        </div>
      </div>
    </div>
  </div>
</div>