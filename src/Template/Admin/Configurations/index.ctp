<?php
use Cake\Routing\Router;
################################################################################
#
# Companee :   index (https://www.companee.it)
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

$user = $this->request->session()->read('Auth.User');

?>
<script>
    $(document).ready(function(){

        $('#tbl-configurazioni').tablesorter({
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

        $('.delete-config').click(function(e){

            if(!confirm("Si è sicuri di voler eliminare la configurazione? L'operazione non sarà reversibile.")){
                e.preventDefault();
            }

        });

    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-cog"></i> Gestione Configurazioni di sistema</h1>
    <h3>Da questa pagina è possibile gestire tutte le configurazioni di sistema.</h3>
</div>
<hr>
<?php foreach($configTypes as $type): ?>
  <div style="clear: both"></div>
  <div class="box box-info <?= ($type == 'generico'? '': 'collapsed-box')  ?>">
      <div class="box-header with-border">
          <h3 class="box-title"><?= ucfirst($type) ?></h3>
              <div class="box-tools pull-right">
                  <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-<?= ($type == 'generico'? 'minus': 'plus')  ?>"></i></button>
                  <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
              </div>
      </div><!-- /.box-header -->
      <div class="box-body ">
        <table id="tbl-configurazdioni" class="table table-striped table-hover ">
            <thead>
                <tr>
                    <th width="15%">Chiave</th>
                    <th width="60%">Etichetta</th>
                    <th width="15%">Valore</th>
                    <th width="10%" class="filter-false" data-sorter="false">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($configs[$type]) && !empty($configs[$type])): ?>
                    <?php foreach ($configs[$type] as $key => $config): ?>
                          <tr>
                              <td><?=$config->key_conf?></td>
                              <td title="<?=$config->tooltip?>"><?=$config->label?></td>
                              <td><?=$config->value?></td>
                              <td>
                                  <a class="btn btn-xs btn-primary" href="<?=Router::url('/admin/configurations/edit/' . $config->id)?>" title="Modifica"><i class="glyphicon glyphicon-pencil"></i></a>
                                  <?php if($user['level'] > 900){ ?>
                                      <a class="btn btn-xs btn-danger delete-config" href="<?=Router::url('/admin/configurations/delete/' . $config->id)?>" title="Elimina"><i class="glyphicon glyphicon-remove"></i></a>
                                  <?php } ?>
                              </td>
                          </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">Non ci sono configurazioni da mostrare.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
      </div>
    </div>
<?php endforeach ?>
<?php /* <div>

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
        <?php if($user['level'] > 900){ ?>
            <a href="<?=Router::url('/admin/configurations/add');?>" class="btn btn-primary" title="Crea utente"><i class="glyphicon glyphicon-plus"></i></a>
        <?php } ?>
    </div>
    <div style="clear: both"></div>
    <table id="tbl-configurazioni" class="table table-striped table-hover ">
        <thead>
            <tr>
                <th>Chiave</th>
                <th>Etichetta</th>
                <th>Valore</th>
                <th class="filter-false" data-sorter="false">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($configurations) && !empty($configurations)){ ?>
                <?php foreach ($configurations as $key => $config) { ?>
                    <tr>
                        <td><?=$config->key_conf?></td>
                        <td title="<?=$config->tooltip?>"><?=$config->label?></td>
                        <td><?=$config->value?></td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="<?=Router::url('/admin/configurations/edit/' . $config->id)?>" title="Modifica"><i class="glyphicon glyphicon-pencil"></i></a>
                            <?php if($user['level'] > 900){ ?>
                                <a class="btn btn-xs btn-danger delete-config" href="<?=Router::url('/admin/configurations/delete/' . $config->id)?>" title="Elimina"><i class="glyphicon glyphicon-remove"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                    <td colspan="10">Non ci sono configurazioni da mostrare.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
*/ ?>
