<div class="col-md-12 form-group">
	<label for="<?= $id ?>">PDR</label>
		<?php if(!empty($disabled)): ?>
				<input class="pull-right"  type="checkbox" onclick="enableDisableInput('<?= $id ?>')"  />
		<?php endif ?>
		<select id="<?= $id ?>" name="contratto_fk_pdr" class="form-control " <?= (!empty($disabled) ? $disabled:'' ) ?>>
			<option value="0">-- Nessuno --</option>
			<?php foreach($pdr_list as $id => $name){ ?>
					<option value="<?= $id ?>"><?= $name ?></option>
			<?php } ?>
		</select>
</div>
