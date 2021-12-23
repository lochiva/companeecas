$(document).ready(function(){

	$('#salvaCespite').click(function(){

		//alert('salverò');

		var ckError = false;
		var msgError = "";

		$('div.has-error').each(function(){
			$(this).removeClass('has-error');
		});

		//Controllo i campi obbligatori
		$('.required').each(function(){

			switch($(this).attr('name')){
				case 'id_azienda':
				case 'id_fattura_passiva':
					if(ckError == false && $(this).val() == 0){
						ckError = true;
						msgError = "Si prega di compilare tutti i campi obbligatori";
						$(this).parent().addClass('has-error');
					}
					break;
				case 'num':
				case 'stato':
				case 'descrizione':
					if(ckError == false && $(this).val() == ""){
						ckError = true;
						msgError = "Si prega di compilare tutti i campi obbligatori";
						$(this).parent().addClass('has-error');
					}
					break;
			}

		});


		if(ckError == true){
			alert(msgError);
		}else{
			//alert('salvo');
			saveFormCespite();
		}

	});

	$('input').focusin(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});

	$('#idAzienda').change(function(){
		var azienda = $(this).val();
		if(azienda == 0){
			$('#idFatturaPassiva').html('');
		}else{
			loadFatture(azienda);
		}
	});

});

$(document).on('hide.bs.modal','#myModalCespite', function (e) {
	clearModal();
});

function saveFormCespite(){

	var id = $('[name="id_cespite"]').val();
	var id_azienda = $('[name="id_azienda"]').val();
	var id_fattura_passiva = $('[name="id_fattura_passiva"]').val();
	var numero = $('[name="num"]').val();
	var stato = $('[name="stato"]').val();
	var descrizione = $('[name="descrizione"]').val();
	var note = $('[name="note"]').val();

	$.ajax({
	    url : pathServer + "cespiti/Ws/saveCespite/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{id:id, id_azienda:id_azienda, id_fattura_passiva:id_fattura_passiva, num:numero, stato:stato, descrizione:descrizione, note:note},
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close').click();
				clearModal();
				reloadTableCespiti();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadFornitori(selected){
	$.ajax({
	    url : pathServer + "cespiti/Ws/getFornitori",
	    type: "POST",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
				$('#idAzienda').html('');
				var html = '<option value="0"></option>';
	        	data.data.forEach(function(fornitore){
					if(selected == fornitore.id){
						html += '<option value="'+fornitore.id+'" selected>'+fornitore.denominazione+'</option>';
					}else{
						html += '<option value="'+fornitore.id+'" >'+fornitore.denominazione+'</option>';
					}
				});
				$('#idAzienda').append(html);
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});
}

function loadFatture(id, selected){
	$.ajax({
	    url : pathServer + "cespiti/Ws/getFatture/"+id,
	    type: "POST",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
				$('#idFatturaPassiva').html('');
				var html = '<option value="0"></option>';
	        	data.data.forEach(function(fattura){
					if(selected == fattura.id){
						html += '<option value="'+fattura.id+'" selected>'+fattura.emission_date.substr(0, fattura.emission_date.indexOf('-'))+' n. '+fattura.num+'</option>';
					}else{
						html += '<option value="'+fattura.id+'" >'+fattura.emission_date.substr(0, fattura.emission_date.indexOf('-'))+' n. '+fattura.num+'</option>';
					}
				});
				$('#idFatturaPassiva').append(html);
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});
}
