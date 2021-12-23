<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role');
?>
<?php $this->assign('title', 'Interviste') ?>
<?= $this->Html->css('Surveys.surveys'); ?>
<?= $this->Html->script( 'Surveys.surveys', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Aziende')?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><?=__c('Aziende')?></li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-surveys" class="box box-surveys">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista aziende')?></h3>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-managing-entities box-body">

                    <div id="pager-managing-entities" class="pager col-sm-6">
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
                        <table id="table-managing-entities" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Azienda</th>
                                    <th width="110px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="text-align:center;">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
