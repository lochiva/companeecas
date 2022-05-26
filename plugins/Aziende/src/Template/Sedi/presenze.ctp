<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 
?>
<script>
    var next_sede = '<?= $nextSede ?>';
</script>
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
                        <form id="formPresenze" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <datepicker :language="datepickerItalian" format="dd/MM/yyyy" :clear-button="false" :monday-first="true" input-class="form-control" 
                                        typeable="true" id="inputDate" ref="inputDate" v-model="date" @input="changedDate()"></datepicker>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label">Totale presenze del giorno</label>
                                    <span class="count-presenze">{{ count_presenze_day }}</span>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label">Totale presenze del mese</label>
                                    <span class="count-presenze">{{ count_presenze_month }}</span>
                                </div>
                                <div class="clear-both"></div>
                            </div>
                            <div v-if="guests.length > 0">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label presenze-label" for="checkAllGuests">Tutti gli ospiti</label>
                                        <input type="checkbox" id="checkAllGuests" v-model="check_all_guests" class="check-presenze-all-guests" @change="checkAllGuests()">
                                    </div>
                                    <div class="clear-both"></div>
                                </div>
                                <div v-for="guest in guests" class="form-group">
                                    <div class="col-sm-3 div-label-guest-presenza">
                                        <div class="col-sm-2 div-label-guest-presenza">
                                            <input type="checkbox" :id="'inputGuest'+guest.id" v-model="guest.presente" class="check-presenza">
                                        </div>
                                        <div class="col-sm-10 div-label-guest-presenza" :class="{'warning-presenze': guest.warning_presenze, 'danger-presenze': guest.danger_presenze}">
                                            <span class="icon-guest-info" @click="openModalInfoGuest(guest)"><i class="fa fa-info-circle"></i></span>
                                            <label class="control-label presenze-label" :for="'inputGuest'+guest.id">
                                                {{guest.name}} {{guest.surname}}
                                                <span v-if="guest.suspended">(sospeso)</span>
                                                <span v-if="guest.not_saved" class="text-small-presenza">(da salvare)</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" v-model="guest.note" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="col-sm-12">Non ci sono ospiti per questa sede nella data selezionata</p>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <div v-if="guests.length > 0">
                            <button type="button" class="btn btn-success pull-right" id="savePresenzeNext" @click="save(true)"
                                :disabled="!next_sede" :title="noNextSedeMessage">
                                Salva e prossimo
                            </button>
                            <button type="button" class="btn btn-primary pull-right btn-save-presenze" id="savePresenze" @click="save(false)">
                                Salva
                            </button>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-success pull-right" id="nextSede" @click="next()">
                                Prossimo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?= $this->element('Aziende.modal_guest_info') ?>
</div>
