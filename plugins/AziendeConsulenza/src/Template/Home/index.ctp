<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>
<script>
    $(document).ready(function(){


        $('#xls_export').click(function(){
            window.open(pathServer + 'aziende/home/index/xls','_self');
        });


    });

</script>

<section class="content-header">
    <h1>
        Clienti
        <small>Elenco clienti</small>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-group"></i> Clienti</a></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-aziende" class="box">
                <div class="box-table-aziende box-body box-table">
                    
                        <div id="pager-aziende" class="pager col-sm-6">
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
                            <a class="btn btn-app" data-toggle="modal" data-target="#myModalAzienda"><i class="fa fa-plus"></i>Nuovo</a>
                            <a class="btn btn-app" id="xls_export" title="Esporta tutti i clienti in formato xlsx per Excel"><img src="<?php echo Router::url('/'); ?>img/xls.png"></a>
                        </div>
                        
                        <table id="table-aziende" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Denominazione</th>
                                    <th>Nome</th>
                                    <th>Cognome</th>
                                    <th>Famiglia</th>
                                    <th>Telefono</th>
                                    <th>Sispac</th>
                                    <th style="min-width:218px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5">Non ci sono dati</td>
                                </tr>
                            </tbody>
                        </table>
                         <div id="pager-aziende" class="pager col-sm-6">
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
                </div>
            </div>
        </div>
    </div>
</section>

<?php echo $this->Element('Aziende.modale_nuova_azienda'); ?>
