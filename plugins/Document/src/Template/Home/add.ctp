<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Add  (https://www.companee.it)
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

?>


<section class="content-header">
    <h1>
        Gestione documentale
        <small>Crea nuovo documento</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=Router::url('/document/home');?>">Documenti</a></li>
        <li class="active">Nuovo</li>
    </ol>
</section>


<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="row">
    <?= $this->Form->create($document) ?>
    <div class="col-md-12" >
         <div class="box box-success">

            <div class="box-header with-border">
                <i class="fa fa-plus"></i><h3 class="box-title"><?= __('Crea nuovo documento') ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                     <div class="col-md-6">
                         <div class="select-add">
                            <div class="input select required form-group">
                                <label for="id_client">Cliente</label>
                                <div class="input-group">
                                    <select name="id_azienda" id="idAzienda" class="select2 form-control">
                                      <?php if(!empty($azienda)): ?>
                                        <option value="<?= $azienda['id'] ?>" ><?= $azienda['denominazione'] ?></option>
                                      <?php endif ?>
                                    </select>
                                    <?php // $this->Form->select('id_client',$clients,['id' => 'id_client' , 'class' => 'form-control']) ?>
                                    <div class="input-group-btn">
                                      <a href="<?=Router::url('/aziende')?>" class="btn btn-primary" type="button">Gestisci</a>
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
                                      <?php if(!empty($ordine)): ?>
                                        <option value="<?= $ordine['id'] ?>" ><?= $ordine['name'] ?></option>
                                      <?php endif ?>
                                    </select>
                                    <div class="input-group-btn">
                                      <a  href="<?=Router::url('/aziende/orders/index/all')?>"  class="btn btn-primary" type="button">Gestisci</a>
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
                            <select multiple="multiple" name="tags[]" id="idTags" class="select2 form-control"></select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <?= $this->Form->input('text1',['label'=>'Contenuto', 'class' => 'editor-html']) ?>
                        </div>
                    </div>
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
