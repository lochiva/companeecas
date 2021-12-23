$(document).ready(function(){


		$('#idAziendaSelect').select2({
			 language: 'it',
			 width: '100%',
			 placeholder: 'Selezione un azienda',
			 closeOnSelect: true,
			 dropdownParent: $("#idAziendaSelectParent"),
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

		 $('#idUserSelect').select2({
 			 language: 'it',
 			 width: '100%',
 			 placeholder: 'Selezione un user',
 			 closeOnSelect: true,
 			 dropdownParent: $("#idUserSelectParent"),
 			 minimumInputLength: 3,
 			 ajax: {
 				 url: pathServer+'ws/autocompleteUser',
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

	$("#idSkills").select2({
		 		language: 'it',
		 		width: '100%'
	});

	$('#salvaNuovoContatto').click(function(){

		//alert('salverò');

		if(formValidation('myFormContatto')){
			//alert('salvo');
			saveFormContatto();
		}

	});

	$('input').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});
	$('select').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});


	$('[name="id_azienda"]').change(function(){

		//alert($(this).val());
		if($(this).val() != "" && !window.fillingModal){
			loadSedi($(this).val());
		}


	});


});

$(document).on('hide.bs.modal','#myModalContatto', function (e) {
		clearModale();

});

function saveFormContatto(){

	var id = $('[name="id"]').val();
	var id_sede = $('[name="id_sede"]').val();
	var id_azienda = $('[name="id_azienda"]').val();
	var id_user = $('[name="id_user"]').val();
	var cognome = $('[name="cognome"]').val();
	var nome = $('[name="nome"]').val();
	var id_ruolo = $('[name="id_ruolo"]').val();
	var cf = $('[name="cf"]').val();
	var indirizzo = $('[name="indirizzo"]').val();
	var num_civico = $('[name="num_civico"]').val();
	var cap = $('[name="cap"]').val();
	var comune = $('[name="comune"]').val();
	var provincia = $('[name="provincia"]').val();
	var nazione = $('[name="nazione"]').val();
	var telefono = $('[name="telefono"]').val();
	var cellulare = $('[name="cellulare"]').val();
	var fax = $('[name="fax"]').val();
	var email = $('[name="email"]').val();
	var skype = $('[name="skype"]').val();
	if($('[name="useApp"]').prop('checked') == true){
		var useApp = 1;
	}else{
		var useApp = 0;
	}
	var skills = $('[name="skills"]').val();

	$.ajax({
	    url : pathServer + "aziende/ws/saveContatto/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{id:id,id_sede:id_sede,id_ruolo:id_ruolo,indirizzo:indirizzo,num_civico:num_civico,cap:cap,comune:comune,provincia:provincia,
	    		nazione:nazione,telefono:telefono,cellulare:cellulare,fax:fax,email:email,skype:skype,useApp:useApp,cognome:cognome,nome:nome,cf:cf,
					id_user:id_user,id_azienda:id_azienda,skills:skills},
	    success : function (data,stato) {

	        if(data.response == "OK"){
						reloadTableContatti();
	        	$('.close').click();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function clearModale(){

	$('[name="id"]').val("");
  if(idAzienda == 0){
			$('[name="id_sede"]').html('<option></option>');
	}
	$('[name="id_user"]').html("");
  	$('[name="id_azienda"]').html('');
	$('[name="id_sede"]').val(id);
	$('[name="id_azienda"]').val(idAzienda);
	$('[name="id_ruolo"]').val(1);
	$('[name="id_user"]').val("");
	$('[name="cognome"]').val("");
	$('[name="nome"]').val("");
	$('[name="cf"]').val("");
	$('[name="indirizzo"]').val("");
	$('[name="num_civico"]').val("");
	$('[name="cap"]').val("");
	$('[name="comune"]').val("");
	$('[name="provincia"]').val("");
	$('[name="nazione"]').val("");
	$('[name="telefono"]').val("");
	$('[name="cellulare"]').val("");
	$('[name="fax"]').val("");
	$('[name="email"]').val("");
	$('[name="skype"]').val("");
	$('[name="useApp"]').val("");
	$('[name="useApp"]').prop("checked", false);
	$("#idSkills").val('').trigger('change');
	$('#myFormContatto')[0].reset();

}

function loadInputModale(idContatto){

	$.ajax({
	    url : pathServer + "aziende/ws/loadContatto/" + idContatto,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {
				window.fillingModal = true;
	        if(data.response == "OK"){
	        	if(tipo == "all"){
							if(data.data.azienda != null){
									$("#idAziendaSelect").html('<option value="'+data.data.id_azienda+'">'+data.data.azienda.denominazione+'</option>');
							}
							$('#idAziendaSelect').val(data.data.id_azienda).trigger("change");
	        		//Devo caricare la select delle sedi perchè è vuota
	        		loadSedi(data.data.id_azienda);
	        	}
				if(data.data.user != null){
					$("#idUserSelect").html('<option value="'+data.data.id_user+'">'+data.data.user.username+'</option>');
				}

	        	$('[name="id"]').val(data.data.id);
				$('[name="id_sede"]').val(data.data.id_sede);
				$('[name="id_azienda"]').val(data.data.id_azienda);
				$('[name="id_ruolo"]').val(data.data.id_ruolo);
				$('[name="id_user"]').val(data.data.id_user);
				$('[name="cognome"]').val(data.data.cognome);
				$('[name="nome"]').val(data.data.nome);
				$('[name="cf"]').val(data.data.cf);
				$('[name="indirizzo"]').val(data.data.indirizzo);
				$('[name="num_civico"]').val(data.data.num_civico);
				$('[name="nazione"]').val(data.data.nazione);
				$('[name="telefono"]').val(data.data.telefono);
				$('[name="cellulare"]').val(data.data.cellulare);
				$('[name="fax"]').val(data.data.fax);
				$('[name="email"]').val(data.data.email);
				$('[name="skype"]').val(data.data.skype);console.log(data.data.Skills.length);
				if(data.data.useApp == true){
					$('[name="useApp"]').val(1);
					$('[name="useApp"]').prop('checked', true);
				}else{
					$('[name="useApp"]').val(0);
					$('[name="useApp"]').prop('checked', false);
				}

				if(data.data.Skills.length > 0){
					 var skills = [];
					 $.each(data.data.Skills,function(index,val){
								skills.push(val.id);
					 });
					 $("#idSkills").val(skills).trigger('change');
				}

				$('[name="provincia"]').append('<option value="'+data.data.provincia+'">'+data.data.provincia+'</option>').val(data.data.provincia).trigger('change');
				$('[name="cap"]').append('<option value="'+data.data.cap+'">'+data.data.cap+'</option>').val(data.data.cap).trigger('change');
				$('[name="comune"]').append('<option value="'+data.data.comune+'">'+data.data.comune+'</option>').val(data.data.comune).trigger('change');

	        }else{
	        	alert(data.msg);
	        }
				window.fillingModal = false;

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}
