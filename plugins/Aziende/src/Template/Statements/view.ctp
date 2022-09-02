<?php

use Cake\Routing\Router;
use Cake\View\Helper\FormHelper;

$this->assign('title', $title);
echo $this->Element('Aziende.include');
echo $this->Html->script('Aziende.statements.js');
echo $this->Html->script('Aziende.statement_form.js');
echo $this->Html->script('AttachmentManager.modal_attachment.js');

?>
<script>
    var company = <?= $company ?? 'false' ?>;
    var ati = <?=$ati;?>;
    var role = "<?=$user['role']?>";
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

        <div class="box box-x11yellow" id="status-container">
                <div class="box-header with-border">
                    <i class="fa fa-tasks"></i>
                    <h3 class="box-title"><?= __c('Stato rendiconto') ?></h3>
                </div>

                <div class="box-body">

                    <div class="row margin d-flex d-align-items-center">
                        <div class="col-md-1"><b>Stato:</b></div>
                        <div class="col-md-11">
                        <?php if ($ati) : ?>
                            <span id="status" data-status_id='' class="badge"></span>
                        <?php else : ?>
                            <?php switch ($statement->companies[0]->status_id):
                            case 1: ?>
                            <span id="status" data-status_id="<?=$statement->companies[0]->status_id;?>" class="badge btn-default"><?=$statement->companies[0]->status->name;?></span>
                            <?php break; ?>
                            <?php case 2 :?>
                                <span id="status" data-status_id="<?=$statement->companies[0]->status_id;?>" class="badge btn-success"><?=$statement->companies[0]->status->name;?></span>
                            <?php break; ?>
                            <?php case 3 :?>
                                <span id="status" data-status_id="<?=$statement->companies[0]->status_id;?>" class="badge btn-warning"><?=$statement->companies[0]->status->name;?></span>
                            <?php break; ?>
                            <?php case 4 :?>
                                <span id="status" data-status_id="<?=$statement->companies[0]->status_id;?>" class="badge btn-info"><?=$statement->companies[0]->status->name;?></span>
                            <?php break; ?>
                            <?php endswitch ?>
                        <?php endif ?>
                        </div>
                    </div>

                    <?php if ($ati) : ?>
                        <div class="row margin d-flex d-align-items-center" id="comments">
                            <div class="col-md-1"><b>Commenti integrazione:</b></div>

                            <div class="col-md-11">
                                <span id="text-notes"></span>
                            
                            <?php if ($user['role'] == 'ente') : ?>
                                <textarea class="form-control" style="overflow:auto;resize:none;border-color: #00acd6;" name="notes" disabled></textarea>
                            <?php else : ?>
                                <textarea class="form-control" style="overflow:auto;resize:none;border-color: #e08e0b;" name="notes"></textarea>
                            <?php endif ?>

                            </div>
                        </div>

                    <?php else : ?>

                        <?php if ($statement->companies[0]->status_id == 1) : ?>
                            <?php if ($user['role'] == 'ente') : ?>
                                <div class="row margin d-flex d-align-items-center" id="comments" style="display:none;">
                                    <div class="col-md-1"><b>Commenti integrazione:</b></div>
                                    <div class="col-md-11">
                                        <textarea class="form-control" style="overflow:auto;resize:none;border-color: #00acd6;" name="notes" disabled><?=$statement->companies[0]->notes?></textarea>
                                    </div>
                                </div>
                            <?php endif?>

                        <?php elseif ($statement->companies[0]->status_id == 2) : ?>
                            <div class="row margin d-flex d-align-items-center" id="comments">
                                <div class="col-md-1"><b>Commenti integrazione:</b></div>
                                <div class="col-md-11">
                                    <span id="text-notes"><?=$statement->companies[0]->notes?></span>
                                </div>
                            </div>
                            
                        <?php elseif ($statement->companies[0]->status_id == 3) : ?>
                            <div class="row margin d-flex d-align-items-center" id="comments">

                                <div class="col-md-1"><b>Commenti integrazione:</b></div>
                                <div class="col-md-11">
                                <?php if ($user['role'] == 'ente') : ?>
                                    <textarea class="form-control" style="overflow:auto;resize:none;border-color: #00acd6;" name="notes"><?=$statement->companies[0]->notes?></textarea>
                                <?php else : ?>
                                    <textarea class="form-control" style="overflow:auto;resize:none;border-color: #e08e0b;" name="notes" disabled><?=$statement->companies[0]->notes?></textarea>
                                <?php endif ?>
                                </div>

                            </div>
                                

                        <?php elseif ($statement->companies[0]->status_id == 4) :  ?>
                            <div class="row margin d-flex d-align-items-center" id="comments">
                                <div class="col-md-1"><b>Commenti integrazione:</b></div>
                                <div class="col-md-11">
                                <?php if ($user['role'] == 'ente') : ?>
                                        <textarea class="form-control" style="overflow:auto;resize:none;border-color: #00acd6;" name="notes" disabled><?=$statement->companies[0]->notes?></textarea>
                                <?php else : ?>
                                        <textarea class="form-control" style="overflow:auto;resize:none;border-color: #e08e0b;" name="notes"><?=$statement->companies[0]->notes?></textarea>
                                <?php endif ?>
                                </div>
                            </div>
        
                        <?php endif ?>

                    <?php endif ?>

                    <div class="row margin d-flex d-align-items-center" id="btn-actions">
                        <?php if ($ati) : ?>
                            <div class="col-md-1"><b>Azioni:</b></div>
                            <div class="col-md-11">
                            
                            <?php if ($user['role'] == 'ente') : ?>
                                <button id="send" data-id="" data-status-id=4 type="button" class="btn btn-info action-status">Invia per approvazione</button>
                            <?php else : ?>
                                <button id="deny" data-id="" data-status-id=3 type="button" class="btn btn-warning action-status" data-toggle="tooltip" data-placement="top" title="Fare click qui per richiedere l'integrazione">Richiesta integrazione</button>
                                <button id="approve" data-status-id=2 data-id="" type="button" class="btn btn-success action-status">Approva</button>
                            <?php endif ?>
                            </div>

                        <?php else : ?>

                            <?php if ($statement->companies[0]->status_id == 1) : ?>

                                <?php if ($user['role'] == 'ente') : ?>
                                    <div class="col-md-1"><b>Azioni:</b></div>
                                    <div class="col-md-11">
                                        <button id="send" data-id="<?=$statement->companies[0]->id?>" data-status-id=4 type="button" class="btn btn-info action-status">Invia per approvazione</button>
                                    </div>
                                <?php endif ?>

                            <?php elseif ($statement->companies[0]->status_id == 2) : ?>

                            <?php elseif ($statement->companies[0]->status_id == 3) : ?>
                                <div class="col-md-1"><b>Azioni:</b></div>
                                <div class="col-md-11">
                                <?php if ($user['role'] == 'ente') : ?>
                                    <button id="send" data-id="<?=$statement->companies[0]->id?>" data-status-id=4 type="button" class="btn btn-info action-status">Invia per approvazione</button>
                                <?php else : ?>
                                    <button id="deny" data-id="<?=$statement->companies[0]->id?>" data-status-id=3 type="button" class=" btn btn-warning" disabled>Richiesta integrazione</button>
                                    <button id="approve" data-status-id=2 data-id="<?=$statement->companies[0]->id?>" type="button" class="btn btn-success action-status" disabled>Approva</button>
                                <?php endif ?>
                                </div>
                                    

                            <?php elseif ($statement->companies[0]->status_id == 4) :  ?>
                                <div class="col-md-1"><b>Azioni:</b></div>
                                <div class="col-md-11">
                                <?php if ($user['role'] == 'ente') : ?>
                                    <button id="send" data-id="<?=$statement->companies[0]->id?>" data-status-id=4 type="button" class="btn btn-info action-status" disabled>Invia per approvazione</button>
                                <?php else : ?>
                                    <button id="deny" data-id="<?=$statement->companies[0]->id?>" data-status-id=3 type="button" class="btn btn-warning action-status">Richiesta integrazione</button>
                                    <button id="approve" data-status-id=2 data-id="<?=$statement->companies[0]->id?>" type="button" class="btn btn-success action-status">Approva</button>
                                <?php endif ?>
                                </div>
                                    
                            <?php endif ?>

                        <?php endif ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="fa fa-list-ul"></i>
                    <h3 class="box-title"><?= __c('Visualizza rendiconto') ?></h3>
                    <a href="<?= $this->request->env('HTTP_REFERER'); ?>" class="pull-right"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>

                <div class="box-body">
                    
                    <div class="row" style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <div class="col-md-2"><b>Ente: </b><?= $statement->agreement->aziende->denominazione ?></div>
                        <div class="col-md-2"><b>CIG:</b> <?= $statement->agreement->cig ?></div>
                        <div class="col-md-2">
                            <span hidden id='contextForAttachment'>agreements</span>
                            <span hidden id='idItemForAttachment'><?=$statement->agreement_id?></span>
                            <span hidden id="attachmentReadOnly">0</span>
                            <?= $this->element('AttachmentManager.button_attachment', ['id' => 'button_attachment', 'buttonLabel' => 'Allegati convenzione']); ?>
                        </div>
                    </div>

                    <?php
                        $this->Form->setTemplates($form_template);
                        echo $this->Form->create($statement, ['url' => ['action' => 'edit'], 'class' => 'form-horizontal', 'type' => 'file', 'id' => 'main-form']);
                        echo $this->element('statement_form');
                    ?>

                    <div class="button-group" style="text-align: end;">
                        <a class="btn btn-default" href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']) ?>" role="button">Annulla</a>

                        <?php if ($statement->companies[0]->status_id === 1 || $statement->companies[0]->status_id === 3) : ?>
                            <button class="btn btn-primary" type="submit" id="save-statment">Salva</button>
                        <?php elseif ($statement->companies[0]->status_id == 4) : ?>
                            <button class="btn btn-primary" type="submit" id="save-statment" disabled>Salva</button>
                        <?php endif ?>

                    </div>

                    <?= $this->Form->end(); ?>

                    <?php if ($ati) {

                        echo $this->Form->postButton(
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
                                'style' => "float: left; margin-top:-34px;",
                                'id' => 'delete-statement'
                            ]
                        );
                    } else {
                        echo $this->Form->postButton(
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
                                'style' => "float: left; margin-top:-34px;",
                                'id' => 'delete-statement',
                                'disabled' => $statement->companies[0]->status_id == 1 ? false : true
                            ]
                        );

                    } ?>

                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-brown">

                <div class="box-header with-border">
                    <i class="fa fa-calculator"></i>
                    <h3 class="box-title"><?= __c('Totali') ?></h3>
                </div>

                <div class="box-body">
                    <div class="container-fluid">
                        <p>
                            <span class="label-like">
                                <span class="badge btn-info" data-toggle="tooltip" data-html=true data-placement="top" title="<div class='text-justify'> Presenze totali nel periodo come registrato nel modulo presenze per le strutture collegata alla convenzione</div>">
                                    <i class="fa fa-info"></i>
                                </span>
                                Tot presenti
                            </span> 
                            <?=$presenze?> 
                            <span class="label-like">per canone</span> &euro;<?=number_format($statement->agreement->guest_daily_price, 2, '.', '')?> 
                            <span class="label-like">pari a</span> &euro;
                            <?= number_format($presenze * $statement->agreement->guest_daily_price, 2, '.', '')?>


                        </p>
                        <p>
                            <span class="label-like">
                                <span class="badge btn-info" data-toggle="tooltip" data-html=true data-placement="top" title="<div class='text-justify'>Numero di bambini con età minore di 30 mesi alla data di fine periodo</div>">
                                    <i class="fa fa-info"></i>
                                </span>
                                Minori di 30 mesi:
                            </span>
                            <?php if ($minors > 0) : ?>
                                SI (<?=$minors?>)
                            <?php else : ?>
                                NO
                            <?php endif ?>
                        </p>
                        
                    </div>
                </div>

            </div>
        </div>



        <div class="col-xs-12">
            <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-money"></i>
                        <h3 class="box-title" id="cost-headers"><?= __c('Spese') ?></h3>
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
                                    <label class="control-label required">Costo &euro;</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" name="amount" required>
                                </div>

                                <div class="form-group col-sm-2 required">
                                    <label class="control-label ">Quota parte &euro;</label>
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
                                    <input type="file" class="form-control" name="file" required>
                                </div>
                            </div>
                        
                        <div class="form-group">
                        <?php if (!$ati) :?>
                            <button id="save-cat" class="btn btn-success">Aggiungi</button>
                        <?php else: ?>
                            <button id="save-cat" class="btn btn-success" disabled>Aggiungi</button>
                        <?php endif?>
                        <button type="reset" class="btn btn-warning">Svuota</button>
                        </div>
                    </form>
                    </div>

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"></div>
                </div>
            </div>

        </div>




    </div>
</section>

<?= $this->element('AttachmentManager.modal_attachment'); ?>