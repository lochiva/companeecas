<?php
/** 
* Companee :    index (https://www.companee.it)
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
use Cake\I18n\Time;

?>
<script>
    $(document).ready(function(){

        $('#tbl-groups').tablesorter({
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

        $('.delete-group').click(function(e){

            if(!confirm("Si è sicuri di voler eliminare l'utente? L'operazione non sarà reversibile.")){
                e.preventDefault();
            }

        });

    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-log-in"></i> Gestione Gruppi</h1>
    <h3>Da questa pagina è possibile gestire i gruppi del portale.</h3>
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
        <a href="<?=Router::url('/admin/groups/add');?>" class="btn btn-primary" title="Crea utente"><i class="glyphicon glyphicon-plus"></i></a>
    </div>
    <div style="clear: both"></div>
    <table id="tbl-groups" class="table table-striped table-hover ">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Note</th>
                <th>Data Crezione</th>
                <th class="filter-false" data-sorter="false">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($groups as $key => $group) { ?>
                <tr>
                    <td><?=$group->id?></td>
                    <td><?=$group->name?></td>
                    <td><?=$group->note?></td>
                    <td><?=$group->created->i18nFormat('dd/MM/yyyy') ?></td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="<?=Router::url('/admin/groups/edit/' . $group->id)?>" title="Modifica"><i class="glyphicon glyphicon-pencil"></i></a>
                        <a class="btn btn-xs btn-danger delete-group" href="<?=Router::url('/admin/groups/delete/' . $group->id)?>" title="Elimina"><i class="glyphicon glyphicon-remove"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
