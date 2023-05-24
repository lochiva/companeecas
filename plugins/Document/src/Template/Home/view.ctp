<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    View  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
use Cake\Core\Configure;

echo $this->Element('include');

//echo "<pre>"; print_r($this->request->referer()); echo "</pre>";

?>


<section class="content-header">
    <h1>
        Gestione documentale
        <small><?=h($document->title)?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=Router::url('/document/home');?>">Documenti</a></li>
        <li class="active">Visualizza</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-md-4" >
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-info-circle"></i><h3 class="box-title">Informazioni</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                         <label>Data Creazione:<br /><span class="badge bg-light-blue"><?=$document->created?></span> </label>
                    </div>
                    <div class="col-md-6">
                        <label >Data Validità: <br /><span class="badge bg-light-blue"><?php if($document->created != $document->modified){echo $document->modified;}else{echo"Valido";}?></span> </a> </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8" >
         <div class="box box-info">
            <?= $this->Form->create($document) ?>
            <div class="box-header with-border">
                <i class="fa fa-refresh"></i><h3 class="box-title"><?= __('Aggiorna il documento') ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                     <div class="col-md-12">

                         <div class="medium select-add">
                            <div class="input select required">
                                <label for="id_client">Cliente</label>
                                <p class="data-view"><?php if(is_object($document->azienda)){ echo h($document->azienda->denominazione); }else{ echo "Sconosciuto"; }?></p>
                            </div>
                             <div style="clear: both;"></div>
                        </div>
                        <div class="medium select-add">
                            <div class="input select required">
                                <label for="id_client">Progetto</label>
                                <p class="data-view"><?php if(is_object($document->ordine)){ echo h($document->ordine->name); }else{ echo "Sconosciuto"; }?></p>
                            </div>
                            <div style="clear: both;"></div>
                        </div>

                        <div class="input text required">
                            <label for="id_client">Titolo del Documento</label>
                            <p class="data-view"><?=h($document->title)?></p>
                        </div>

                        <div class="input textarea">
                            <label for="id_client">Contenuto</label>
                            <div class="data-view"><?=$document->text1?></div>
                        </div>



                    </div>
                </div>
            </div>
            <div class="box-footer">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="btn-form-add-edit">
                           <?php if($document->last_saved == 1){ ?>
                                <a class="button btn btn-primary" href="<?=Router::url('/document/home/edit/' . $document->id)?>">Modifica</a>
                           <?php } ?>


                        </div>
                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
    <div class="col-md-12" >
        <a class="button" href="<?=$this->request->referer()?>">Torna indietro</a>
    </div>
  </div>
</section>
