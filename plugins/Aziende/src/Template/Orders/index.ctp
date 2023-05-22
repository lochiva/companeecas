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
?>
<?php $this->assign('title',$title); ?>
<?php echo $this->Element('Aziende.include'); ?>
<?php //echo "<pre>"; print_r($azienda); echo "</pre>"; ?>
<?= $this->Html->script( 'Aziende.orders' ); ?>
<section class="content-header">
    <h1>
        Ordini
        <?php if(is_object($azienda)){ ?>
            <small>gestione ordini <?php echo $azienda->denominazione; ?></small>
        <?php }else{ ?>
            <small>gestione ordini aziendali</small>
        <?php } ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/aziende/home');?>">Aziende</a></li>
        <li class="active">Gestione Ordini</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-orders" class="box box-warning">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title">Elenco degli Ordini</h3>
                  <div id="box-general-action"  class=" pull-right">
                    <a id="new-order" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalOrder" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                  </div>
                </div>
                <div class="box-table-orders box-body">

                        <div id="pager-orders" class="pager col-sm-6">
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
                            <table id="table-orders" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <?php if($idAzienda === 'all'):?>
                                          <th>Azienda</th>
                                        <?php endif ?>
                                        <th>Nome</th>
                                        <th>Note</th>
                                        <th>Contatto di riferimento</th>
                                        <th>Data Apertura</th>
                                        <th>Data Chiusura</th>
                                        <th>Stato</th>
                                        <th style="min-width: 84px;" data-sorter="false" data-filter="false" ></th>
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
<?php echo $this->Element('modale_nuovo_order'); ?>
