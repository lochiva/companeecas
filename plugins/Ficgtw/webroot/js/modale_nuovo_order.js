// $.fn.modal.Constructor.prototype.enforceFocus = function() {};
$(document).ready(function(){

	if(idAzienda == 'all'){
		$('#idAzienda').select2({
			 language: 'it',
			 width: '100%',
			 placeholder: 'Selezione un azienda',
			 closeOnSelect: true,
			 dropdownParent: $("#idAziendaParent"),
			 minimumInputLength: 3,
			 ajax: {
				 url: pathServer+'aziende/ws/autocompleteAziende',
				 dataType: 'json',
				 delay: 250,
				 processResults: function (data) {
					 return {
						 results: data.data
					 };
				 },
				 cache: true
			 }
		 });
	}

	$('#salvaNuovoOrder').click(function(){

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
			saveFormOrder();
		}

	});

	$('input').focusin(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});


	$('#idAzienda').change(function(){

		//alert($(this).val());
		if($(this).val() != ""){

			loadContattiAzienda($(this).val());
		}


	});


});

$(document).on('hide.bs.modal','#myModalOrder', function (e) {
	  clearModale();
  	//reloadTableSedi();
});

function saveFormOrder(){

	var id = $('[name="id"]').val();
	var id_azienda = $('[name="id_azienda"]').val();
	var nome = $('[name="nome"]').val();
	var id_contatto = $('[name="id_contatto"]').val();
  var note = $('[name="note"]').val();
	var id_status = $('[name="id_status"]').val();

	$.ajax({
	    url : pathServer + "aziende/Ws/saveOrder/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{id_contatto:id_contatto,note:note,name:nome,id_azienda:id_azienda,id:id,id_status:id_status},
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close').click();
            reloadTableOrder();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadContattiAzienda(id, selectedId)
{

	$.ajax({
	    url : pathServer + "aziende/Ws/getContattiAzienda/" + id  ,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    data:{},
	    success : function (data,stato) {

	        if(data.response == "OK"){
						//console.log(data);

	        	var option = '<option style="color: graytext;" value="0">Nessuno</option>';

	        	for (var item in data.data) {

								option += '<option value="' + data.data[item].id+ '">' + data.data[item].text + '</option>';

						}

	        	$('#idContatto').html(option);

						if(selectedId !== undefined){
							$('#idContatto').val(selectedId);
						}

	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}
