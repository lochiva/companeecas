$(document).ready(function(){
	//popolo hidden per box allegati
	if($('#boxAttachments').length > 0){
		$('#contextForAttachment').html('aziende');
		$('#idItemForAttachment').html(id_azienda);
	}
});

function afterSaveModalAziende(){
		$('.close').click();
		location.reload();
}
