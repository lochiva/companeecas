<?php
use Cake\Routing\Router;
?>
<?php $this->assign('title', 'Interviste') ?>
<?= $this->Html->css('Leads.leads'); ?>
<?= $this->Html->script( 'Leads.leads', ['block']); ?>
<section class="content-header">
    <h1><?=__c('Intervista')?></h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/admin/leads/interview/home');?>"><?=__c('Gestione interviste')?></a></li>
        <li class="active"><?=__c('Intervista')?></li>
    </ol>
</section>

<div class="padding15">
    <?= $this->Flash->render() ?>
</div>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-answers" class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Domande</h3>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" id="formAnswers" action="<?= Router::url('/admin/leads/interview/answers/'.$interview->id) ?>" enctype="multipart/form-data" method="post">
                        <?php $counter = 1; ?> 
                        <?php $printedQuestions = 0; ?>  
                        <?php foreach($questions as $q){ ?>
                            <?php $printedQuestions++; ?>  
                            <?php if($counter % 2 != 0){ ?>
                            <div class="form-group">
                            <?php } ?> 
                                <div class="input">
                                    <div class="col-md-6 col-sm-12"> 
                                        <?php if(!empty($q['info'])){ ?>
                                        <a class="info-question" data-toggle="modal" data-target="#modalInfoQuestion"><i class="fa fa-info-circle"></i></a>
                                        <span hidden class="text-info-question"><?= $q['info'] ?></span>
                                        &nbsp;
                                        <?php } ?>
                                        <label class="control-label" ><?= $q['name'] ?></label>
                                        <?php if($q['id_type'] == 3){ ?>
                                        <br />
                                        <div class="input-radio">
                                            <input type="radio" name="<?= $q['id'] ?>" 
                                                class="" value="Sì" <?= (!empty($answers[$q['id']]) && $answers[$q['id']]['answer'] == 'Sì') ? 'checked' : '' ?>/> Sì
                                            <input type="radio" name="<?= $q['id'] ?>" class="radio-no" value="No" <?= (!empty($answers[$q['id']]) && $answers[$q['id']]['answer'] == 'No') ? 'checked' : '' ?>/> No
                                        </div>
                                        <?php }elseif($q['id_type'] == 4){ ?> 
                                            <select name="<?= $q['id'] ?>" class="form-control" value="" >
                                                <option value=""></option>
                                                <?php foreach(explode(';', $q['options']) as $option){ ?> 
                                                    <option value="<?= $option ?>" <?= $answers[$q['id']]['answer'] == $option ? 'selected' : '' ?>><?= $option ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php }elseif($q['id_type'] == 2){ ?>
                                            <input type="text" name="<?= $q['id'] ?>" class="form-control datepicker" value="<?= !empty($answers[$q['id']]) ? $answers[$q['id']]['answer'] : '' ?>" />
                                        <?php }elseif($q['id_type'] == 5){ ?>
                                            <?php if(empty($answers[$q['id']]['answer'])){ ?>
                                                <input type="file" name="<?= $q['id'] ?>" class="form-control" value="" />
                                            <?php }else{ ?>
                                                <br />
                                                <a data-id="<?=$answers[$q['id']]['id']?>" id="downloadFile" class="btn btn-default" title="Scarica file"><i class="fa fa-download"></i></a>
                                                <a data-id="<?=$answers[$q['id']]['id']?>" id="deleteFile" class="btn btn-danger" title="Elimina file"><i class="fa fa-trash"></i></a>
                                            <?php } ?>
                                        <?php }else{ ?>
                                            <textarea name="<?= $q['id'] ?>" class="form-control answers-textarea"><?= !empty($answers[$q['id']]) ? $answers[$q['id']]['answer'] : '' ?></textarea>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php if($counter % 2 == 0 || $printedQuestions == count($questions)){ ?>
                            </div>
                            <?php } ?> 
                            <?php $counter++; ?>
                        <?php } ?> 
                        <div class="modal-footer">
                            <a href="<?=Router::url('/admin/leads/interview/home/');?>" class="btn btn-default">Annulla</a>
                            <button type="submit" class="btn btn-primary" id="saveAnswers" >Salva</button>
                        </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->element('Leads.modal_info_question'); ?>