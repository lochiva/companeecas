<?php
use Cake\Routing\Router;
?>

<div class="modal fade" id="myModalNewOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generazione Nuovo Anno</h4>
            </div>

            <div class="modal-body row">
                <p>
                    Per questa azienda è stata rilevata una o più configurazioni degli anni precedenti, se si desidera clonarne una di esse per l'anno richiesto, selezionare la data da clonare e premere genera. Altrimenti cliccare su annull aper procedere in manuale.
                </p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-warning btn-flat" id="salvaNuovoEvento" >Genera</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->