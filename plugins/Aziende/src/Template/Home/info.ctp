<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Aziende.include'); ?>
<?= $this->Html->script( 'Aziende.aziende' ); ?>
<?= $this->Html->script( 'Aziende.aziende_info', ['block' => 'script']); ?>
<?= $this->Html->script( 'Aziende.fornitori' ); ?>
<?= $this->Html->script( 'Aziende.clienti' ); ?>
<script>
    var id_azienda = <?= $azienda->id ?>;
</script>
<section class="content-header">
    <h1>
        <?=__c('Enti')?>
        <small>Gestione <?=__c('enti')?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/aziende/home');?>"><?=__c('Enti')?></a></li>
        <li class="active">Gestione <?=__c('Enti')?></li>
    </ol>
</section>

<section class="content">
    <div class="row">

        <?= $this->element('Remarks.modal_remarks_by_id'); ?>
        <?= $this->element('AttachmentManager.modal_attachment'); ?>

        <div class="col-md-12">

            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-building text-aqua"></i> <?=$azienda->denominazione?></h3>
                    <div class="box-tools pull-right">
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                        <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
                    </div>
                </div><!-- /.box-header -->



                <div class="box-body">
                  <div class="row">
                    <div class="col-md-4">
                        <dl>

                            <dd><i class="fa fa-building"></i> <b>Denominazione:</b> <?=$azienda->denominazione?></dd>
                            <!--<dd><i class="fa fa-user"></i> <b>Nome:</b> <?php if($azienda->nome){echo h($azienda->nome);}else{echo "-";}?> </dd>-->
                            <!--<dd><i class="fa fa-user"></i> <b>Cognome:</b> <?php if($azienda->cognome){echo h($azienda->cognome);}else{echo "-";}?></dd>-->
                            <dd><i class="fa fa-envelope"></i> <b>Pec amministrativa:</b> <?php if($azienda->pec){echo h($azienda->pec);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-envelope"></i> <b>Pec atti commissione:</b> <?php if($azienda->pec){echo h($azienda->pec_commissione);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-user"></i> <b>Referente 1:</b> <?php if($azienda->referente_1){echo h($azienda->referente_1);}else{echo "-";}?> </dd>
                            <dd><i class="fa fa-user"></i> <b>Referente 2:</b> <?php if($azienda->referente_2){echo h($azienda->referente_2);}else{echo "-";}?> </dd>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd><i class="fa fa-phone"></i> <b>Telefono:</b> <?php if($azienda->telefono){echo h($azienda->telefono);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-mobile"></i> <b>Cellulare:</b> <?php if($azienda->fax){echo h($azienda->fax);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-envelope"></i> <b>Email Info:</b> <?php if($azienda->email_info){echo h($azienda->email_info);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-globe"></i> <b>Sito:</b> <?php if($azienda->sito_web){echo '<a target="_blank" href="http://'.h($azienda->sito_web).'">'.h($azienda->sito_web).'</a>';}else{echo "-";}?></dd>
                            <dd><i class="fa fa-circle"></i> <b>Tipologia ente:</b> <?php if($azienda->tipo){echo h($azienda->tipo->name);}else{echo "-";}?></dd>
                            <!--<dd><i class="fa fa-envelope"></i> <b>Contabilità:</b> <?php if($azienda->email_contabilita){echo h($azienda->email_contabilita);}else{echo "-";}?></dd>-->
                            <!--<dd><i class="fa fa-envelope"></i> <b>Solleciti:</b> <?php if($azienda->email_solleciti){echo h($azienda->email_solleciti);}else{echo "-";}?></dd>-->
                        </dl>
                    </div>
                    <!--
                    <div class="col-md-4">
                        <dl>
                            <dd><i class="fa fa-globe"></i> <b>Codice Paese:</b> <?php if($azienda->cod_paese){echo h($azienda->cod_paese);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-briefcase"></i> <b>Partita Iva:</b> <?php if($azienda->piva){echo h($azienda->piva);}else{echo "-";}?></dd>
                            <dd><i class="fa fa-address-card"></i> <b>Codice Fiscale:</b> <?php if($azienda->cf){echo h($azienda->cf);}else{echo "-";}?></dd>
							<dd><i class="fa fa-address-card-o"></i> <b>Cod. Destinatario:</b> <?php if($azienda->pa_codice){echo h($azienda->pa_codice);}else{echo "-";}?></dd>
							<div class="row">
                              <div class="col-sm-4">
                                <dt>Cliente</dt>
                                <dd><?php if($azienda->cliente){echo "Si";}else{echo "No";}?></dd>
                              </div>
                              <div class="col-sm-4">
                                <dt>Fornitore</dt>
                                <dd><?php if($azienda->fornitore){echo "Si";}else{echo "No";}?></dd>
                              </div>
                              <div class="col-sm-4">
                                <dt>Interno</dt>
                                <dd><?php if($azienda->interno){echo "Si";}else{echo "No";}?></dd>
                              </div>
                            </div>
                        </dl>
                    </div>
                    -->
                  </div>
                </div><!-- /.box-body -->


                <div class="box-footer clearfix">

                    <a class="btn btn-sm btn-default btn-flat pull-right edit"  data-id="<?= $azienda->id ?>"data-toggle="modal"
                       data-target="#myModalAzienda" data-backdrop="false" data-keyboard="false" data-parentTab="#click_tab_1" >Modifica</a>

                </div><!-- /.box-footer -->

              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-home text-aqua"></i> Strutture</h3>
                    <div class="box-tools pull-right">
                        <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-content">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Codice centro</th>
                                    <th>Tipologia ministero</th>
                                    <?php if ($azienda->id_tipo == 1) { ?>
                                        <th>Tipologia capitolato</th>
                                    <?php } ?>
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
                                        <tr style="position:relative;">
                                            <td><?=h($sede->code_centro)?></td>
                                            <td><span class="label sediTipiMinisteroBG-<?=$sede->id_tipo_ministero?> pull-left"><?=h($sede['stm']['name'])?></span></td>
                                            <?php if ($azienda->id_tipo == 1) { ?>
                                                <td><span class="label sediTipiCapitolatoBG-<?=$sede->id_tipo_capitolato?> pull-left"><?=h($sede['stc']['name'])?></span></td>
                                            <?php } ?>
                                            <td><?=h($sede->indirizzo)?></td>
                                            <td><?=h($sede->num_civico)?></td>
                                            <td><?=h($sede->cap)?></td>
                                            <td><?=h($sede['c']['des_luo'])?></td>
                                            <td><?=h($sede['p']['des_luo'])?></td>
                                            <td width="50px" style="padding-left: 0px; padding-right: 0px">
                                              <div class="tools-hover">
                                                <a class="edit pointer" data-toggle="modal"  data-parentTab="#click_tab_2" data-childTab="#click_subtab_sede_<?=$sede->id?>"
                                                data-id="<?= $azienda->id ?>" data-target="#myModalAzienda" data-backdrop="false" data-keyboard="false"><i data-toggle="tooltip" data-placement="left" title="Modifica struttura" class="glyphicon glyphicon-pencil text-red"></i></a>
                                                &nbsp;
                                                <a class="delete-sede pointer" data-id="<?= $sede->id ?>" data-toggle="tooltip" data-placement="left" title="Cancella struttura"><i class="glyphicon glyphicon-trash pull-right text-red"></i></a>
                                              </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="6">Non ci sono strutture inserite.</td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                

                <div class="box-footer clearfix">

                    <a class="btn btn-sm btn-default btn-flat pull-right edit" data-parentTab="#click_tab_2" data-childTab=".add-tab-sede"
                    data-id="<?= $azienda->id ?>"data-toggle="modal" data-target="#myModalAzienda" data-backdrop="false" data-keyboard="false">Inserisci nuova struttura</a>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-address-book-o text-blue"></i> Contatti</h3>
                        <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
                            <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
                        </div>
                </div><!-- /.box-header -->
                <div class="box-body contatti-list">
                    <ul class="products-list product-list-in-box">

                        <?php if(isset($contatti) && !empty($contatti)){ ?>

                            <?php foreach ($contatti as $key => $contatto) { ?>

                                <li class="item">

                                    <div class="product-img icon-contatti">
                                        <!--<img alt="Product Image" src="dist/img/default-50x50.gif">-->
                                        <!--<i class="ion ion-ios-people-outline"></i>-->
                                        <i class="fa fa-user"></i>
                                    </div>

                                    <div class="product-info" style="position:relative">
                                        <a class="product-title edit pointer" data-toggle="modal"  data-parentTab="#click_tab_3" data-childTab="#click_subtab_contatto_<?=$contatto->id?>"
                                            data-id="<?= $azienda->id ?>" data-target="#myModalAzienda" data-backdrop="false" data-keyboard="false">
                                            <?=h($contatto->cognome . " " . $contatto->nome)?>
                                            <span class="label ruoliBG-<?=$contatto->id_ruolo?> pull-right">
                                              <?=h(!empty($contatto->ruolo->ruolo)? $contatto->ruolo->ruolo : '')?></span>
                                        </a>
                                        <span class="product-description">
                                            <?php if(!empty($contatto->sede)): ?>
                                              Struttura di: <?=h($contatto->sede->indirizzo . " " . $contatto->sede->num_civico)?>
                                            <?php else: ?>
                                              Struttura non impostata.
                                            <?php endif ?>
                                        </span>
                                        <div class="tools-hover " style="margin-top:-20px">
                                            <a class="edit pointer" data-toggle="modal"  data-parentTab="#click_tab_3" data-childTab="#click_subtab_contatto_<?=$contatto->id?>"
                                            data-id="<?= $azienda->id ?>" data-target="#myModalAzienda" data-backdrop="false" data-keyboard="false"><i data-toggle="tooltip" data-placement="left" title="Modifica contatto" class="text-red glyphicon glyphicon-pencil"></i></a>
                                            &nbsp;
                                            <a class="delete-contatto pointer" data-id="<?= $contatto->id ?>" data-toggle="tooltip" data-placement="left" title="Cancella contatto"><i class="text-red glyphicon glyphicon-trash pull-right"></i></a>
                                        </div>

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
                    <a class="btn btn-sm btn-default btn-flat pull-right edit" data-parentTab="#click_tab_3" data-childTab=".add-tab-contatto"
                    data-id="<?= $azienda->id ?>"data-toggle="modal" data-target="#myModalAzienda" data-backdrop="false" data-keyboard="false">Inserisci nuovo Contatto</a>
                </div><!-- /.box-footer -->
            </div>
        </div>
        <div class="col-md-6">
            <?= $this->element('AttachmentManager.box_attachment'); ?>
        </div>
    </div>


</section>
<?php echo $this->Element('Aziende.modale_nuova_azienda'); ?>
<?php echo $this->Element('Aziende.modale_nuova_invoicepayable'); ?>
<?php echo $this->Element('Aziende.modale_nuova_invoicepayable_attiva'); ?>
<?php //echo $this->Element('Crm.modale_nuova_offers'); ?>
<?php //echo $this->Element('Leads.modal_interview'); ?>

