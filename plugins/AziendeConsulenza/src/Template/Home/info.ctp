<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>

<section class="content-header">
    <h1>
        Clienti
        <small>Visualizza cliente</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo Router::url('/aziende/home');?>"><i class="fa fa-group"></i>Clienti</a></li>
        <li class="active">Visualizza cliente</li>
    </ol>
</section>

<section class="content">
    <div class="row">

        <div class="col-md-12">

            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title"><?=$azienda->denominazione?></h3>
                    <div class="box-tools pull-right">
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
                    </div>
                </div><!-- /.box-header -->



                <div class="box-body">
                    <div class="col-md-4">
                        <dl>
                            <dt>Denominazione</dt>
                            <dd id = "denominazione-azienda"><?=$azienda->denominazione?></dd>
                            <dt>Nome</dt>
                            <dd><?php if($azienda->nome){echo $azienda->nome;}else{echo "-";}?></dd>
                            <dt>Cognome</dt>
                            <dd><?php if($azienda->cognome){echo $azienda->cognome;}else{echo "-";}?></dd>
                            <dt>Famiglia</dt>
                            <dd><?php if($azienda->famiglia){echo $azienda->famiglia;}else{echo "-";}?></dd>

                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dt>Telefono</dt>
                            <dd><?php if($azienda->telefono){echo $azienda->telefono;}else{echo "-";}?></dd>
                            <dt>Fax</dt>
                            <dd><?php if($azienda->fax){echo $azienda->fax;}else{echo "-";}?></dd>
                            <dt>Email Info</dt>
                            <dd><?php if($azienda->email_info){echo $azienda->email_info;}else{echo "-";}?></dd>
                            <dt>Email Contabilità</dt>
                            <dd><?php if($azienda->email_contabilita){echo $azienda->email_contabilita;}else{echo "-";}?></dd>
                            <dt>Email Solleciti</dt>
                            <dd><?php if($azienda->email_solleciti){echo $azienda->email_solleciti;}else{echo "-";}?></dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dt>Codice Paese</dt>
                            <dd><?php if($azienda->cod_paese){echo $azienda->cod_paese;}else{echo "-";}?></dd>
                            <dt>Partita Iva</dt>
                            <dd><?php if($azienda->piva){echo $azienda->piva;}else{echo "-";}?></dd>
                            <dt>Codice Fiscale</dt>
                            <dd><?php if($azienda->cf){echo $azienda->cf;}else{echo "-";}?></dd>
                            <dt>Codice Sispac</dt>
                            <dd><?php if($azienda->cod_sispac){echo $azienda->cod_sispac;}else{echo "-";}?></dd>
                            <dt>Cliente</dt>
                            <dd><?php if($azienda->cliente){echo "Si";}else{echo "No";}?></dd>
                            <dt>Fornitore</dt>
                            <dd><?php if($azienda->fornitore){echo "Si";}else{echo "No";}?></dd>
                        </dl>
                    </div>
                </div><!-- /.box-body -->


                <div class="box-footer clearfix">
                    <!--
                    <a class="btn btn-sm btn-info btn-flat pull-left" href="javascript::;">Place New Order</a>
                    <a class="btn btn-sm btn-default btn-flat pull-right" href="javascript::;">View All Orders</a>
                    -->
                </div><!-- /.box-footer -->

              </div>
        </div>
        <div class="col-md-8">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Sedi</h3>
                    <div class="box-tools pull-right">
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Indirizzo</th>
                                    <th>Civico</th>
                                    <th>Cap</th>
                                    <th>Comune</th>
                                    <th>Provincia</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if(isset($sedi) && !empty($sedi)){ ?>
                                    <?php foreach ($sedi as $key => $sede) { ?>
                                        <tr>
                                            <td><?=$sede->tipoSede->tipo?></td>
                                            <td><?=$sede->indirizzo?></td>
                                            <td><?=$sede->num_civico?></td>
                                            <td><?=$sede->cap?></td>
                                            <td><?=$sede->comune?></td>
                                            <td><?=$sede->provincia?></td>
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="5">Non ci sono sedi inserite.</td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->

                <div class="box-footer clearfix">

                    <!--<a class="btn btn-sm btn-info btn-flat pull-left" href="javascript::;">Place New Order</a>-->
                    <a class="btn btn-sm btn-default btn-flat pull-right" href="<?php echo Router::url('/aziende/sedi/index/' . $idAzienda)?>">Gestione sedi</a>

                </div><!-- /.box-footer -->
              </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Contatti</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                            <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
                        </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">

                        <?php if(isset($contatti) && !empty($contatti)){ ?>

                            <?php foreach ($contatti as $key => $contatto) { ?>

                                <li class="item">

                                    <div class="product-img icon-contatti">
                                        <!--<img alt="Product Image" src="dist/img/default-50x50.gif">-->
                                        <!--<i class="ion ion-ios-people-outline"></i>-->
                                        <i class="fa fa-user"></i>
                                    </div>

                                    <div class="product-info">
                                        <a class="product-title" href="javascript::;">
                                            <?=$contatto->cognome . " " . $contatto->nome?>
                                            <span class="label label-warning pull-right"><?=$contatto->ruolo->ruolo?></span>
                                        </a>
                                        <span class="product-description">
                                            Sede di: <?=$contatto->sede->indirizzo . " " . $contatto->sede->num_civico?>
                                        </span>
                                    </div>
                                </li><!-- /.item -->

                            <?php } ?>

                        <?php }else{ ?>
                            <li class="item">
                                <span class="product-description">Non ci sono contatti inseriti.</span>
                            </li>
                        <?php } ?>

                    </ul>
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                    <!--<a class="uppercase" href="javascript::;">View All Products</a>-->
                    <a class="btn btn-sm btn-default btn-flat pull-right" href="<?php echo Router::url('/aziende/contatti/index/azienda/' . $idAzienda)?>">Gestione contatti</a>
                </div><!-- /.box-footer -->
              </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-4">
            <div class="box box-default  border-purple">
              <div class="box-header with-border ">
                <h3 class="box-title">Rating Crediti</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <?php if(!empty($rating) && $rating != null): ?>
                  <h4><span class='rating-crediti-azienda <?= $rating ?> '><?= $rating ?></span></h4>
                  <?php $creditiDisabled = '' ?>
                <?php else: ?>
                  <h4><span class='rating-crediti-azienda alert-info'>ND</span></h4>
                  <?php $creditiDisabled = 'disabled' ?>
                <?php endif ?>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-center">
                  <!--<a class="uppercase" href="javascript::;">View All Products</a>-->
                  <button class="btn btn-sm btn-default btn-flat pull-right action-credit" value="<?= $idAzienda ?>" <?= $creditiDisabled ?> >Gestione crediti</button>
              </div><!-- /.box-footer -->
            </div>
            <!-- /.box -->
          </div>
    </div>
</section>
<?php echo $this->Element('Crediti.modale_comunicazione'); ?>
