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

	//Formattazione campi decimal (costi)
    $(document).on('change', '.number-decimal', function(event) {
        var val = $(this).val().replace(',', '.');
        var valParsed = parseFloat(val);
        if(valParsed == val || valParsed + '.00' == val){
            var value = parseFloat($(this).val().replace(',', '.')).toFixed(2);
        }else{
            var value = $(this).val();
        }
        
        $(this).val(value.replace('.', ',')).trigger('keyup');
    });
	
	$('#salvaNuovaSede').click(function(){
		
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
		
		if(ckError == true){
			alert(msgError);
		}else{
			//alert('salvo');
			saveFormSede();
		}
		
	});
	
	$('input').focusin(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});
	
});

$(document).on('hide.bs.modal','#myModalSede', function (e) {
	clearModale();
  	reloadTableSedi();
});

function saveFormSede(){

	if(formValidation('formSede')){
	
		var id = $('[name="id"]').val();
		var id_azienda = $('[name="id_azienda"]').val();
		var code_centro = $('[name="code_centro"]').val();
		var id_tipo_ministero = $('[name="id_tipo_ministero"]').val();
		var id_tipo_capitolato = $('[name="id_tipo_capitolato"]').val();
		var id_tipologia_centro = $('[name="id_tipologia_centro"]').val();
		var id_tipologia_ospiti = $('[name="id_tipologia_ospiti"]').val();
		var indirizzo = $('[name="indirizzo"]').val();
		var num_civico = $('[name="num_civico"]').val();
		var cap = $('[name="cap"]').val();
		var comune = $('[name="comune"]').val();
		var provincia = $('[name="provincia"]').val();
		var nazione = $('[name="nazione"]').val();
		var referente = $('[name="referente"]').val();
		var telefono = $('[name="telefono"]').val();
		var cellulare = $('[name="cellulare"]').val();
		var fax = $('[name="fax"]').val();
		var email = $('[name="email"]').val();
		var skype = $('[name="skype"]').val();
		var n_posti_struttura = $('[name="n_posti_struttura"]').val();
		var n_posti_effettivi = $('[name="n_posti_effettivi"]').val();
		var operativita = $('[name="operativita"]').val();
		
		$.ajax({
			url : pathServer + "aziende/Ws/saveSede/" + id,
			type: "POST",
			dataType: "json",
			data:{id:id,id_azienda:id_azienda,id_tipo_ministero:id_tipo_ministero,id_tipo_capitolato:id_tipo_capitolato,id_tipologia_centro:id_tipologia_centro,
				id_tipologia_ospiti:id_tipologia_ospiti,indirizzo:indirizzo,num_civico:num_civico,cap:cap,comune:comune,provincia:provincia,nazione:nazione,
				referente:referente, telefono:telefono,cellulare:cellulare,fax:fax,email:email,skype:skype,n_posti_struttura:n_posti_struttura,
				n_posti_effettivi:n_posti_effettivi,operativita:operativita,code_centro:code_centro},
			success : function (data,stato) {
				
				if(data.response == "OK"){
					if (data.data) {
						//Aggiorna conteggio notifiche
						$.ajax({
							url : pathServer + "aziende/ws/getGuestsNotificationsCount/",
							type: "GET",
							dataType: "json"
						}).done(function(res) {
							if(res.response == 'OK'){
								var count = res.data;
								if(count > 0){
									$('.guests_notify_count_label').html(count);
								} else {
									$('.guests_notify_count_label').html('');
								}
							}
						}).fail(function(richiesta,stato,errori){
							alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
						});
					}
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
	
}

