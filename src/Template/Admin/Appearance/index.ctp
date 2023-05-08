<?php
use Cake\Routing\Router;
################################################################################
#
# Companee :   Index (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################
?>
<script>
    var pathServer = '<?=Router::url('/')?>';
    $(document).ready(function(){

        $('#table-backgrounds').tablesorter({
            widthFixed: true,
            widgets: [ "zebra" , 'columns', 'filter', 'uitheme', 'bootstrap']
        }).tablesorterPager({
            container: $("#pager"),
            // output string - default is '{page}/{totalPages}'; possible variables: {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
            output: '{startRow} - {endRow} / {filteredRows} ({totalRows})',
            // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
            // table row set to a height to compensate; default is false
            fixedHeight: false,
            // remove rows from the table to speed up the sort of large tables.
            // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
            removeRows: false,
            // go to page selector - select dropdown that sets the current page
            cssGoto: '.gotoPage'
        });

        //elimina sfondo
        $('.delete-background').click(function(e){

            if(confirm("Si è sicuri di voler eliminare l'immagine di sfondo? L'operazione non sarà reversibile.")){
                var background_id = $(this).attr('data-id');
                $.ajax({
                    url: pathServer+'admin/appearance/deleteBackground/'+background_id,
                    type: "POST",
                    dataType: 'json'
                }).done(function(res){
                    if(res.response == 'OK'){
                        location.reload();
                    }else{
                        alert(res.msg);
                    }
                }).fail(function(richiesta,stato,errori){
                    alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                });
            }

        });

        //salva sfondo
        $('.add-background').click(function(e){
            $(this).prop('disabled', true);

            var formData = new FormData($('#backgroundUpload')[0]);
    
            $.ajax({
                url: pathServer+'admin/appearance/addBackground',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json'
            }).done(function(res){
                if(res.response == 'OK'){
                    location.reload();
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                $(this).prop('disabled', false);
            });
        });

        //controlli sull'immagine
        $("#backgroundImage").change(function(e) {  
            var file = this.files[0]; 
            if(file){ 
                $('.add-background').prop('disabled', false);
                $('.add-background').prop('title', '');
                var type = file.type.split('/').shift(); 
                var _URL = window.URL || window.webkitURL;
                var image = new Image();     

                image.onload = function(){  
                    if (this.width < 1440 || this.height < 900) {
                        alert("L'immagine deve avere una larghezza minima di 1440px e un'altezza minima di 900px.");
                        $("#backgroundImage").val('').trigger('change');
                    }
                };
                image.onerror = function() {
                    alert( "Il file caricato deve essere un'immagine.");
                    $("#backgroundImage").val('').trigger('change');
                };
                image.src = _URL.createObjectURL(file);       
            }else{
                $('.add-background').prop('disabled', true);
                $('.add-background').prop('title', 'Selezionare un\'immagine per poter salvare');
            }
        });

    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-eye-open"></i> Gestione Aspetto</h1>
    <h3>Da questa pagina è possibile gestire lo sfondo della pagina di login.</h3>
</div>
<hr>
<div>
    
    <div id="pager" class="pager tablesorter-pager">
        Pagina: 
        <select class="gotoPage" aria-disabled="false">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <i title="Prima pagina" class="first glyphicon glyphicon-step-backward" tabindex="0" aria-disabled="false"></i>
        <i title="Pagina precedente" class="prev glyphicon glyphicon-backward" tabindex="0" aria-disabled="false"></i>
        <span class="pagedisplay">21 - 30 / 50 (50)</span> <!-- this can be any element, including an input -->
        <i title="Pagina successiva" class="next glyphicon glyphicon-forward" tabindex="0" aria-disabled="false"></i>
        <i title="Ultima pagina" class="last glyphicon glyphicon-step-forward" tabindex="0" aria-disabled="false"></i>
        <select class="pagesize" aria-disabled="false">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
        </select>
    </div>
    <div class="box-action">
        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalBackground" title="Aggiungi sfondo"><i class="glyphicon glyphicon-plus"></i></a>
    </div>
    <div style="clear: both"></div>
    <table id="table-backgrounds" class="table table-striped table-hover ">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Percorso immagine</th>
                <th class="filter-false" data-sorter="false">Azioni</th>
            </tr>
        </thead>
        <tbody>
        <?php if(count($backgrounds) > 0){ ?>
        <?php foreach ($backgrounds as $background) { ?>
                <tr>
                    <td><?=$background->name?></td>
                    <td><?=$background->path?></td>
                    <td>
                        <a class="btn btn-xs btn-danger delete-background" data-id="<?=$background->id?>" title="Elimina sfondo"><i class="glyphicon glyphicon-remove"></i></a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else{?>
            <tr>
                <td colspan="3" style="text-align:center;">Nessuno sfondo trovato</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?= $this->element('modale_background'); ?>