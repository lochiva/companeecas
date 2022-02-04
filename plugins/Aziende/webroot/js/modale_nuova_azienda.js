$(document).ready(function(){

	//solo numeri in input number
    $(document).on('keydown', '.number-integer, .number-decimal', function (e) {
        if($(this).hasClass('number-integer')){
            // Allow: backspace, delete, tab, escape and enter 
            var accepted = [46, 8, 9, 27, 13]
        }else{
            // Allow: backspace, delete, tab, escape, enter, . and ,
            var accepted = [46, 8, 9, 27, 13, 110, 190, 188]
        }
        
        if ($.inArray(e.keyCode, accepted) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {

                if (($.inArray(e.keyCode, [110, 190]) !== -1 && (this.value.split('.').length === 2 || this.value.split(',').length === 2)) || 
                    (e.keyCode == 188 && (this.value.split(',').length === 2 || this.value.split('.').length === 2))) {
                    return false;
                }else{
                    return;
                }
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

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

			var msgCf ='';
			if(isNaN($('input#inputCF').val()) ){
				msgCf = ControllaCF($('input#inputCF').val());
			}else{
				msgCf = ControllaPIVA($('input#inputCF').val(), 'Il codice fiscale di una persona giuridica');
			}
			//var msgCf = ControllaCF($('input#inputCF').val());
			//alert(msgCf);
			if(msgCf != "OK"){

				ckError = true;
				msgError = msgCf;
				$('input#inputCF').parentsUntil('div.form-group').parent().addClass('has-error');

			}

		}

		//Controllo che la pec sia valida
		if(ckError == false && $('input#inputPec').val() != ""){

				if(!validateEmail($('input#inputPec').val()) ){
					ckError = true;
					msgError = "Si prega di inserire una pec valida";
					$('input#inputPec').parentsUntil('div.form-group').parent().addClass('has-error');
				}

		}

		if(ckError == true){
			alert(msgError);
		}else{
			//alert('salvo');
			saveFormAzienda();
		}

	});

	/*$('input').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});*/

});

$(document).on('hide.bs.modal','#myModalAzienda', function (e) {
		//clearModale();
  	//reloadTableAziende();
});

$(document).on('keyup change','#inputPA', function (e) {
	$(this).val($(this).val().toUpperCase());
});

$(document).on('show.bs.modal','#myModalAzienda', function (e) {
	$('#saveModalAziende').prop('disabled', false);
});


function saveFormAzienda(){

	var denominazione = $('[name="Denominazione"]').val();
	var nome = $('[name="Nome"]').val();
	var cognome = $('[name="Cognome"]').val();
	var telefono = $('[name="Telefono"]').val();
	var fax = $('[name="Fax"]').val();
	var id = $('[name="idAzienda"]').val();
	var emailInfo = $('[name="emailInfo"]').val();
	var emailContabilita = $('[name="emailContabilita"]').val();
	var emailSolleciti = $('[name="emailSolleciti"]').val();
	var codicePaese = $('[name="codicePaese"]').val();
	var piva = $('[name="piva"]').val();
	var cf = $('[name="cf"]').val();
	var pec = $('[name="pec"]').val();
	var sito_web = $('[name="sito_web"]').val();
	var cliente = 0;
	var fornitore = 0;
	var interno = 0;
	if($('[name="checkCliente"]').is(':checked')){
		cliente = 1;
	}
	if($('[name="checkFornitore"]').is(':checked')){
		fornitore = 1;
	}
	if($('[name="checkInterno"]').is(':checked')){
		interno = 1;
	}


	$.ajax({
	    url : pathServer + "aziende/Ws/saveAzienda/" + id,
	    type: "POST",
	    dataType: "json",
	    data:{denominazione:denominazione,nome:nome,cognome:cognome,telefono:telefono,fax:fax,id:id,email_info:emailInfo,email_contabilita:emailContabilita,
	    	email_solleciti:emailSolleciti,cod_paese:codicePaese,piva:piva,cf:cf,cliente:cliente,fornitore:fornitore,pec:pec,sito_web:sito_web,interno:interno},
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
