<table width="100%">
	<thead><tr><th>
		Intestazione pagina
	</th></tr></thead>
	<tbody><tr><td>
		<?php foreach($interview['answers'] as $index => $section){ ?>
			<?= $this->Survey->printSectionSurvey($interview['answers'], $section, $index, $section->layout, $interview['data_sheets_info'], $interview['active_components'], $interview['dimensions']) ?>
		<?php } ?>
	</td></tr></tbody>
</table>