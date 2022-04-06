<div class="modal fade" id="confirmExitFamily" ref="confirmExitFamily" role="dialog" aria-labelledby="confirmExitFamilyLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p v-if="exitProcedure.procedure == 'exit'" class="text-confirm">Si desidera eseguire la procedura di uscita per tutti gli ospiti associati alla famiglia?</p>
        <p v-if="exitProcedure.procedure == 'transfer'" class="text-confirm">Si desidera eseguire la procedura di trasferimento per tutti gli ospiti associati alla famiglia?</p>
      </div>
      <div class="modal-footer">
        <button v-if="exitProcedure.procedure == 'exit'" type="button" class="btn btn-secondary confirm-no" @click="exitGuest(false)" data-dismiss="modal">No</button>
        <button v-if="exitProcedure.procedure == 'exit'" type="button" class="btn btn-primary confirm-si" @click="exitGuest(true)" data-dismiss="modal">Sì</button>
        <button v-if="exitProcedure.procedure == 'transfer'" type="button" class="btn btn-secondary confirm-no" @click="exitProcedure.transfer_volume = 1" data-dismiss="modal">No</button>
        <button v-if="exitProcedure.procedure == 'transfer'" type="button" class="btn btn-primary confirm-si" @click="exitProcedure.transfer_volume = guestData.family.length + 1" data-dismiss="modal">Sì</button>
      </div>
    </div>
  </div>
</div>