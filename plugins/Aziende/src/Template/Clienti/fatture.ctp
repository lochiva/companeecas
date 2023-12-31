<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Fatture  (https://www.companee.it)
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
<?php echo $this->Element('Aziende.include'); ?>

<section class="content-header">
    <h1>
        Fatture attive
        <small>Elenco gestione fatture attive dei clienti</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/aziende/home');?>">Aziende</a></li>
        <li class="active">Gestione Fatture Attive</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-invoice" class="box box-danger">
            	<div class="box-header with-border">
	              <i class="fa fa-list-ul"></i>
	              <h3 class="box-title">Elenco Fatture Attive</h3>
	              <a class="btn btn-info btn-xs pull-right new-active-invoice" data-toggle="modal" data-target="#myModalFatturaAttiva"  style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
	            </div>

                <div class="box-table-invoice box-body">
    	            <div id="pager-invoice" class="pager col-sm-6">
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
                        <table id="table-invoicepayable-attiva" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Emesso da</th>                                  
                                    <?php if($idCliente === 'all'):?>
                                        <th>Cliente</th>
                                    <?php endif ?>
                                    <th>Numero</th>
                                    <th>Data</th>
                                    <th>Importo da pagare</th>
                                    <th>Scadenza</th>
                                    <th data-filter="false">Allegato</th>
                                    <th>Pagato</th>
                                    <th style="min-width: 110px" data-sorter="false" data-filter="false" ></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('Aziende.modale_nuova_invoicepayable_attiva'); ?>
