<?php

use Cake\Routing\Router;
use Cake\View\Helper\FormHelper;

$this->assign('title', $title);
echo $this->Element('Aziende.include');
echo $this->Html->script('Aziende.statements.js');
echo $this->Html->script('Aziende.statement_form.js');
?>
<script>
    var company = <?= $company ?? 'false' ?>;
    var ati = <?=$ati;?>;
</script>
<section class="content-header">
    <h1>
        <?= __c('Rendiconti') ?>
        <small>Gestione <?= __c('rendicontinti') ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Router::url('/'); ?>"><i class="fa fa-home"></i> Home</a></li>
        <li>Gestione rendiconti</li>
        <li class="active">Visualizza</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="fa fa-list-ul"></i>
                    <h3 class="box-title"><?= __c('Visualizza rendiconto') ?></h3>
                    <a href="<?= $this->request->env('HTTP_REFERER'); ?>" class="pull-right"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>

                <div class="box-body">
                    <div class="row" style="display: flex; justify-content: space-between;">
                        <div class="col-md-2"><b>Ente: </b><?= $statement->agreement->aziende->denominazione ?></div>
                        <div class="col-md-2"><b>CIG:</b> <?= $statement->agreement->cig ?></div>
                        <div class="col-md-2 panel panel-default"><?= $statement->agreement->procedure->name ?></div>

                        <?php if ($ati) : ?>
                            <div class="col-md-1"><button id="deny" type="button" class="btn btn-danger">Rifiuta</button></div>
                            <div class="col-md-1"><button id="approve" type="button" class="btn btn-success">Approva</button></div>
                            <div class="col-md-1"><span id="status" class="badge"></span></div>
                        <?php else : ?>
                            <?php if ($statement->companies[0]->status_id == 1) : ?>
                                <div class="col-md-1"><button id="deny" data-id="<?=$statement->companies[0]->id?>" type="button" class="btn btn-danger">Rifiuta</button></div>
                                <div class="col-md-1"><button id="approve" data-id="<?=$statement->companies[0]->id?>" type="button" class="btn btn-success">Approva</button></div>
                                <div class="col-md-1"><span id="status" class="badge"></span></div>
                            <?php else : ?>
                                <?php if ($statement->companies[0]->status_id == 2) : ?>
                                    <div class="col-md-1"><span id="status" class="badge btn-success"><?=$statement->companies[0]->status->name;?></span></div>
                                <?php elseif ($statement->companies[0]->status_id == 3) : ?>
                                    <div class="col-md-1"><span id="status" class="badge btn-danger"><?=$statement->companies[0]->status->name;?></span></div>
                                <?php else : ?>
                                    <div class="col-md-1"><span id="status" class="badge btn-default"><?=$statement->companies[0]->status->name;?></span></div>
                                <?php endif ?>
                            <?php endif ?>
                        <?php endif ?>
                    </div>

                    <?php
                        $this->Form->setTemplates($form_template);
                        echo $this->Form->create($statement, ['url' => ['action' => 'edit'], 'class' => 'form-horizontal', 'type' => 'file', 'id' => 'main-form']);
                        echo $this->element('statement_form');
                    ?>

                    <div class="button-group" style="text-align: end;">
                        <a class="btn btn-default" href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']) ?>" role="button">Annulla</a>
                        <button class="btn btn-primary" type="submit" id="save-statment">Salva</button>
                    </div>

                    <?= $this->Form->end(); ?>

                    <?= $this->Form->postButton(
                        'Elimina', 
                        [
                            "plugin" => "Aziende",
                            "controller" => 'Statements',
                            "action" => "delete",
                            $statement->id
                        ],
                        [
                            'confirm' => 'Eliminare il rendiconto?',
                            'class' => 'btn btn-danger',
                            'style' => "float: left; margin-top:-34px;"
                        ]
                    );?>

                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-money"></i>
                        <h3 class="box-title"><?= __c('Spese') ?></h3>
                    </div>

                <div class="box-body">
                    <div class="container-fluid">
                        <form id="add-cost">
                            <div class="row">

                                <?php if (!$ati) :?>
                                    <input type="hidden" name="statement_company" value=<?=$statement->companies[0]->id?>>
                                <?php else: ?>
                                    <input type="hidden" name="statement_company" value="">
                                <?php endif?>

                                <div class="form-group col-sm-4">
                                    <label class="control-label required">Categoria</label>
                                    <select id="searchCat" class="select2 form-control" name="category_id" required></select>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label class="control-label required">Costo</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" name="amount" required>
                                </div>

                                <div class="form-group col-sm-2 required">
                                    <label class="control-label ">Quota parte</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" name="share" required>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label class="control-label required">Data</label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label class="control-label required">Num doc</label>
                                    <input type="text" class="form-control" name="number" required>
                                </div>


                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="control-label required">Descrizione</label>
                                    <input type="text" class="form-control" name="description" required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="control-label required">Fornitore</label>
                                    <input type="text" class="form-control" name="supplier" required>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label class="control-label">Note</label>
                                    <input type="text" class="form-control" name="notes" maxlength="255">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label class="control-label ">File</label>
                                    <input type="file" class="form-control" name="file">
                                </div>
                            </div>
                        
                        <div class="form-group">
                        <?php if (!$ati) :?>
                            <button id="save-cat" class="btn btn-success">Aggiungi</button>
                        <?php else: ?>
                            <button id="save-cat" class="btn btn-success" disabled>Aggiungi</button>
                        <?php endif?>
                        </div>
                    </form>
                    </div>

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"></div>
                </div>
            </div>

        </div>




    </div>
</section>



