<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Modale Notice   (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
?>
<script>
$(document).ready(function(){
    /*$("#myModalNotice").on('show.bs.modal', function () {
        $('#inputMessage').val('');
    });*/

    $("#sendNotice").click(function(){
        var message = $('#inputMessage').val();
        var id_dest = $('#id_dest').val();
        if(message != ''){
            $.ajax({
          	    url : pathServer + "Ws/sendNotice",
          	    type: "POST",
          	    dataType: "json",
                data: {message:message, id_dest:id_dest},
          	    success : function (data,stato) {

          	        if(data.response == "OK"){
                      $("#myModalNotice").modal('hide');
                      $('#inputMessage').val('');

          	        }else{
          	        	alert(data.msg);
          	        }

          	    },
          	    error : function (richiesta,stato,errori) {
          	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
          	    }
              });
          }else{
              alert("Devi inserire un messaggio!");
          }
    });
});
</script>
<div class="modal fade" id="myModalNotice" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Invia Notifica</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label required" for="inputNome">Messaggio</label>
                            <div class="col-sm-10">
                                <textarea name="message" id="inputMessage"  class="form-control required" rows="3" placeholder="Messaggio ..."></textarea>
                                <input type="hidden" name="id_dest" id="id_dest" value="<?=$user['id'] ?>" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="sendNotice" >Invia</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
