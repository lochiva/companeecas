<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Generate Document  (https://www.companee.it)
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
        Generazione documentazione
        <small>Titolo documento: <b><?=h($title)?></b></small>
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
    <div class="col-md-12" >
         <div class="box box-warning">

            <div class="box-header with-border">
              <?php if(!empty($tags)): ?>
                <i class="fa fa-tags"></i><h3 class="box-title"><?= __('Tag usati: ') ?>
                  <?php foreach($tags as $tag): ?>
                    <span class="tag-view"><?= h($tag['name']) ?></span>
                  <?php endforeach ?>
                </h3>
              <?php endif ?>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                          <div class="input textarea"><label for="text1">Contenuto</label>
                            <textarea name="text1" class="editor-html" maxlength="100000" id="text1" rows="5">
                              <?= $content ?>
                            </textarea>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="btn-form-add-edit">
                       </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-12" >
        <a class="button" href="<?=Router::url('/document/home/index')?>">Torna indietro</a>
    </div>
  </div>
</section>
<?= $this->Form->create(null, array( 'enctype' => 'multipart/form-data', 'style'=>'width:0px;height:0;overflow:hidden', 'id'=>'tinymce_upload_form')) ?>
	 <input name="uploadedfile" type="file" id="tinymce_upload" class="" >
<?= $this->Form->end(); ?>
