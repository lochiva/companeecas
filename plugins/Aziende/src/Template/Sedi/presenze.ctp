<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 
?>
<?php $this->assign('title', 'Presenze') ?>
<?= $this->Html->css('Aziende.aziende'); ?>
<?= $this->Html->script('Aziende.vue-presenze', ['block' => 'script-vue']); ?>

<section class="content-header">
    <h1>
        <?=__c('Ente '.$azienda['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune']['des_luo'].' ('.$sede['provincia']['s_prv'].')')?>
        <small>Gestione <?=__c('presenze')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <?php if ($role == 'admin') { ?>
        <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
        <?php } ?>
        <li><a href="<?=Router::url('/aziende/sedi/index/'.$azienda['id']);?>">Strutture</a></li>
        <li class="active">Gestione presenze</li>
    </ol>
</section>

<div id='app-presenze'>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-presenze" class="box box-presenze">
                    <div class="box-header with-border">
                        <i class="fa fa-calendar"></i>
                        <h3 class="box-title"><?=__c('Presenze per la struttura')?></h3>
                        <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                    </div>
                    <div class="box-body">
                        <form id="formPresenze">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <datepicker :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="false" :monday-first="true" input-class="form-control" 
                                        id="inputDate" v-model="date" @closed="loadGuests()"></datepicker>
                                </div>
                                <div class="clear-both"></div>
                            </div>
                            <div v-if="guests.length > 0">
                                <div v-for="guest in guests" class="form-group">
                                    <div class="col-sm-12">
                                        <input type="checkbox" :id="'inputGuest'+guest.id" v-model="guest.presente" class="check-presenza">
                                        <label class="control-label" :for="'inputGuest'+guest.id">
                                            {{guest.name}} {{guest.surname}}
                                            <span v-if="guest.suspended">(sospeso)</span>
                                            <span v-if="guest.not_saved" class="text-small-presenza">(da salvare)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="col-sm-12">Non ci sono ospiti per questa sede nella data selezionata</p>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-primary pull-right" id="savePresenze" @click="save()">Salva</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
