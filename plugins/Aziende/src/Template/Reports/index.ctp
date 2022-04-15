<?php
use Cake\Routing\Router;
?>

<?php $this->assign('title', 'Report') ?>
<?= $this->Html->css('Aziende.reports'); ?>
<?= $this->Html->script('Aziende.reports', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Report')?>
        <small><?=__c('Generazione reportistica')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Report</li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-guests" class="box box-guests">
                <div class="box-header with-border">
                    <i class="fa fa-users"></i>
                    <h3 class="box-title">Report ospiti Emergenza Ucraina</h3>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-reports box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="date">Data</label>
                            <input id="date" type="text" placeholder="gg/mm/aaaa" class="form-control datepicker" value="<?= date('d/m/Y') ?>">
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="exportGuestsEmergenzaUcraina" class="btn btn-success">Genera presenza Ucraina</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
