<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

echo $this->Element('include');

?>

<section class="content-header">
    <h1>
        Gestione documentale
        <small>Modifica di <b><?=h($document->title)?></b></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=Router::url('/document/home');?>">Documenti</a></li>
        <li class="active">Modifica</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="row">
    <?= $this->Form->create($document) ?>
    <?php /* <div class="col-md-4" >
        <div class="box box-warning">
            <div class="box-header with-border">
                <i class="fa fa-info-circle"></i><h3 class="box-title">Informazioni</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                         <label>Data Creazione:<br /><span class="badge bg-light-blue"><?=$document->created?></span> </label>
                    </div>
                    <div class="col-md-6">
                        <label >Revisioni: <br /><a href="<?=Router::url('/document/home/history/' . $document->id_document)?>" title="Visualizza"><span class="badge bg-light-blue"><?=$revision?></span></a> </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <i class="fa fa-sitemap"></i><h3 class="box-title">Gerarchia</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                         <div id="tree1" class="parent-tree"></div>



                    </div>

                </div>
            </div>
        </div>
    </div> */ ?>
    <div class="col-md-12" >
         <div class="box box-warning">

            <div class="box-header with-border">
                <i class="fa fa-refresh"></i><h3 class="box-title"><?= __('Aggiorna il documento') ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                     <div class="col-md-6">
                         <div class="select-add">
                            <div class="input select required form-group">
                                <label for="id_client">Cliente</label>
                                <div class="input-group">
                                    <?php // $this->Form->select('id_client',$clients,['id' => 'id_client' , 'class' => 'form-control']) ?>
                                    <select name="id_azienda" id="idAzienda" class="select2 form-control">
                                      <?php if(!empty($document->azienda)): ?>
                                        <option value="<?= $document->azienda->id ?>" ><?= h($document->azienda->denominazione) ?></option>
                                      <?php endif ?>
                                    </select>
                                    <div class="input-group-btn">
                                      <a  href="<?=Router::url('/aziende')?>" class="btn btn-primary"  type="button">Gestisci</a>
                                    </div><!-- /btn-group -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="select-add">
                            <div class="input select required form-group">
                                <label for="id_client">Progetto</label>
                                <div class="input-group">
                                    <?php // $this->Form->select('id_project',$projects,['id' => 'id_project' , 'class' => 'form-control']) ?>
                                    <select name="id_order" id="idOrder" class="select2 form-control">
                                      <?php foreach($orders as $order): ?>
                                        <?php $selected = ($document->id_order == $order->id ? 'selected' : '') ?>
                                        <option value="<?= $order->id ?>"><?= h($order->name) ?></option>
                                      <?php endforeach ?>
                                    </select>
                                    <div class="input-group-btn">
                                      <a href="<?=Router::url('/aziende/orders/index/all')?>" class="btn btn-primary" type="button">Gestisci</a>
                                    </div><!-- /btn-group -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $this->Form->input('title',['label'=>'Titolo del Documento', 'class'=>'form-control']) ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Tag</label>
                            <select multiple="multiple" name="tags[]" id="idTags" class="select2 form-control">
                              <?php if(!empty($document->tags)): ?>
                                <?php foreach($document->tags as $tag): ?>
                                  <option value="<?= $tag['id'] ?>" selected ><?= h($tag['name']) ?></option>
                                <?php endforeach ?>
                              <?php endif ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <div class="input textarea">
                            <label for="text1">Contenuto</label>
                            <textarea name="text1" class="editor-html" maxlength="100000" id="text1" rows="5">
                              <?= $document->text1 ?>
                            </textarea>
                          </div>
                          <?php // $this->Form->input('text1',['label'=>'Contenuto', 'class' => 'editor-html']) ?>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?= $document->id ?>" />
                </div>
            </div>
            <div class="box-footer">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="btn-form-add-edit">

                            <?= $this->Form->button(__('Salva'), ['class' => 'btn btn-primary ']); ?>
                       </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-12" >
        <a class="button" href="<?=Router::url('/document/home/index')?>">Torna indietro</a>
    </div>
    <?= $this->Form->end() ?>
  </div>
</section>
<?= $this->Form->create(null, array( 'enctype' => 'multipart/form-data', 'style'=>'width:0px;height:0;overflow:hidden', 'id'=>'tinymce_upload_form')) ?>
	 <input name="uploadedfile" type="file" id="tinymce_upload" class="" >
<?= $this->Form->end(); ?>
