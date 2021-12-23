<?php

use Cake\Core\Configure;

?>
<div class="col-md-12 form-group">
    <label for="<?= $id ?>">Stato</label>
    <?php if(!empty($disabled)): ?>
				<input class="pull-right"  type="checkbox" onclick="enableDisableInput('<?= $id ?>')"  />
		<?php endif ?>
	<select id="<?= $id ?>" name="contratto_fk_statiContratti" class="form-control" <?= (!empty($disabled) ? $disabled:'' ) ?>>
		<?php foreach(Configure::read('localConfig.filtro_stato_adesioni') as $stato => $id){ ?>
			<option value="<?= $id ?>"><?= $stato ?></option>
		<?php } ?>
	</select>
</div>
