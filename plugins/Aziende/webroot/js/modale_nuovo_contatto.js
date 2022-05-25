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
 			 minimumInputLength: 2,
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

	//invio testo privacy via email
    $('#sendPrivacy').click(function(){
        var email = $('.emailForPrivacy').html();
        var privacyText = $('#modalPrivacyPolicy .privacy-text').html();

        $.ajax({
            url: pathServer + 'gdpr/ws/sendPrivacyText',
            type: 'POST',
            dataType: 'json',
            data: {email: email, privacyText: privacyText}
        }).done(function(res) {
            alert(res.msg);
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
	});
	
	//email per invio privacy
	$('#inputEmail').change(function(){ 
		if($(this).val() != ''){
			$('.emailForPrivacy').html($(this).val());
			$('#sendPrivacy').prop('disabled', false);
			$('#sendPrivacy').prop('title', '');
		}else{
			$('.emailForPrivacy').html($(this).val());
			$('#sendPrivacy').prop('disabled', true); 
			$('#sendPrivacy').prop('title', 'Inserire un indirizzo e-mail per poter inviare l\'informativa sulla privacy');  
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
	var skills = $('[name="skills"]').val();
	var readP = $('#checkReadPrivacy').is(':checked') ? '1' : '0';
	var acceptedP = $('#checkAcceptedPrivacy').is(':checked') ? '1' : '0';
	var marketingP = $('#checkMarketingPrivacy').is(':checked') ? '1' : '0';
	var thirdP = $('#checkThirdPartyPrivacy').is(':checked') ? '1' : '0';
	var profilingP = $('#checkProfilingPrivacy').is(':checked') ? '1' : '0';
	var spreadP = $('#checkSpreadPrivacy').is(':checked') ? '1' : '0';

	$.ajax({
	    url : pathServer + "aziende/ws/saveContatto/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{id:id,id_sede:id_sede,id_ruolo:id_ruolo,indirizzo:indirizzo,num_civico:num_civico,cap:cap,comune:comune,provincia:provincia,
	    		nazione:nazione,telefono:telefono,cellulare:cellulare,fax:fax,email:email,skype:skype,cognome:cognome,nome:nome,cf:cf,
					id_user:id_user,id_azienda:id_azienda,skills:skills,read_privacy:readP,accepted_privacy:acceptedP,marketing_privacy:marketingP,
					thid_party_privacy:thirdP,profiling_privacy:profilingP,spread_privacy:spreadP},
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
	$('[name="provincia"]').val("").trigger('change');
	$('#comuneValue').val("");
	$('[name="comune"]').val("").trigger('change');
	$('[name="nazione"]').val("");
	$('[name="telefono"]').val("");
	$('[name="cellulare"]').val("");
	$('[name="fax"]').val("");
	$('[name="email"]').val("");
	$('[name="skype"]').val("");
	$("#idSkills").val('').trigger('change');
	$('#myFormContatto')[0].reset();
	$('#checkReadPrivacy').prop('checked', false);
	$('#checkAcceptedPrivacy').prop('checked', false);
	$('#checkMarketingPrivacy').prop('checked', false);
	$('#checkThirdPartyPrivacy').prop('checked', false);
	$('#checkProfilingPrivacy').prop('checked', false);
	$('#checkSpreadPrivacy').prop('checked', false);

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
				$('[name="cap"]').val(data.data.cap);
				$('#comuneValue').val(data.data.comune);
				$('[name="provincia"]').val(data.data.provincia).trigger('change');
				$('[name="nazione"]').val(data.data.nazione);
				$('[name="telefono"]').val(data.data.telefono);
				$('[name="cellulare"]').val(data.data.cellulare);
				$('[name="fax"]').val(data.data.fax);
				$('[name="email"]').val(data.data.email);
				$('[name="skype"]').val(data.data.skype);console.log(data.data.Skills.length);
				if(data.data.Skills.length > 0){
					 var skills = [];
					 $.each(data.data.Skills,function(index,val){
								skills.push(val.id);
					 });
					 $("#idSkills").val(skills).trigger('change');
				}

				//check privacy
				if(data.data.read_privacy){
					$('#checkReadPrivacy').prop('checked', true);
				}
				if(data.data.accepted_privacy){
					$('#checkAcceptedPrivacy').prop('checked', true);
				}
				if(data.data.marketing_privacy){
					$('#checkMarketingPrivacy').prop('checked', true);
				}
				if(data.data.third_party_privacy){
					$('#checkThirdPartyPrivacy').prop('checked', true);
				}
				if(data.data.profiling_privacy){
					$('#checkProfilingPrivacy').prop('checked', true);
				}
				if(data.data.spread_privacy){
					$('#checkSpreadPrivacy').prop('checked', true);
				}

				$('.emailForPrivacy').html(data.data.email);
				if(data.data.email == ''){
					$('#sendPrivacy').prop('disabled', true);   
					$('#sendPrivacy').prop('title', 'Inserire un indirizzo e-mail per poter inviare l\'informativa sulla privacy');  
				}else{
					$('#sendPrivacy').prop('disabled', false);   
					$('#sendPrivacy').prop('title', '');  
				}

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
