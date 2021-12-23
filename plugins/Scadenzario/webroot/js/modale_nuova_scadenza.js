$(document).ready(function(){

	$('#salvaNuovaScadenza').click(function(){

		//alert('salverò');

		var ckError = false;
		var msgError = "";

		//Controllo i campi obbligatori
		$('input.required').each(function(){

			if(ckError == false && $(this).val() == ""){
				ckError = true;
				msgError = "Si prega di compilare tutti i campi obbligatori";
				$(this).parentsUntil('div.form-group').parent().addClass('has-error');
			}

		});


		if(ckError == true){
			alert(msgError);
		}else{
			//alert('salvo');
			saveFormScadenzario();
		}

	});

	$('input').focusin(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});

});

$(document).on('hide.bs.modal','#myModalScadenzario', function (e) {
	clearModale();
  	reloadTableScadenzario();
});

function saveFormScadenzario(){

	var descrizione = $('[name="Descrizione"]').val();
	var data = $('[name="Data"]').val();
	var data_eseguito = $('[name="DataEseguito"]').val();
	var note = $('[name="Note"]').val();
	var id = $('[name="idScadenzario"]').val();
	var id_event = $('[name="idEvent"]').val();

	$.ajax({
	    url : pathServer + "scadenzario/Ws/saveScadenzario/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{descrizione:descrizione,data:data,data_eseguito:data_eseguito,note:note,id:id,id_event:id_event},
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close').click();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}
