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
<?php echo $this->Element('Aziende.include'); ?>
<?= $this->Html->script('Aziende.contatti'); ?>

<section class="content-header">
    <h1>
        Contatti
        <?php if(is_object($sede)){ ?>
            <small>gestione contatti <?php echo $sede->azienda->denominazione . " - " . $sede->indirizzo . " " . $sede->num_civico; ?></small>
        <?php }else{ ?>
            <?php if(is_object($azienda)){ ?>
                <small>gestione contatti <?=$azienda->denominazione?></small>
            <?php }else{ ?>
                <small>gestione contatti aziendali</small>
            <?php } ?>
        <?php } ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ragioneria') { ?>
        <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
        <?php } else { ?>
            <li><a href="<?=Router::url('/aziende/sedi/index/').$idAzienda;?>">Strutture</a></li>
        <?php } ?>
        <li class="active">Gestione Contatti</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-sedi" class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title">Elenco dei Contatti</h3>
                  <div id="box-general-action"  class=" pull-right">
                    <?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ente_ospiti' || $user['role'] == 'questura') { ?>
                        <a class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalContatto" data-backdrop="false" data-keyboard="false" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                    <?php } ?>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                  </div>
                </div>
                <div class="box-table-contatti box-body">

                        <div id="pager-contatti" class="pager col-sm-6">
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

                        <div class="table-content">

                        <table id="table-contatti" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="14%">Cognome</th>
                                    <th width="14%">Nome</th>
                                    <th width="14%">Ente</th>
                                    <th width="10%">Ruolo</th>
                                    <th width="10%">Login</th>
                                    <th width="10%">Telefono</th>
                                    <th width="10%">Cellulare</th>
                                    <th width="14%">Email</th>
                                    <th style="min-width: 84px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Remarks.modal_remarks_by_id'); ?>
<?php echo $this->Element('modale_nuovo_contatto'); ?>
