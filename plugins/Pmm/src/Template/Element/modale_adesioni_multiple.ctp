<?php

use Cake\Core\Configure;

?>

<script type="text/javascript">

     $(document).on('reveal:close','#modale-adesioni-multiple',function(){
        $('form#form-adesioni-multiple')[0].reset();
        $('input.ids').remove();
     });

</script>

<div id="modale-adesioni-multiple" class="reveal-modal">
     <legend>Modifica adesioni multiple</legend>
     <p>Per abilitare i campi da modificare devi cliccare sul check di fianco al nome.</p><br />
     <p>
     	<form id="form-adesioni-multiple">
               <div class="row">
                    <?= $this->element('select_pdr_adesione',['id' => 'edit-pdr-multiple','disabled'=>'disabled']) ?>
                    <?= $this->element('input_data_adesione',['id' => 'edit-date-multiple','disabled'=>'disabled']) ?>
                    <?= $this->element('select_stato_adesione',['id' => 'edit-status-multiple','disabled'=>'disabled']) ?>
                    <div class="col-md-12 text-right">
                         <input type="reset" id="reset-adesioni-multiple" value="Annulla" class="btn btn-default btn-flat"/>
                         <input type="submit" id="submit-adesioni-multiple" value="Salva" class="btn btn-success btn-flat"/>
                    </div>
               </div>


     	</form>
     </p>
     <a class="close-reveal-modal">&#215;</a>
</div>
