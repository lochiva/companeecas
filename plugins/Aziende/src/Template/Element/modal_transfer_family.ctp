<div class="modal fade" id="transferFamily" ref="transferFamily" role="dialog" aria-labelledby="transferFamily" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-confirm">Attenzione! L'ospite è associato ad un nucleo familiare pertanto verrà eseguita la procedura di trasferimento per tutti gli ospiti associati alla famiglia.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary confirm-no" data-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-primary confirm-si" @click="transferGuest(1)" data-dismiss="modal">Prosegui</button>
      </div>
    </div>
  </div>
</div>