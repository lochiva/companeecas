<div class="col-md-12 form-group">
   <label for="<?= $id ?>">Data (gg/mm/aaaa)</label>
   <?php if(!empty($disabled)): ?>
       <input class="pull-right"  type="checkbox" onclick="enableDisableInput('<?= $id ?>')"  />
   <?php endif ?>
   <input type="text" id="<?= $id ?>" name="contratto_data_pdr" class="datepicker form-control" <?= (!empty($disabled) ? $disabled:'' ) ?>/>
</div>
