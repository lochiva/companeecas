$(document).ready(function(){

	$('#salvaNuovoCliente').click(function(){

		//alert('salverò');

		var ckError = false;
		var msgError = "";

		//Controllo i campi obbligatoriW
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



		if(ckError == true){
			alert(msgError);
		}else{
			//alert('salvo');
			saveFormCliente($(this).attr('rel'));
		}

	});

	/*$('input').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});*/

});

$(document).on('hide.bs.modal','#myModalCliente', function (e) {
		clearModale();
  	//reloadTableAziende();
});

function saveFormCliente(tipo){

  var id = $('[name="id"]').val();

	var nome = $('[name="nome"]').val();
	var referente = $('[name="referente"]').val();

  var indirizzo_via = $('[name="indirizzo_via"]').val();
  var indirizzo_citta = $('[name="indirizzo_citta"]').val();
  var indirizzo_cap = $('[name="indirizzo_cap"]').val();
  var indirizzo_provincia = $('[name="indirizzo_provincia"]').val();
  var paese = $('[name="paese"]').val();

  var pagamento_fine_mese = $('[name="pagamento_fine_mese"]').val();
  var cod_iva_default = $('[name="cod_iva_default"]').val();
  var extra = $('[name="extra"]').val();

	var tel = $('[name="tel"]').val();
	var fax = $('[name="fax"]').val();
	var email = $('[name="email"]').val();
	var piva = $('[name="piva"]').val();
	var cf = $('[name="cf"]').val();

  var pa = $('[name="PA"]').val();
  var pa_codice = $('[name="PA_codice"]').val();

	$.ajax({
	    url : pathServer + "ficgtw/Ws/addClienteFornitore/"+tipo,
	    type: "POST",
	    dataType: "json",
	    data:{nome:nome, referente:referente,tel:tel,fax:fax,email:email,paese:paese,piva:piva,cf:cf,
            pagamento_fine_mese:pagamento_fine_mese,cod_iva_default:cod_iva_default,extra:extra,pa:pa,pa_codice:pa_codice},
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close-btn').click();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}
