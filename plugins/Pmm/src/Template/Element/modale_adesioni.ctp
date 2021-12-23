<?php

use Cake\Core\Configure;

?>

<script type="text/javascript">

     $(document).on('reveal:close','modale-adesioni',function(){
          // resetto il form 
          $('form#form-adesione')[0].reset();
          // Cancello il nome del cliente
          $('span#user-name').html("");
     });

</script>

<div id="modale-adesioni" class="reveal-modal">
     <legend>Programmazione adesione <span id="user-name"></span></legend>
     <p>
     	<form id="form-adesione">
     		<input type="hidden" class="not-reset" name="contratto_id" />
               <div class="row">
                    <?= $this->element('select_pdr_adesione',['id' => 'edit-pdr']) ?>
                    <?= $this->element('input_data_adesione',['id' => 'edit-date']) ?>
                    <?= $this->element('select_stato_adesione',['id' => 'edit-status']) ?>
                    <?= $this->element('textarea_note_adesione',['id' => 'edit-note']) ?>
                    <div class="col-md-12 text-right">
                         <input type="reset" id="reset-adesione" value="Annulla" class="btn btn-default btn-flat"/>
                         <input type="submit" id="submit-adesione" value="Salva" class="btn btn-success btn-flat"/>
                    </div>
               </div>


     	</form>
     </p>
     <a class="close-reveal-modal">&#215;</a>
</div>