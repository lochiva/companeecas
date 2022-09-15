<div class="modal fade" id="authorizeRequestExitFamily" ref="authorizeRequestExitFamily" role="dialog" aria-labelledby="authorizeRequestExitFamilyLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-confirm">Si desidera autorizzare la richiesta di uscita per tutti gli ospiti associati alla famiglia? Cliccando SI verrà autorizzata la richiesta per tutti i membri. Cliccando NO verrà autorizzata la richiesta per il solo ospite</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary confirm-no" @click="authorizeRequestExitGuest(0)" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary confirm-si" @click="authorizeRequestExitGuest(1)" data-dismiss="modal">Sì</button>
      </div>
    </div>
  </div>
</div>