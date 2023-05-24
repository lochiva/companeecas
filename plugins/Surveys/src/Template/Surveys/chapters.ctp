<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Chapters  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
?>
<?php $this->assign('title', 'Capitoli') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->script('Surveys.surveys', ['block']); ?>
<?= $this->Html->script('../plugins/tinymce/jquery.tinymce.min', ['block']);?>
<?= $this->Html->script('Surveys.tinymce', ['block']); ?>

<section class="content-header">
    <h1>
        <?=__c('Capitoli')?>
        <small>Gestione <?=__c('capitol')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a><?= __('Configurazioni') ?></a></li>
        <li class="active"><?=__c('Capitoli')?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-targets" class="box box-info">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title"><?=__c('Elenco capitoli')?></h3>
                  <a id="newTarget" role="button" class="btn btn-info btn-sm pull-right btn-new" data-toggle="modal" data-target="#modalChapter" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                  <a href="<?= Router::url('/home/index'); ?>" class="pull-right btn-back" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-targets box-body">

                    <div class="pager pager-chapters col-sm-6">
                        <form>
                            <i class="first glyphicon glyphicon-step-backward"></i>
                            <i class="prev glyphicon glyphicon-backward"></i>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <i class="next glyphicon glyphicon-forward"></i>
                            <i class="last glyphicon glyphicon-step-forward"></i>
                            <select class="pagesize">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                            </select>
                        </form>
                    </div>


                    <div class="table-content">
                        <table id="table-chapters" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="70%">Nome</th>
                                    <th width="20%">Ordine</th>
                                    <th style="width: 130px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-center">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pager pager-chapters col-sm-6">
                        <form>
                            <i class="first glyphicon glyphicon-step-backward"></i>
                            <i class="prev glyphicon glyphicon-backward"></i>
                            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                            <i class="next glyphicon glyphicon-forward"></i>
                            <i class="last glyphicon glyphicon-step-forward"></i>
                            <select class="pagesize">
                                <option selected="selected" value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                            </select>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Surveys.modal_chapter'); ?>