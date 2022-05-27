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

<?php if ($role == 'admin' || ($role == 'ente' && $azienda['id_tipo'] == 1)) { ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-guests" class="box box-guests">
                <div class="box-header with-border">
                    <i class="fa fa-users"></i>
                    <h3 class="box-title">Report ospiti CAS</h3>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-reports box-body">
                    <?php if ($role == 'admin') { ?>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label" for="dateReportGuestsCas">Data</label>
                                <input id="dateReportGuestsCas" type="text" placeholder="gg/mm/aaaa" class="form-control datepicker" value="<?= date('d/m/Y') ?>">
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="reportGuestsCas" class="btn btn-success">Genera presenza CAS</button>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($role == 'ente' && $azienda['id_tipo'] == 1) { ?>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label" for="yearExportGuestsCas">Anno</label>
                                <select id="yearExportGuestsCas" class="form-control">
                                    <?php foreach (range(date('Y'), 2021) as $year) { ?>
                                        <option value="<?= $year ?>" <?= date('Y') == $year ? 'selected' : '' ?>><?= $year ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label" for="monthExportGuestsCas">Mese</label>
                                <select id="monthExportGuestsCas"class="form-control">
                                    <option value="01" <?= date('m') == '01' ? 'selected' : '' ?>>Gennaio</option>
                                    <option value="02" <?= date('m') == '02' ? 'selected' : '' ?>>Febbraio</option>
                                    <option value="03" <?= date('m') == '03' ? 'selected' : '' ?>>Marzo</option>
                                    <option value="04" <?= date('m') == '04' ? 'selected' : '' ?>>Aprile</option>
                                    <option value="05" <?= date('m') == '05' ? 'selected' : '' ?>>Maggio</option>
                                    <option value="06" <?= date('m') == '06' ? 'selected' : '' ?>>Giugno</option>
                                    <option value="07" <?= date('m') == '07' ? 'selected' : '' ?>>Luglio</option>
                                    <option value="08" <?= date('m') == '08' ? 'selected' : '' ?>>Agosto</option>
                                    <option value="09" <?= date('m') == '09' ? 'selected' : '' ?>>Settembre</option>
                                    <option value="10" <?= date('m') == '10' ? 'selected' : '' ?>>Ottobre</option>
                                    <option value="11" <?= date('m') == '11' ? 'selected' : '' ?>>Novembre</option>
                                    <option value="12" <?= date('m') == '12' ? 'selected' : '' ?>>Dicembre</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="exportGuestsCas" class="btn btn-success">Esporta tutti gli ospiti</button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php if ($role == 'admin' || ($role == 'ente' && $azienda['id_tipo'] == 2)) { ?>
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
                    <?php if ($role == 'admin') { ?>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label" for="dateReportGuestsEmergenzaUcraina">Data</label>
                                <input id="dateReportGuestsEmergenzaUcraina" type="text" placeholder="gg/mm/aaaa" class="form-control datepicker" value="<?= date('d/m/Y') ?>">
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="reportGuestsEmergenzaUcraina" class="btn btn-success">Genera presenza Ucraina</button>
                            </div>
                        </div>
                    <?php } ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" id="exportGuestsEmergenzaUcraina" class="btn btn-success">Esporta tutti gli ospiti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>