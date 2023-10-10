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
<?php $this->assign('title', 'Notifiche') ?>
<?= $this->Html->css('Aziende.guests'); ?>
<?= $this->Html->script('Aziende.statements_notifications', ['block']); ?>

<section class="content-header">
    <h1>
        Notifiche  Rendiconti
        <small>Gestione notifiche
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Notifiche</li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-statements" class="box box-statements">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista notifiche')?></h3>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-statements box-body">

                    <div id="pager-statements-notifications" class="pager col-sm-6">
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

                    <div class="text-right">
                        <input type="checkbox" id="showAllNotifications"> Mostra anche le notifiche gestite<br>
                        <button id="markAllNotificationsDone" type="button" class="btn btn-xs btn-primary">Segna tutte come gestite</button>

                    </div>

                    <div class="table-content">
                        <table id="table-statements-notifications" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Data di creazione</th>
                                    <th>Ente</th>
                                    <th>CIG</th>
                                    <th>Periodo</th>
                                    <th>Inizio</th>
                                    <th>Fine</th>
                                    <th>Descrizione</th>
                                    <th class="filter-select filter-match">Gestita</th>
                                    <th>Rendiconto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
