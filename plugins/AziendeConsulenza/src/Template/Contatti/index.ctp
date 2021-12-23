<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>
<?php //echo "<pre>"; print_r($sede); echo "</pre>"; ?>
<?php //echo "<pre>"; print_r($this->request); echo "</pre>"; ?>

<section class="content-header">
    <h1>
        Clienti
        <?php if(is_object($sede)){ ?>
            <small>gestione contatti <?php echo $sede->azienda->denominazione . " - " . $sede->indirizzo . " " . $sede->num_civico; ?></small>
        <?php }else{ ?>
            <?php if(is_object($azienda)){ ?>
                <small>gestione contatti <?=$azienda->denominazione?></small>
            <?php }else{ ?>
                <small>gestione contatti aziendali</small>
            <?php } ?>
        <?php } ?>
        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/aziende/home');?>"><i class="fa fa-group"></i> Clienti</a></li>
        <li class="active">Gestione Contatti</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-sedi" class="box">
                <div class="box-table-contatti box-body">
                    
                        <div id="pager-contatti" class="pager col-sm-6">
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
                        
                        <div class="col-sm-6" id="box-general-action">
                            <a class="btn btn-app" data-toggle="modal" data-target="#myModalContatto"><i class="fa fa-plus"></i>Nuovo</a>
                            <a class="btn btn-app" href="<?=$this->request->env('HTTP_REFERER');?>"><i class="fa fa-mail-reply"></i>Indietro</a>
                        </div>
                        
                        <table id="table-contatti" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Cognome</th>
                                    <th>Nome</th>
                                    <th>Ruolo</th>
                                    <th>Telefono</th>
                                    <th>Cellulare</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('modale_nuovo_contatto'); ?>
