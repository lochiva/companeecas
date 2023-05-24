<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Modale Generazione  (https://www.companee.it)
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
?>

<div class="modal fade" id="myModalGenerazione" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Generazione documentazione</h4>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null,array('url' => '/document/home/generateDocument/')) ?>
        <input type="hidden" name="id_document" value="" />
        <div class="row">
          <div class="form-group col-sm-12">
              <label for="title">Tag</label>
              <select multiple="multiple" name="tags[]" id="idTags" class="select2 form-control">
                <?php if(!empty($document->tags)): ?>
                  <?php foreach($document->tags as $tag): ?>
                    <option value="<?= $tag['id'] ?>" selected ><?= h($tag['name']) ?></option>
                  <?php endforeach ?>
                <?php endif ?>
              </select>
          </div>
          <hr />
          <div class="col-sm-12">
            <h5>Gestione dei titoli dei documenti:</h5>
          </div>
          <div class="form-group col-sm-6">
              <label for="title">Heading iniziale</label>
              <input name="heading" type="number" min="1" max="6" value="1" class="form-control"/>
          </div>
          <div class="form-group col-sm-6">
              <label for="title">Heading massimo</label>
              <input name="headingMax" type="number" min="1" max="6" value="6" class="form-control"/>
          </div>
          <div class="form-group col-sm-6">
              <label for="titsectionle">Aggiungi la numerazione ai paragrafi</label>
              <select name="section" class="form-control">
                <option value="1">Si</option>
                <option value="0">No</option>
              </select>
          </div>
          <div class="form-group col-sm-6">
              <label for="central">Titoli centrali</label>
              <select name="central" class="form-control">
                <option value="1">Si</option>
                <option value="0">No</option>
              </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-default pull-right" data-dismiss="modal">Chiudi</a>
        <button type="submit" class="btn btn-primary pull-right" >Genera</button>
        <?= $this->Form->end() ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
