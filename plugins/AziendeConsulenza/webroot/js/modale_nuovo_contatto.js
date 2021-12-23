$(document).ready(function(){
	
	$('#salvaNuovoContatto').click(function(){
		
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
		
		//Controllo che il codice fiscale sia valirdo
		if(ckError == false && $('input#inputCF').val() != ""){
			
			var msgCf = ControllaCF($('input#inputCF').val());
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
			saveFormContatto();
		}
		
	});
	
	$('input').focusin(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});
	
	
	$('#idAzienda').change(function(){
		
		//alert($(this).val());
		if($(this).val() != ""){
			loadSedi($(this).val());
		}
		
		
	});
	
	
});

$(document).on('hide.bs.modal','#myModalContatto', function (e) {
	clearModale();
  	reloadTableSedi();
});

function saveFormContatto(){
	
	var id = $('[name="id"]').val();
	var id_sede = $('[name="id_sede"]').val();
	var id_azienda = $('[name="id_azienda"]').val();
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
	var telefono = $('[name="Telefono"]').val();
	var cellulare = $('[name="cellulare"]').val();
	var fax = $('[name="Fax"]').val();
	var email = $('[name="email"]').val();
	var skype = $('[name="skype"]').val();
	
	
	$.ajax({
	    url : pathServer + "aziende/Ws/saveContatto/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{id:id,id_sede:id_sede,id_ruolo:id_ruolo,indirizzo:indirizzo,num_civico:num_civico,cap:cap,comune:comune,provincia:provincia,
	    		nazione:nazione,telefono:telefono,cellulare:cellulare,fax:fax,email:email,skype:skype,cognome:cognome,nome:nome,cf:cf,id_azienda:id_azienda},
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


