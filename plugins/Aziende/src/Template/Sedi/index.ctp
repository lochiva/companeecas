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

$user = $this->request->session()->read('Auth.User');
?>
<script>
    var role = '<?= $user['role'] ?>';
    var ente_tipo = <?= $azienda->id_tipo ?>;
</script>
<?php echo $this->Element('Aziende.include'); ?>
<?php //echo "<pre>"; print_r($azienda); echo "</pre>"; ?>
<?= $this->Html->script( 'Aziende.sedi' ); ?>
<section class="content-header">
    <h1>
        Strutture
        <?php if(is_object($azienda)){ ?>
            <small>gestione strutture <?php echo $azienda->denominazione; ?></small>
        <?php }else{ ?>
            <small>gestione strutture</small>
        <?php } ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ragioneria') { ?>
        <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
        <?php } ?>
        <li class="active">Gestione strutture</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-sedi" class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title">Elenco delle strutture</h3>
                  <div id="box-general-action"  class=" pull-right">
                    <?php if ($azienda->id_tipo == 1) { 
                        if($user['role'] != 'questura'){?>
                        
                        <a class="btn btn-primary btn-xs pull-right" href="<?= Router::url('/aziende/agreements/index/' . $azienda->id)?>" style="margin-left: 10px;"><i class="fa fa-file-text-o"></i> Convenzioni</a>
                    <?php } }?>
                    <?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ente_ospiti') { ?>
                    <a class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalSede" data-backdrop="false" data-keyboard="false" onclick="clearModale()" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                    <?php } ?>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                  </div>
                </div>
                <div class="box-table-sedi box-body">

                        <div id="pager-sedi" class="pager col-sm-6">
                            <form>
                                <i class="first glyphicon glyphicon-step-backward"></i>
                                <i class="prev glyphicon glyphicon-backward"></i>
                                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                <i class="next glyphicon glyphicon-forward"></i>
                                <i class="last glyphicon glyphicon-step-forward"/></i>
                                <select class="pagesize">
                                    <option selected="selected" value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                            </form>
                        </div>

                        <table id="table-sedi" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Codice centro</th>
                                    <th>Tipologia ministero</th>
                                    <?php if ($azienda->id_tipo == 1) { ?>
                                    <th>Tipologia capitolato</th>
                                    <?php } ?>
                                    <th>Indirizzo</th>
                                    <th>Civico</th>
                                    <th>Cap</th>
                                    <th>Comune</th>
                                    <th>Provincia</th>
                                    <th data-sorter="false" data-filter="false">
                                        <span data-toggle="tooltip" data-placement="bottom" title="Posti occupati / capienza effettiva" class="table-label">
                                            Posti
                                        </span>
                                    </th>
                                    <?php if ($azienda->id_tipo == 1) { ?>
                                    <th>Tipologia ospiti</th>
                                    <?php } ?>
                                    <th style="min-width:110px" data-sorter="false" data-filter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="11">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('modale_nuova_sede'); ?>
