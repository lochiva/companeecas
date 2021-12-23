<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>
<?php //echo "<pre>"; print_r($azienda); echo "</pre>"; ?>

<section class="content-header">
    <h1>
        Clienti
        <?php if(is_object($azienda)){ ?>
            <small>gestione sedi <?php echo $azienda->denominazione; ?></small>
        <?php }else{ ?>
            <small>gestione sedi aziendali</small>
        <?php } ?>
        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/aziende/home');?>"><i class="fa fa-group"></i> Clienti</a></li>
        <li class="active">Gestione sedi</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-sedi" class="box">
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
                        
                        <div class="col-sm-6" id="box-general-action">
                            <a class="btn btn-app" data-toggle="modal" data-target="#myModalSede"><i class="fa fa-plus"></i>Nuova</a>
                            <a class="btn btn-app" href="<?=Router::url('/aziende/')?>"><i class="fa fa-mail-reply"></i>Indietro</a>
                        </div>
                        
                        <table id="table-sedi" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Indirizzo</th>
                                    <th>Civico</th>
                                    <th>Cap</th>
                                    <th>Comune</th>
                                    <th>Provincia</th>
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

<?php echo $this->Element('modale_nuova_sede'); ?>
