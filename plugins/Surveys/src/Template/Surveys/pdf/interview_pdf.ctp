<h1><?= $interview['title'] ?></h1>
<h3><?= $interview['subtitle'] ?></h3>
<p><?= $interview['description'] ?></p>     
<?php foreach($interview['answers'] as $index => $section){ ?>
	<br>
	<?= $this->Utils->printSectionSurvey($section, $index, '') ?>
<?php } ?>
