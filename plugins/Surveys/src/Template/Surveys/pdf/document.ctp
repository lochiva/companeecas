<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<main>
		<?php foreach($interview['answers'] as $index => $section){ ?>
			<?= $this->Survey->printSectionSurvey($interview['answers'], $section, $index, $section->layout, $interview['data_sheets_info'], $interview['active_components'], $interview['dimensions']) ?>
		<?php } ?>
	</main>
</body>
<html>