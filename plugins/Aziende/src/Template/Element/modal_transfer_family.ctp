<div class="modal fade" id="transferFamily" ref="transferFamily" role="dialog" aria-labelledby="transferFamily" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-confirm">Si desidera eseguire la procedura di trasferimento per tutti gli ospiti associati alla famiglia?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary confirm-no" @click="transferGuest(0)" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary confirm-si" @click="transferGuest(1)" data-dismiss="modal">Sì</button>
      </div>
    </div>
  </div>
</div>