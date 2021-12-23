$(document).ready(function(){
	
	$('#salvaNuovaAzienda').click(function(){
		
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
		
		//Controlle che la mail sia realmente una mail
		if(ckError == false){
			
			$('input.check-email').each(function(){
				
				if($(this).val() != ""){
					
					if(!validateEmail($(this).val())){
						ckError = true;
						msgError = "Si prega di inserire una mail valida";
						$(this).parentsUntil('div.form-group').parent().addClass('has-error');
					}
					
				}
				
			});
			
		}
		
		//Controllo che la partita iva sia valida
		if(ckError == false && $('input#inputPiva').val() != ""){
			
			var msgIva = ControllaPIVA($('input#inputPiva').val());
			//alert(msgIva);
			if(msgIva != "OK"){
				
				ckError = true;
				msgError = msgIva;
				$('input#inputPiva').parentsUntil('div.form-group').parent().addClass('has-error');
				
			}
			
		}
		
		//Controllo che il codice fiscale sia valirdo
		if(ckError == false && $('input#inputCF').val() != ""){
			
			var cf = $('input#inputCF').val();

			//alert(cf.length);
			if(cf.length == 11){
				var msgCf = ControllaPIVA(cf);
			}else if(cf.length == 16){
				var msgCf = ControllaCF(cf);
			}else{
				var msgCf = "Il codice fiscale può essere lungo o 11 caratteri (aziende) o 16 caratteri (persone fisiche).";
			}
			
			//alert(msgCf);
			if(msgCf != "OK"){
				
				ckError = true;
				msgError = msgCf;
				$('input#inputCF').parentsUntil('div.form-group').parent().addClass('has-error');
				
			}
			
		}
		
		if(ckError == true){
			alert(msgError);
		}else{
			//alert('salvo');
			saveFormAzienda();
		}
		
	});
	
	$('input').focusin(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});

    $( "[name=Famiglia]" ).autocomplete({
        source: pathServer + "aziende/ws/autocomplete/famiglia",
        minLength: 1,
        select: function( event, ui ) {

            $('[name=Famiglia]').val(ui.item.label);

        }
    });	
	
});



$(document).on('hide.bs.modal','#myModalAzienda', function (e) {
	clearModale();
  	reloadTableAziende();
});

function saveFormAzienda(){
	
	var denominazione = $('[name="Denominazione"]').val();
	var nome = $('[name="Nome"]').val();
	var cognome = $('[name="Cognome"]').val();
	var famiglia = $('[name="Famiglia"]').val();
	var telefono = $('[name="Telefono"]').val();
	var fax = $('[name="Fax"]').val();
	var id = $('[name="idAzienda"]').val();
	var emailInfo = $('[name="emailInfo"]').val();
	var emailContabilita = $('[name="emailContabilita"]').val();
	var emailSolleciti = $('[name="emailSolleciti"]').val();
	var codicePaese = $('[name="codicePaese"]').val();
	var piva = $('[name="piva"]').val();
	var cf = $('[name="cf"]').val();
	var codSispac = $('[name="codSispac"]').val();
	if($('[name="checkCliente"]').is(':checked')){
		var cliente = 1;
	}else{
		var cliente = 0;
	}
	if($('[name="checkFornitore"]').is(':checked')){
		var fornitore = 1;
	}else{
		var fornitore = 0;
	}
	
	
	$.ajax({
	    url : pathServer + "aziende/Ws/saveAzienda/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{denominazione:denominazione,nome:nome,cognome:cognome,famiglia:famiglia,telefono:telefono,fax:fax,id:id,email_info:emailInfo,email_contabilita:emailContabilita,
	    	email_solleciti:emailSolleciti,cod_paese:codicePaese,piva:piva,cf:cf,cod_sispac:codSispac,cliente:cliente,fornitore:fornitore},
	    success : function (data,stato) {
	        
	        if(data.response == "OK"){
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

