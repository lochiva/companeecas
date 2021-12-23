<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $service->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $service->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Services'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="services form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($service,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Edit Service') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('billable');
            echo $this->Form->input('editable');
            echo $this->Form->input('ordering');
                    echo '<div class="input input-group color-picker colorpicker-component">'.
                    $this->Form->input('color').
                    '<span class="input-group-addon"><i></i></span></div>';
            ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
<div class="col-md-12">
	<div class="col-md-12">
		<div class="col-md-6" style="">
			<h3 style="display: inline;">Attività</h3>
		</div>
		<div class="col-md-6">
			<p style="float: right;" >Aggiungi attività per questo servizio <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModalAddActivity" title="Aggiungi"><i class="glyphicon glyphicon-plus"></i></button></p>
		</div>
	</div>
	<table class="table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Numero</th>
                <th scope="col">Nome</th>
                <th scope="col">Ordinamento</th>
				<th scope="col">Note</th>
                <th scope="col">Creato</th>
                <th scope="col">Modificato</th>
                <th scope="col" class="actions"><?= __('Azioni') ?></th>
            </tr>
        </thead>
		<tbody>
			<?php foreach($activities as $activity){ ?>
			<tr>
				<td><?= $activity['id'] ?></td>
				<td><?= $activity['name'] ?></td>
				<td><?= $activity['order_value'] ?></td>
				<td>
					<?php if($activity['hasNote']){
							  echo 'Sì';
						  }else{
							  echo 'No';
						  }
					?>
				</td>
				<td><?= $activity['created'] ?></td>
				<td><?= $activity['modified'] ?></td>
				<td class="actions">
					<button class="btn btn-xs btn-primary editActivityButton" data-toggle="modal" data-target="#myModalEditActivity" data-activityid="<?= $activity['id'] ?>" data-name="<?= $activity['name'] ?>" data-order="<?= $activity['order_value'] ?>" data-note="<?= $activity['hasNote'] ?>" title="Modifica"><i class="glyphicon glyphicon-pencil"></i></button>
					<?= $this->Form->create(null,['url' => ['controller' => 'Activities', 'action' => 'delete', $activity['id'], $id_service], 'style' => 'display:inline;' ]) ?>
					<button onclick="if (confirm('<?= __('Are you sure you want to delete # {0}?', $activity['id']) ?>')) { document.post_58e39137cc0fa725607991.submit(); } event.returnValue = false; return false;"
					  class="btn btn-xs btn-danger" type="submit" title="Elimina"><i class="glyphicon glyphicon-remove"></i></button>
					<?= $this->Form->end() ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?= $this->element('modal_add_activity', array('id_service' => $service->id)); ?>
<?= $this->element('modal_edit_activity', array('id_service' => $service->id)); ?>
