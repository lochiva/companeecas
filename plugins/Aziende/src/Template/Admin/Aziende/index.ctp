<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Index  (https://www.companee.it)
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
use Cake\Core\Configure;

?>
<script>
$(document).ready(function(){
    $('#add-ruolo').click(function(){
        var line = '<tr><td><input name="ruolo" type="text" value="" /></td>'+
              '<td class="input-group color-picker colorpicker-component"><input name="color" type="text" value="" /><span class="input-group-addon"><i></i></span></td>'+
              '<td><input name="ordering" type="number" value="" /></td>'+
              '<td><a class="btn btn-sm btn-success ruolo_edit-add" data-id=""><i class="fa fa-save"></i></a> '+
              '</td></tr>';
        $('#ruoli-table > tbody').append(line);
        $('.color-picker').colorpicker();
    });

    $(function() {
        $('.color-picker').colorpicker();
    });

});
$(document).on("click",".ruolo_edit-add",function() {
    var id = $(this).attr('data-id');
    var button  = $(this);
    var parentForm = $(this).parent().parent();
    var ruolo = parentForm.find('[name="ruolo"]').val();
    var color = parentForm.find('[name="color"]').val();
    var ordering = parentForm.find('[name="ordering"]').val();
    $.ajax({
        url : "<?= Router::url('/admin/aziende/aziende/editAddRuolo/')?>"+id,
        type: "POST",
        data : {ruolo:ruolo, color:color, ordering:ordering},
        success: function(data, textStatus, jqXHR)
        {
            if(!isNaN(data)){
              $(button).attr('data-id',data);
              alert('Salvataggio avvenuto con successo');
            }else{
              alert(data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {

        }
      });
});
$(document).on("click",".ruolo_delete",function() {
    var id = $(this).attr('data-id');
    var parentForm = $(this).parent().parent();
    $.ajax({
        url : "<?= Router::url('/admin/aziende/aziende/deleteRuolo/')?>"+id,
        type: "GET",
        success: function(data, textStatus, jqXHR)
        {
            if(data == 1){
              parentForm.remove();
              alert('Cancellazione avvenuta con successo');
            }else{
              alert(data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {

        }
      });
});
</script>
<div>
    <h1><i class="fa fa-industry"></i> Gestione Plugin Aziende</h1>
    <h3>Da questa pagina è possibile gestire i componenti del Plugin Aziende.</h3>
</div>
<hr>
<div class="row">
    <div class="col-md-6">
      <h2>Ruoli Contatti</h2>
      <p>Da questa tabella puoi modificare o aggiungere ruoli:
        <a id="add-ruolo" class="btn btn-sm btn-info pull-right" href="#" ><i class="fa fa-plus"></i></a>
      </p>
      <div class="table-content">
        <table id="ruoli-table" class="table">
          <thead>
            <tr>
              <th>Ruolo</th>
              <th>Colore</th>
              <th>Ordine</th>
              <th>Azioni</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ruoli as $ruolo): ?>
              <tr>
                <td><input name="ruolo" type="text" value="<?= $ruolo['ruolo'] ?>" /></td>
                <td class="input-group color-picker colorpicker-component"><input name="color" type="text" value="<?= $ruolo['color'] ?>" /><span class="input-group-addon"><i></i></span></td>
                <td><input name="ordering" type="number" value="<?= $ruolo['ordering'] ?>" /></td>
                <td>
                  <a class="btn btn-sm btn-success ruolo_edit-add" data-id="<?= $ruolo['id'] ?>" ><i class="fa fa-check"></i></a>
                  <a class="btn btn-sm btn-danger ruolo_delete" data-id="<?= $ruolo['id'] ?>" ><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
</div>
