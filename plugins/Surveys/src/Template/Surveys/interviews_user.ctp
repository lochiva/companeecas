<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Interviews User  (https://www.companee.it)
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

$role = $this->request->session()->read('Auth.User.role');
?>
<?php $this->assign('title', 'Interviste') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->script( 'Surveys.surveys', ['block']); ?>
<script>
    var id_managing_entity = '<?= $managingEntityId ?>';
    var id_structure = '<?= $structure['id'] ?>';
</script>
<section class="content-header">
    <h1>
        <?=__c('Interviste')?> <?= strtoupper($structure['comune']).' - '.$structure['indirizzo'] ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/surveys/surveys/managingEntities');?>"><?=__c('Aziende')?></a></li>
        <li><a href="<?=Router::url('/surveys/surveys/structures/'.$managingEntityId);?>"><?=__c('Sedi')?></a></li>
        <li class="active"><?=__c('Interviste')?></li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-surveys" class="box box-surveys">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista interviste')?></h3>
                  <button id="addInterview" data-entity="<?= $managingEntityId ?>" data-structure="<?= $structure['id'] ?>" class="btn btn-info btn-xs pull-right" style="margin-left:10px"><i class="fa fa-plus"></i> Nuova</button>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-interviews-user box-body">

                    <div id="pager-interviews-user" class="pager col-sm-6">
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
                        <table id="table-interviews-user" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Titolo</th>
                                    <th>Sottotitolo</th>
                                    <th>Descrizione</th>
                                    <th>Data creazione</th>
                                    <th>Ultimo compilatore</th>
                                    <th width="135px" class="filter-select filter-onlyAvail status-filter">Stato</th>
                                    <th class="filter-select filter-onlyAvail valid-filter">Valida</th>
                                    <th>Data firma</th>
                                    <th width="100px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" style="text-align:center;">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Surveys.modal_survey_choice') ?>
