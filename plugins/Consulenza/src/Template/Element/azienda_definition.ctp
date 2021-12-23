<?php
if( !$azienda['denominazione'] || $azienda['denominazione'] == "") {
	echo $azienda['cognome'] . ' ' .$azienda['nome'];
} else {
	echo $azienda['denominazione'];
}
?>