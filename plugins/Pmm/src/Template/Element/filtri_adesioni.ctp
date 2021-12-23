<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>

<script type="text/javascript">

	$(document).ready(function(){

		$('#apply-filters').click(function(){
			applyFilters();
		});

		$('#xls-export').click(function(){
			window.open('<?= Router::url(['plugin' => 'Pmm','controller' => 'Ws','action' => 'getXlsAdesioni']) ?>','_self');
		});

		$('#xls-export2').click(function(){
			window.open('<?= Router::url(['plugin' => 'Pmm','controller' => 'Ws','action' => 'getSecondXlsAdesioni']) ?>','_self');
		});

	});


	function applyFilters()
	{
		var filters = getFilters();

		$.ajax({
			url : '<?= Router::url(['plugin' => 'Pmm','controller' => 'Ajax','action' => 'applyFiltersAdesioni']) ?>',
			type : 'post',
			data : filters,
			success : function(data)
			{
				if(data == 1)
					$('table#table-adesioni').trigger('update');
				else
					alert('Si è verificato un errore durante l\'applicazione dei filtri');
			},
			error : function(data)
			{
				alert('Impossibile caricare i filtri: ' + data.status + ' ' + data.statusText);
			}
		});
	}

	function getFilters()
	{
		var filters = {};

		$('.filter').each(function(){
			filters[$(this).attr('id')] = $(this).val();
		});

		return filters;
	}

</script>

<div class="input-group filtri-adesioni">
	<div class="col-md-2 col-xs-6">
		<label for="filter-pos">POS:</label>
		<select class="form-control select2 filter" id="filter-pos">
			<option value="">Qualsiasi</option>
			<option value="0">Non assegnato</option>
			<?php foreach($pos_list as $id => $name){ ?>
				<?php if($this->request->session()->check(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-pos") && $this->request->session()->read(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-pos") == $id)
						$selected = "selected";
					else
						$selected = "";
				?>

				<option value="<?= $id ?>" <?= $selected ?>><?= $name ?></option>

			<?php } ?>
		</select>
	</div>

	<div class="col-md-2 col-xs-6">
		<label for="filter-pdr">PDR:</label>
		<select class="form-control select2 filter" id="filter-pdr">
			<option value="">Qualsiasi</option>
			<option value="0">Non assegnato</option>
			<?php foreach($pdr_list as $id => $name){ ?>
				<?php if($this->request->session()->check(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-pdr") && $this->request->session()->read(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-pdr") == $id)
						$selected = "selected";
					else
						$selected = "";
				?>

				<option value="<?= $id ?>" <?= $selected ?>><?= $name ?></option>

			<?php } ?>
		</select>
	</div>

	<div class="col-md-2 col-xs-6">
		<label for="filter-status">Stato:</label>
		<select class="form-control select2 filter" id="filter-status">
			<option value="">Qualsiasi</option>
			<?php

			foreach(Configure::read('localConfig.filtro_stato_adesioni') as $stato => $id)
			{
				if($this->request->session()->check(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-status") && $this->request->session()->read(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-status") == $id)
					$selected = "selected";
				else
					$selected = "";
				?>
					<option value="<?= $id ?>" <?= $selected ?>><?= $stato ?></option>
			<?php } ?>
		</select>
	</div>

	<div class="col-md-2 col-xs-6">
		<label for="filter-date">Data:</label>
		<select class="form-control select2 filter" id="filter-date">
			<option value="">Qualsiasi</option>

			<?php

			foreach(Configure::read('localConfig.filtro_data_adesioni') as $data => $id)
			{
				if($this->request->session()->check(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-date") && $this->request->session()->read(Configure::read('localConfig.adesioni_filter_prefix') . ".filter-date") == $id)
					$selected = "selected";
				else
					$selected = "";
				?>
					<option value="<?= $id ?>" <?= $selected ?>><?= $data ?></option>
			<?php } ?>
		</select>
	</div>

	<div class="col-md-4 col-xs-12" >
		<span class="input-group-btn">
			<button class="btn btn-flat btn-success " id="apply-filters" title="Applica i filtri">Filtra</button>
			<button class="btn btn-flat btn-default " data-toggle="tooltip" id="xls-export" title="Esporta il report in formato xlsx per Excel"><img src="<?php echo Router::url('/'); ?>img/xls.png"></button>
			<button class="btn btn-flat btn-default " data-toggle="tooltip"  id="xls-export2" title="Esporta il report in formato xlsx con i dati per fatturazione"><img src="<?php echo Router::url('/'); ?>img/xls.png"></button>
		</span>
	</div>

</div>
