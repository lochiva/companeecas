<?php
use Cake\Routing\Router;

$role = $this->request->session()->read('Auth.User.role'); 
?>
<script>
    var sede_id = '<?= $sede['id'] ?>';
</script>
<?php $this->assign('title', 'Notifiche') ?>
<?= $this->Html->css('Aziende.guests'); ?>
<?= $this->Html->script('Aziende.guests', ['block']); ?>
<section class="content-header">
    <h1>
        Notifiche ospiti
        <small>Gestione notifiche ospiti
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Notifiche ospiti</li>
    </ol>
</section>

<?= $this->Flash->render() ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-guests" class="box box-guests">
                <div class="box-header with-border">
                  <i class="fa fa-list-alt"></i>
                  <h3 class="box-title"><?=__c('Lista notifiche ospiti')?></h3>
                  <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                </div>
                <div class="box-table-guests box-body">

                    <div id="pager-guests-notifications" class="pager col-sm-6">
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
                        <table id="table-guests-notifications" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Ente</th>
                                    <th>Struttura</th>
                                    <th>Ospite</th>
                                    <th>Operatore</th>
                                    <th>Operazione</th>
                                    <th class="filter-select filter-done">Gestita</th>
                                    <th>Operatore gestione</th>
                                    <th>Data gestione</th>
                                    <th width="70px" class="filter-false" data-sorter="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
