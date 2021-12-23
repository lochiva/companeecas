<?php
switch ($phase['status']) {
	case 'READY':
		echo '<td><button class="btn btn-flat btn-warning btn-block" id="inviaBtn">Invia</button></td>';
	break;

	case 'DONE':
		echo '<td class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO </td>';
	break;

	default:
		echo '<td><button class="btn btn-flat btn-warning disabled btn-block">Invia</button></td>';
}		
?>