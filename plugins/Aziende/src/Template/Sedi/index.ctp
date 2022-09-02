<?php
use Cake\Routing\Router;

$user = $this->request->session()->read('Auth.User');
?>
<script>
    var role = '<?= $user['role'] ?>';
    var ente_tipo = <?= $azienda->id_tipo ?>;
</script>
<?php echo $this->Element('Aziende.include'); ?>
<?php //echo "<pre>"; print_r($azienda); echo "</pre>"; ?>
<?= $this->Html->script( 'Aziende.sedi' ); ?>
<section class="content-header">
    <h1>
        Strutture
        <?php if(is_object($azienda)){ ?>
            <small>gestione strutture <?php echo $azienda->denominazione; ?></small>
        <?php }else{ ?>
            <small>gestione strutture</small>
        <?php } ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ragioneria') { ?>
        <li><a href="<?=Router::url('/aziende/home');?>">Enti</a></li>
        <?php } ?>
        <li class="active">Gestione strutture</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-sedi" class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-list-ul"></i>
                  <h3 class="box-title">Elenco delle strutture</h3>
                  <div id="box-general-action"  class=" pull-right">
                    <?php if ($azienda->id_tipo == 1) { ?>
                        <a class="btn btn-primary btn-xs pull-right" href="<?= Router::url('/aziende/agreements/index/' . $azienda->id)?>" style="margin-left: 10px;"><i class="fa fa-file-text-o"></i> Convenzioni</a>
                    <?php } ?>
                    <?php if ($user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ente_ospiti') { ?>
                    <a class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#myModalSede" data-backdrop="false" data-keyboard="false" onclick="clearModale()" style="margin-left:10px"><i class="fa fa-plus"></i> Nuovo</a>
                    <?php } ?>
                    <a href="<?=$this->request->env('HTTP_REFERER');?>" class="pull-right" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i> indietro </a>
                  </div>
                </div>
                <div class="box-table-sedi box-body">

                        <div id="pager-sedi" class="pager col-sm-6">
                            <form>
                                <i class="first glyphicon glyphicon-step-backward"></i>
                                <i class="prev glyphicon glyphicon-backward"></i>
                                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                <i class="next glyphicon glyphicon-forward"></i>
                                <i class="last glyphicon glyphicon-step-forward"/></i>
                                <select class="pagesize">
                                    <option selected="selected" value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                            </form>
                        </div>

                        <table id="table-sedi" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">Codice centro</th>
                                    <th width="10%">Tipologia ministero</th>
                                    <?php if ($azienda->id_tipo == 1) { ?>
                                    <th width="12%">Tipologia capitolato</th>
                                    <?php } ?>
                                    <th width="15%">Indirizzo</th>
                                    <th width="7%">Civico</th>
                                    <th width="7%">Cap</th>
                                    <th width="15%">Comune</th>
                                    <th width="12%">Provincia</th>
                                    <th width="10%">
                                        <span data-toggle="tooltip" data-placement="bottom" title="Posti occupati / capienza effettiva" class="table-label">
                                            Posti
                                        </span>
                                    </th>
                                    <th style="min-width:110px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('modale_nuova_sede'); ?>
