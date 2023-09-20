<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    View  (https://www.companee.it)
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
        <small>Gestione <?= __c('rendiconti enti') ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Router::url('/'); ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'statements', 'action' => 'index']); ?>">Gestione rendiconti</a></li>
        <li class="active">Visualizza</li>
    </ol>
</section>

<section class="content">
    <div class="row">
    <?php if ($ati) : ?>
    <div class="col-xs-12">
        <div class="section-select-company col-xs-12 form-horizontal">
            <div class="container-select-company form-group">
                <div class="col-xs-4">
                    <?= $this->Form->control('select-company', [
                        'class' => 'select-company form-control',
                        'type' => 'select',
                        'multiple' => false,
                        'options' => $companies,
                        'empty' => "Selezionare un'azienda",
                        'value' => '',
                        'disabled' => [''],
                        'label' => ['text' => 'Report di', 'class' => 'control-label']
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>

    <div class="col-xs-12">
        <div class="col-xs-12">
            <div class="box box-x11yellow" id="status-container">
                <div class="box-header with-border statement-status-header">
                    <i class="fa fa-tasks"></i>
                    <h3 class="box-title"><?= __c('Stato rendiconto') ?></h3>
                    <span id="lastStatusLabel">
                        <?php 
                            $lastStatus = $statement->companies[0]->history[count($statement->companies[0]->history)-1];
                            switch ($lastStatus->status->id) {
                                case 1:
                                    $badgeClass = 'btn-default';
                                    break;
                                case 2:
                                    $badgeClass = 'btn-success';
                                    break;
                                case 3:
                                    $badgeClass = 'btn-warning';
                                    break;
                                case 4:
                                case 5:
                                    $badgeClass = 'btn-info';
                                    break;  
                                default:
                                    $badgeClass = 'btn-default';
                            } 
                        ?>
                        <span data-status-id="<?= $lastStatus->status->id ?>" class="badge <?= $badgeClass ?> badge-statement-status"><?= $lastStatus->status->name ?></span>
                        </span>
                        <?php if ($lastStatus->status->id == 2) { ?>
                            <span class="statement-status-date">approvato il <?= $lastStatus->created->format('d/m/Y') ?></span>
                        <?php } ?>
                        <?php if ($lastStatus->status->id == 4 && ($user['role'] == 'admin' || $user['role'] == 'ragioneria')) {
                        ?>
                            <span class="statement-status-date" id="due-date">
                                <?php if ($statement->companies[0]->due_date) : ?>
                                Da approvare entro il <?= $statement->companies[0]->due_date->format('d-m-Y') ?>
                                <?php endif ?>
                            </span>
                        <?php } ?>
                </div>
                <div class="box-body chat statement-status-body">
                <?php foreach ($statement->companies[0]->history as $history) { ?>
                    <div class="item">
                        <?php switch ($history->status->id) {
                            case 1:
                                $badgeClass = 'btn-default';
                                break;
                            case 2:
                                $badgeClass = 'btn-success';
                                break;
                            case 3:
                                $badgeClass = 'btn-warning';
                                break;
                            case 4:
                            case 5:
                                $badgeClass = 'btn-info';
                                break;  
                            default:
                                $badgeClass = 'btn-default';
                        } ?>
                        <span data-status-id="<?= $history->status->id ?>" class="badge <?= $badgeClass ?> badge-statement-status"><?= $history->status->name ?></span>
                        <p class="message">
                            <span class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?= $history->created->format('d/m/Y H:i:s') ?></small>
                                <span class="user-info <?= empty($history->note) ? 'no-message' : '' ?>"><?= empty($history->user->nome) && empty($history->user->cognome) ? '-' : $history->user->nome . ' '. $history->user->cognome ?> (<?= str_replace('_', ' ', $history->user->role) ?>)</span>
                            </span>
                            <?= $history->note ?>
                        </p>
                    </div>
                <?php } ?>
                </div>
                <?php 
                    $statusDisabled = '';
                    if (
                        ($user['role'] == 'ente_contabile' && ($ati || $lastStatus->status->id == 4 || $lastStatus->status->id == 5)) ||
                        (($user['role'] == 'admin' || $user['role'] == 'ragioneria') && in_array($lastStatus->status->id, [1, 3]))
                    ) {
                        $statusDisabled = 'disabled';
                    } 
                ?>
                <div class="box-footer" <?= $lastStatus->status->id == 2 ? 'hidden' : '' ?>>
                    <div class="input-group">
                        <input id="statusNote" name="notes" class="form-control" placeholder="Inserisci un commento..." <?= $statusDisabled ?>>
                        <div class="input-group-btn statement-status-actions">
                        <?php if ($user['role'] == 'ente_contabile') : ?>
                            <button id="send" data-id="<?= $statement->companies[0]->id ?>" data-status-id="4" class="btn btn-success action-status" <?= $statusDisabled ?>>Invia per approvazione</button>
                        <?php elseif ($user['role'] == 'admin' || $user['role'] == 'ragioneria') : ?>
                            <button class="btn btn-success action-status" id="approve" data-id="<?= $statement->companies[0]->id ?>" data-status-id="2" <?= $statusDisabled ?>>Approva</button>
                            <button class="btn btn-success dropdown-toggle action-status-dropdown" data-toggle="dropdown" <?= $statusDisabled ?>><span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="#" id="approve" data-id="<?= $statement->companies[0]->id ?>" data-status-id="2" class="dropdown-item action-status" <?= $statusDisabled ?>>
                                        Approva
                                    </a>
                                </li>
                                <li>
                                    <a href="#" id="deny" data-id="<?= $statement->companies[0]->id ?>" data-status-id="3" class="dropdown-item action-status" <?= $statusDisabled ?>>
                                        Richiesta integrazione
                                    </a>
                                </li>
                                <?php if ($lastStatus->status->id === 4) :?>
                                    <li>
                                        <a href="#" id="deny" data-id="<?= $statement->companies[0]->id ?>" data-status-id="5" class="dropdown-item action-status" <?= $statusDisabled ?>>
                                            In verifica
                                        </a>
                                    </li>
                                <?php elseif($lastStatus->status->id === 5) :?>
                                    <li>
                                        <a href="#" id="deny" data-id="<?= $statement->companies[0]->id ?>" data-status-id="4" class="dropdown-item action-status" <?= $statusDisabled ?>>
                                            In approvazione
                                        </a>
                                    </li>
                                <?php endif ?>
                            </ul>
                        <?php endif ?>
                        </div>
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

                        <?php if ($user['role'] == 'admin' || $user['role'] == 'ente_contabile') : ?>
                            <?php if ($statement->companies[0]->status_id === 1 || $statement->companies[0]->status_id === 3) : ?>
                                <button class="btn btn-primary" type="submit" id="save-statement">Salva</button>
                            <?php elseif ($statement->companies[0]->status_id == 4) : ?>
                                <button class="btn btn-primary" type="submit" id="save-statement" disabled>Salva</button>
                            <?php elseif ($statement->companies[0]->status_id == 2) : ?>
                                <button class="btn btn-primary" type="submit" id="save-statement" style="display: none">Salva</button>
                            <?php endif ?>
                        <?php endif ?>
                    </div>

                    <?= $this->Form->end(); ?>

                    <?php if ($user['role'] == 'admin' || $user['role'] == 'ente_contabile') : ?>
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
                    <?php endif ?>

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
                            <span id="totPresenze"><?=$presenze?></span>
                            <span class="label-like">per canone</span>
                            <span id="daily_price">&euro;<?=number_format($statement->agreement->guest_daily_price, 2, '.', '')?></span>
                            <span class="label-like">pari a</span> &euro;
                            <span id="presenzeRent"><?= number_format($presenze * $statement->agreement->guest_daily_price, 2, '.', '')?></span>


                        </p>
                        <p>
                            <span class="label-like">
                                <span class="badge btn-info" data-toggle="tooltip" data-html=true data-placement="top" title="<div class='text-justify'> Pocket money maturato considerando le presenze per le strutture collegate alla convenzione. Il sistema calcola un massimo di tre presenze per gruppo familiare per giornata.</div>">
                                    <i class="fa fa-info"></i>
                                </span> TOT pocket money maturati</span> <?=$pocketMoney['heads']?>
                            <span class="label-like">per</span> &euro;<?=$pocketMoney['factor']?>
                            <span class="label-like">pari a</span> &euro;<?=$pocketMoney['total']?>
                        </p>
                        <p>
                            <span class="label-like">
                                <span class="badge btn-info" data-toggle="tooltip" data-html=true data-placement="top" title="<div class='text-justify'>Numero di bambini con età minore di 30 mesi alla data di fine periodo</div>">
                                    <i class="fa fa-info"></i>
                                </span>
                                Minori di 30 mesi:
                            </span>
                            <span id="minors">
                                <?php if ($minors > 0) : ?>
                                    SI (<?=$minors?>)
                                <?php else : ?>
                                    NO
                                <?php endif ?>
                            </span>
                        </p>
                    </div>
                </div>

            </div>
        </div>



        <div class="col-xs-12" id="costs-box">
            <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-money"></i>
                        <h3 class="box-title" id="cost-headers"><?= __c('Spese') ?></h3>
                    </div>

                <div class="box-body">
                    <div class="container-fluid">
                    <?php if ($user['role'] == 'admin' || $user['role'] == 'ente_contabile') : ?>
                        <?php if ($ati) :?>
                            <form id="add-cost">
                        <?php else :?>
                            <?php switch ($statement->companies[0]->status_id):
                                case 1:
                            ?>
                                    <form id="add-cost">
                                <?php break; ?>
                                <?php case 2:?>
                                    <form id="add-cost" style="display: none;">
                                <?php break; ?>
                                <?php case 3:?>
                                    <form id="add-cost">
                                <?php break; ?>
                                <?php case 4:?>
                                    <form id="add-cost" style="display: none;">
                                <?php break; ?>
                            <?php endswitch ?>

                        <?php endif ?>
                        <?php else: ?>
                            <form id="add-cost" style="display: none;">
                        <?php endif ?>   
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
                                        <label class="control-label required">Totale documento &euro;</label>
                                        <input type="number" step="0.01" min="0.01" class="form-control" name="amount" required>
                                    </div>

                                    <div class="form-group col-sm-2 required">
                                        <label class="control-label ">Quota parte &euro;</label>
                                        <input type="number" step="0.01" min="0.01" class="form-control" name="share" required>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label class="control-label required">Data</label>
                                        <input type="date" class="form-control" name="date" min="1900-01-01" max="9999-12-31" required>
                                    </div>

                                    <div class="form-group col-sm-2">
                                        <label class="control-label required">Num doc</label>
                                        <input type="text" class="form-control" name="number" required>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="control-label">Descrizione</label>
                                        <input type="text" class="form-control" name="description">
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
                                    <button id="save-cat" class="btn btn-success" data-cost=false>Aggiungi</button>
                                <?php else: ?>
                                    <button id="save-cat" class="btn btn-success" data-cost=false disabled>Aggiungi</button>
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
