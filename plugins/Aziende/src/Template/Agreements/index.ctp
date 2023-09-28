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

$role = $this->request->session()->read('Auth.User.role'); 
?>
<script>
    var azienda_id = '<?= $azienda['id'] ?>';
    var role = '<?= $role ?>';
</script>
<?php $this->assign('title', 'Convenzioni') ?>
<?= $this->Html->css('Aziende.aziende'); ?>
<?= $this->Html->script('Aziende.agreements', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Convenzioni per ente '.$azienda['denominazione'])?>
        <small>Gestione <?=__c('convenzioni')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <?php if ($role == 'admin' || $role == 'area_iv' || $role == 'ro_area_iv' || $role == 'ragioneria') { ?>
        <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
        <?php } ?>
        <li><a href="<?=Router::url('/aziende/sedi/index/'.$azienda['id']);?>">Strutture</a></li>
        <li class="active">Gestione convenzioni</li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-guests" class="box box-guests">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista convenzioni')?></h3>
                  <?php if ($role == 'admin' || $role == 'area_iv' || $role == 'ro_area_iv' ) { ?>
                    <a id="newAgreement" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#modalAgreement" data-backdrop="false" data-keyboard="false" style="margin-left:10px" data-denominazione="<?=$azienda['denominazione']?>"><i class="fa fa-plus"></i> Nuovo</a>
                  <?php } ?>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-agreements box-body">

                    <div id="pager-agreements" class="pager col-sm-6">
                        <form>
                            <i class="first glyphicon glyphicon-step-backward"></i>
                            <i class="prev glyphicon glyphicon-backward"></i>
                            <span class="pagedisplay"></span> 
                            <i class="next glyphicon glyphicon-forward"></i>
                            <i class="last glyphicon glyphicon-step-forward"></i>
                            <select class="pagesize">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </form>
                    </div>

                    <div class="table-content">
                        <table id="table-agreements" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Procedura di affidamento</th>
                                    <th>Data di stipula della convenzione</th>
                                    <th>Data di scadenza della convenzione</th>
                                    <th>Data di scadenza della proroga</th>
                                    <th>Prezzo giornaliero per ospite</th>
                                    <th>Incremento posti (%)</th>
                                    <th width="70px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= $this->element('modale_nuovo_agreement'); ?>
</section>

