<?= $this->Html->script('Calendar.modale_stampa'); ?>
<div class="modal fade" id="myModalStampOperatori" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Stampa Calendario operatori</h4>
      </div>
      <div class="modal-body">
          <?= $this->Form->create(null, ['url' => ['controller'=>'stampe','action' => 'operatori'],
            'id'=>'formStampOperatori', 'target'=>'_blank', 'class'=>'form-stampa']); ?>
          <div class="form-group" >
            <input type="hidden" name="start" value="" />
            <input type="hidden" name="end" value="" />
            <div class="checkbox">
              <label>
                <input name ="select_all" value="1"  type="checkbox">Seleziona tutti
              </label>
            </div>
            <hr />
            <div style="max-height:400px; overflow:auto;">
              <?php foreach ($contacts as $key => $contact) :?>
                <div class="checkbox">
                  <label>
                    <input name ="operatore[]" value="<?=  $contact['id'];?>"  type="checkbox"><?= $contact['cognome'] . " " . $contact['nome'];?>
                  </label>
                </div>
              <?php endforeach ?>
            </div>
          </div>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
        <button type="submit" class="btn btn-primary stamp" name="stampa" >Stampa</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalStampPersone" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Stampa Calendario Persone</h4>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['url' => ['controller'=>'stampe','action' => 'persone'],
        'id'=>'formStampPersone', 'target'=>'_blank', 'class'=>'form-stampa']); ?>
          <div class="form-group">
            <input type="hidden" name="start" value="" />
            <input type="hidden" name="end" value="" />
            <div class="checkbox">
              <label>
                <input name ="select_all" value="1"  type="checkbox">Seleziona tutti
              </label>
            </div>
            <hr />
            <div style="max-height:400px; overflow:auto;">
              <?php foreach ($people as $key => $person) :?>
                <div class="checkbox">
                  <label>
                    <input name ="persona[]" value="<?= $person['id'];?>"  type="checkbox"><?= h($person['text']) ?>
                  </label>
                </div>
              <?php endforeach ?>
            </div>
          </div>
          
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          <button type="submit" class="btn btn-primary stamp" name="stampa" >Stampa</button>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalStampMonteOre" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Stampa Monte ore sett. operatori</h4>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['url' => ['controller'=>'stampe','action' => 'monteOre'],
        'id'=>'formStampMoteOre', 'target'=>'_blank', 'class'=>'form-stampa']); ?>
          <input type="hidden" name="start" value="" />
          <input type="hidden" name="end" value="" />
          <div class="form-group" >
            <div class="checkbox">
              <label>
                <input name ="skills[]" value="1"  type="checkbox">Per tutti gli oss
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input name ="skills[]" value="2"  type="checkbox">Per tutti i Colf
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input name ="skills[]" value="3"  type="checkbox">Per tutti gli assistenti familiari
              </label>
            </div>
          </div>
          
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          <button type="submit" class="btn btn-primary stamp" name="stampa" >Stampa</button>
      </div>
    </form>
    </div>
  </div>
</div>
