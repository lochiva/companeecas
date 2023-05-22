<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Index  (https://www.companee.it)
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

echo $this->Element('Document.include');

//echo "<pre>"; print_r($dcn); echo "</pre>";
//echo "<pre>"; print_r($drn); echo "</pre>";
//echo "<pre>"; print_r($documents); echo "</pre>";
echo $this->Html->script('Document.home-tree');
?>

<section class="content-header">
    <h1>
        Documentazione
        <small>gestione documentale</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="<?=Router::url('/document/home');?>">Documentazione</a></li>
        <li class="active">Gestione Documentale</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
<?php /*<div class="row">

    <div class="col-lg-9 ">
      <div class="info-box bg-teal box-icon-text">
        <span class="info-box-icon info-box-text"><?= __('Filtri') ?><br /><i class="fa fa-filter"></i></span>
        <div class="info-box-content">

          <?= $this->Form->create($documents,['method' => 'post']) ?>
          <form role="form">
          	<div class="col-md-5">
          		<div class="form-group">
		            <label>Cliente</label>
		            <?= $this->Form->select('id_client',$clients,['id' => 'id_client', 'class' => 'home form-control select2', 'style' => 'width: 100%', 'empty' => 'Tutti', 'required' => false]) ?>
		        </div>
          	</div>
	        <div class="col-md-5">
		        <div class="form-group">
		            <label>Progetto</label>
		            <?= $this->Form->select('id_project',$projects,['id' => 'id_project', 'class' => 'home form-control select2', 'style' => 'width: 100%', 'empty' => 'Tutti', 'required' => false]) ?>
		        </div>
          	</div>
          	<div class="col-md-2 btn-filter-center">
          	    <?= $this->Form->button( __('Cerca'), ['class' => 'btn btn-default ']); ?>

          	</div>
          </form>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-lg-3">
    	<div class="info-box bg-teal box-icon-text">
        	<span class="info-box-icon info-box-text">Nuovo<br /><i class="fa fa-plus"></i></span>
        	<div class="info-box-content">
            <div class="col-md-12 btn-filter-center text-center">
        		    <a class="btn btn-app" href="<?=Router::url('/document/home/add/0')?>" title="Crea nuovo documento">
                  <i class="fa fa-plus-square"></i> Nuovo doc
                </a>
            </div>
        	</div>
        </div>
    </div>



  <?php if($documentsNumber > 0){ ?>

  <?php foreach ($documents as $key => $doc) { ?>

    <div class="col-md-12">

      <div class="box box-primary collapsed-box">

        <div class="box-header with-border">
              <h3 class="box-title"><?=ucfirst($doc->title)?></h3>
              <div class="box-tools pull-right">
                <a data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
                <a data-widget="remove" class="btn btn-box-tool" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento">
                  <i class="fa fa-times"></i>
                </a>
              </div>
        </div>

        <div class="box-body ">
          <div class="row">

            <div class="col-md-3 col-xs-12 col-md-push-9 text-right">
        			<div class="btn-group ">
                <a class="btn btn-info" href="<?=Router::url('/document/home/view/' . $doc->id_document)?>" title="Visualizza documento" ><i class="fa fa-eye"></i></button>
                <a class="btn btn-info" href="<?=Router::url('/document/home/edit/' . $doc->id)?>" title="Modifica documento" ><i class="fa fa-pencil"></i></a>
                <a class="btn btn-info" href="<?=Router::url('/document/home/add/' . $doc->id_document)?>" title="Aggiungi figlio"><i class="fa fa-plus"></i></a>
              </div>
        		</div>

        		<div class="col-md-9 col-xs-12 col-md-pull-3">
        			<p class="info small col-lg-3 col-md-6"><b>Cliente:</b> <?php if(is_object($doc->client)){ echo $doc->client->name; }else{ echo "-"; } ?></p>
              <p class="info small col-lg-3 col-md-6"><b>Progetto:</b> <?php if(is_object($doc->project)){ echo $doc->project->name; }else{ echo "-"; } ?></p>
              <p class="info small col-lg-3 col-md-6">Dipendenze: <?=$dcn[$doc->id]?></p>
              <p class="info small col-lg-3 col-md-6">Revisioni: <?=$drn[$doc->id]?></p>
              <p class="info creato small row col-md-12">Data: <?=$doc->created?></p>
        		</div>

          </div>

        	<div id="accordion" class="box-group ">
        		<div class="panel box collapsed-box box-info ">
        			<div class="box-header with-border">
	              <h3 class="box-title">Sottodocumenti</h3>
	              <div class="box-tools pull-right">
	                <a data-widget="collapse" class="btn btn-box-tool show-my-child" data-parent="<?=$doc->id_document?>" title="Visualizza figli"><i class="fa fa-plus"></i></a>
	              </div>
	            </div>

              <div class="box-body" data-parent="<?=$doc->id_document?>">

                <!-- Da inserire in dinamico -->
                <div id="" class="box-group">
                  <div class="panel box ">
                    <div class="box-header with-border">
                      <h3 class="box-title">Doc 2 figlio di titolo 1</h3>
                      <div class="box-tools pull-right">
                        <a data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
                        <a data-widget="remove" class="btn btn-box-tool" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento"><i class="fa fa-times"></i></a>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-3 col-xs-12 col-md-push-9 text-right">
                          <div class="btn-group ">
                              <a class="btn btn-default" href="<?=Router::url('/document/home/view/' . $doc->id_document)?>" title="Visualizza documento" ><i class="fa fa-eye"></i></button>
                              <a class="btn btn-default" href="<?=Router::url('/document/home/edit/' . $doc->id)?>" title="Modifica documento"><i class="fa fa-pencil"></i></a>
                              <a class="btn btn-default" href="<?=Router::url('/document/home/add/' . $doc->id_document)?>" title="Aggiungi figlio"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="col-md-9 col-xs-12 col-md-pull-3">
                          <p class="info small col-lg-3 col-md-6"><b>Cliente:</b> <?php if(is_object($doc->client)){ echo $doc->client->name; }else{ echo "-"; } ?></p>
                          <p class="info small col-lg-3 col-md-6"><b>Progetto:</b> <?php if(is_object($doc->project)){ echo $doc->project->name; }else{ echo "-"; } ?></p>
                          <p class="info small col-lg-3 col-md-6">Dipendenze: <?=$dcn[$doc->id]?></p>
                          <p class="info small col-lg-3 col-md-6">Revisioni: <?=$drn[$doc->id]?></p>
                          <p class="info creato small row col-md-12">Data: <?=$doc->created?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="" class="box-group">
                  <div class="panel box ">
                    <div class="box-header with-border">
                      <h3 class="box-title">Doc 2 figlio di titolo 1</h3>
                      <div class="box-tools pull-right">
                        <a data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
                        <a data-widget="remove" class="btn btn-box-tool" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento"><i class="fa fa-times"></i></a>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-3 col-xs-12 col-md-push-9 text-right">
                          <div class="btn-group ">
                              <a class="btn btn-default" href="<?=Router::url('/document/home/view/' . $doc->id_document)?>" title="Visualizza documento" ><i class="fa fa-eye"></i></button>
                              <a class="btn btn-default" href="<?=Router::url('/document/home/edit/' . $doc->id)?>" title="Modifica documento"><i class="fa fa-pencil"></i></a>
                              <a class="btn btn-default" href="<?=Router::url('/document/home/add/' . $doc->id_document)?>" title="Aggiungi figlio"><i class="fa fa-plus"></i></a>
                          </div>
                        </div>
                        <div class="col-md-9 col-xs-12 col-md-pull-3">
                          <p class="info small col-lg-3 col-md-6"><b>Cliente:</b> <?php if(is_object($doc->client)){ echo $doc->client->name; }else{ echo "-"; } ?></p>
                          <p class="info small col-lg-3 col-md-6"><b>Progetto:</b> <?php if(is_object($doc->project)){ echo $doc->project->name; }else{ echo "-"; } ?></p>
                          <p class="info small col-lg-3 col-md-6">Dipendenze: <?=$dcn[$doc->id]?></p>
                          <p class="info small col-lg-3 col-md-6">Revisioni: <?=$drn[$doc->id]?></p>
                          <p class="info creato small row col-md-12">Data: <?=$doc->created?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Fine contenuto dinamico -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>

  <?php }else{ ?>
    <div class="alert alert-danger">Non ci sono documenti</div>
  <?php } ?>

</div> */?>
<div class="box box-info">
    <div class="box-header with-border">
          <i class="fa fa-folder-open"></i><h3 class="box-title">Documenti</h3>
          <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" id="refresh-tree"><i class="fa fa-refresh"></i></button>
              </div>
           
    </div>

    <div class="box-body" style=" min-height:550px;">
      <div class="row">
          <div class="col-md-5">
              <div class="input-group margin10-bot">
                <input id="plugins4_q" type="text" class="form-control" placeholder="Cerca documento" />
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                
              </div>
          </div>
          <div class="col-md-7">
            
              <div class="btn-group margin10-bot">
                <button class="btn btn-default" id="open_all">Apri albero</button>
                <button class="btn btn-default" id="close_all">Chiudi albero</button>
              </div>
              <a type="button" class="btn btn-info pull-right margin10-bot" href="<?=Router::url('/document/home/add/0')?>" >
                 <i class="fa fa-plus"></i>&nbsp;&nbsp; Nuovo Documento
              </a>
          </div>
        </div>
        <hr style="margin:0px;" />
        <div class="row">
          <div class="col-md-5">
               <div id="tree2" class=""></div>
          </div>
          <div id="preview-container" class="col-md-7 back-white">
               <div id="preview" class="row">
                   <div class="col-sm-3">
                     <label>Cliente:</label><p class="cliente data-view"></p>
                    </div>
                    <div class="col-sm-3">
                     <label>Progetto:</label><p class="project data-view"></p>
                    </div>
                    <div class="col-sm-2">
                     <label>Revisioni:</label><p class="revision data-view"></p>
                    </div>
                    <div class="col-sm-4">
                     <label>Tags:</label><p class="tags data-view"></p>
                    </div>
                    <div class="col-sm-9">
                        <label>Titolo:</label><p class="title data-view"></p>
                    </div>
                    <div class="col-sm-3">
                      <label>Azioni: </label><br />
                      <div class="btn-group ">
                          <button class="btn btn-default data-id_document action-document" href="<?=Router::url('/document/home/view/')?>" title="Visualizza documento" ><i class="fa fa-eye"></i></button>
                          <button class="btn btn-default data-id action-document" href="<?=Router::url('/document/home/edit/')?>" title="Modifica documento"><i class="fa fa-pencil"></i></button>
                          <button class="btn btn-default data-id_document action-document" href="<?=Router::url('/document/home/add/')?>" title="Aggiungi figlio"><i class="fa fa-plus"></i></button>
                       </div>
                    </div>
                 <div class="col-sm-12 ">
                   <div class="panel panel-default">
                      <div class="panel-body" style="max-height:400px; min-height:300px; overflow: auto; ">...</div>
                  </div>
                  <button class="btn btn-primary data-id_document generate" data-toggle="modal" data-target="#myModalGenerazione" title="Genera documentazione">Genera documentazione</button>
                  <button class="btn btn-danger data-id action-document pull-right delete-doc" href="<?=Router::url('/document/home/delete/')?>" title="Elimina documento">Elimina documento</button>
                 </div>
               </div>
          </div>
        </div>
      </div>
    </div>
</section>
<?= $this->Element('Document.modale_generazione'); ?>
