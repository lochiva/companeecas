$(document).ready(function(){

	disableInputModale();

	var idAzienda = $('#aziendaId').val(); 

	loadInputModale(idAzienda);

	enableInputModale();

});
