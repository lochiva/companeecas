<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Document  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
?>

<!DOCTYPE html>
<html>
<head>
	<style>
		*  {
			font-family: "Times" !important;
		}
	</style>
</head>
<body>
	<main>
		<?php foreach($interview['answers'] as $index => $section){ ?>
			<?= $this->Survey->printSectionSurvey($interview['answers'], $section, $index, $section->layout, $interview['data_sheets_info'], $interview['active_components'], $interview['dimensions']) ?>
		<?php } ?>
	</main>
</body>
<html>