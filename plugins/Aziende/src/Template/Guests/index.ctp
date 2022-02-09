<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 
?>
<script>
    var sede_id = '<?= $sede['id'] ?>';
</script>
<?php $this->assign('title', 'Diario di Bordo - Ospiti') ?>
<?= $this->Html->css('Diary.diary'); ?>
<?= $this->Html->script( 'Diary.diary', ['block']); ?>
<section class="content-header">
    <h1>
        <?=__c('Diario '.$azienda['denominazione'].' - '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune'].' ('.$sede['provincia'].')')?>
        <small>Gestione <?=__c('ospiti')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><?=__c('Diario di Bordo')?></li>
        <li><a href="<?=Router::url('/diary/home/index');?>"> <?=__c('Partner')?></a></li>
        <li><a href="<?=Router::url('/diary/home/partnerSedi/'.$azienda['id'].($role == 'admin' ? '/'.$sede['id_progetto'] : ''));?>"> <?=__c('Sedi')?></a></li>
        <li class="active"><?=__c('Ospiti')?></li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-guests-diary" class="box box-diary">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista ospiti per la sede '.$sede['indirizzo'].' '.$sede['num_civico'].', '.$sede['comune'].' ('.$sede['provincia'].') dell\'ente '.$azienda['denominazione'])?></h3>
                  <span hidden class="warning-out-of-spots">Posti esauriti</span>
                  <a href="<?=Router::url('/diary/guests/guest?sede='.$sede['id'].'&guest=""');?>" id="newGuest" class="btn btn-info btn-xs pull-right <?= !$creationEnabled ? 'disabled' : '' ?>" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-guests-diary box-body">

                    <div id="pager-guests-diary" class="pager col-sm-6">
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
                        <table id="table-guests-diary" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Codice</th>
                                    <th>Nome</th>
                                    <th>Cognome</th>
                                    <th>Data arrivo</th>
                                    <th width="70px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
