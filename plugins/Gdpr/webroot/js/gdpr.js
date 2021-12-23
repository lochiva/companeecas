$(document).ready(function(){

    //apertura overlay gdpr
    $('.open-overlay-gdpr').click(function(){
        $('#disabled_background').fadeIn('200');
        $('#overlay_gdpr').fadeIn('200');
    });

    //chiusura overlay gdpr
    $('.close-overlay-gdpr').click(function(){
        $('#disabled_background').fadeOut('200');
        $('#overlay_gdpr').fadeOut('200');
        //reset input email
        $('#gdpr_email').val('');
        $('#gdpr_email').removeClass("invalid");
        $('#invalid_email_message').html('');
        //riabilito tato verifica
        $('#verify_email').prop('disabled', false);
    });

    //chiusura overlay gdpr al click background
    $('#disabled_background').click(function(e){
        $('#disabled_background').fadeOut('200');
        $('#overlay_gdpr').fadeOut('200');
        //reset input email
        $('#gdpr_email').val('');
        $('#gdpr_email').removeClass("invalid");
        $('#invalid_email_message').html('');
        //riabilito tato verifica
        $('#verify_email').prop('disabled', false);
    });

    //valido input email
    $('#gdpr_email, #inputEmail').focusout(function() {
        var email = $(this).val();
        if(email != ''){
            var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var is_email = regex.test(email);
            if(is_email){
                $(this).parentsUntil('.input').parent().removeClass("invalid");
                $('#invalid_email_message').html('');
                $('#verify_email').prop('disabled', false);
                $('#saveContact').prop('disabled', false);
            }else{
                $(this).parentsUntil('.input').parent().addClass("invalid");
                $('#invalid_email_message').html('Inserire una email valida.');
                $('#verify_email').prop('disabled', true);
                $('#saveContact').prop('disabled', true);
            }
        }
    });

    //valido input cf
    $('#inputCf').focusout(function() {
        var cf = $(this).val();
        if(cf != ''){
            var checkMessage = checkCf(cf);
            if(checkMessage == 'OK'){
                $(this).parentsUntil('.input').parent().removeClass("invalid");
                $('#invalid_cf_message').html('');
                $('#saveContact').prop('disabled', false);
            }else{
                $(this).parentsUntil('.input').parent().addClass("invalid");
                $('#invalid_cf_message').html(checkMessage);
                $('#saveContact').prop('disabled', true);
            }
        }else{
            $(this).parentsUntil('.input').parent().removeClass("invalid");
            $('#invalid_cf_message').html('');
            $('#saveContact').prop('disabled', false);
        }
    });

    //Verifico email
    $('#verify_email').click(function(){
        var email = $('#gdpr_email').val();
        if(email != ''){
            $.ajax({
                url: pathServer + 'gdpr/ws/verifyEmail',
                type: 'POST',
                dataType: 'json',
                data: {email: email}
            }).done(function(res) {

                $('#response_verify_email').html('<p>'+res.msg+'</p>');
                if(res.response == 'OK'){
                    $('#response_verify_email').removeClass('error-message');
                    $('#response_verify_email').addClass('success-message');
                }else{
                    $('#response_verify_email').removeClass('success-message');
                    $('#response_verify_email').addClass('error-message');
                }
                $('#response_verify_email').show().delay('10000').fadeOut('400');

            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }else{
            $('#gdpr_email').addClass("invalid");
            $('#invalid_email_message').html('Inserire una email valida.');
            $('#verify_email').prop('disabled', true);
        }
    });

    //Imposto l'helper dei luoghi nel form.
    if($('#inputProvincia').length > 0){
        //carica valori provincia, comune, cap per contatto
        $.ajax({
            url: pathServer + 'gdpr/ws/getLuoghiContatto',
            type: 'POST',
            dataType: 'json',
            data: {email: $('#inputEmail').val()}
        }).done(function(res) {
            if(res.response == 'OK'){
                var luoghi = {
                    'provincia': res.data.provincia,
                    'comune': res.data.comune,
                    'cap': res.data.cap
                };
                select2luoghi(luoghi);
            }

        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    }

    //Salvo dati contatto
    $('#saveContact').click(function(){
        if(validationFormContact()){
			saveContact();
		}
    })

    //email per notifica modifiche dati e invio privacy
    $('.emailForPrivacy').html($('#inputEmail').val());
    $('#inputEmail').change(function(){ 
        $('.emailForPrivacy').html($(this).val());
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

    if($('#box-check-data-success').length > 0){ 
        $('#box-check-data-success').parent().css('height', $('#container').height());
    }

});

function saveContact(){

    var formData = new FormData($('#formContact')[0]);

	$.ajax({
	    url : pathServer + "gdpr/ws/saveContact",
        type: "POST",
        processData: false,
        contentType: false,
	    dataType: "json",
	    data: formData,
	    success : function (data,stato) {

	        if(data.response == "OK"){
                window.location = pathServer + "gdpr/profile/checkSuccess";
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function validationFormContact(){
    var ckError = false;
 	var msgError = "";
 	var firstElem = '';
 	
 	$('#formContact input, #formContact select').each(function(){
        //Controllo i campi obbligatori
 		if($(this).val() == "" || $(this).val() == null ){
            if($(this).hasClass('required')){
                ckError = true;
     			msgError = "Si prega di compilare tutti i campi obbligatori";
                 $(this).parentsUntil('.input').parent().addClass('invalid');
     			if(firstElem == ""){
     				firstElem = this;
     			}
            }
        }
     });
     
    if(ckError == true){
        alert(msgError);
        $(firstElem).focus();
        return false;
    }

   return true;
}

function checkCf(cf){
    var validi, i, s, set1, set2, setpari, setdisp;

    cf = cf.toUpperCase();

    if( cf.length != 16 ){
    	return "Il codice fiscale dovrebbe essere lungo\n" +"esattamente 16 caratteri.\n";
    }

    validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for( i = 0; i < 16; i++ ){
        if( validi.indexOf( cf.charAt(i) ) == -1 ){
        	 return "Il codice fiscale contiene un carattere non valido `" + cf.charAt(i) + "'.\nI caratteri validi sono le lettere e le cifre.\n";
        }

    }

    set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
    s = 0;
    for( i = 1; i <= 13; i += 2 )
        s += setpari.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
    for( i = 0; i <= 14; i += 2 )
        s += setdisp.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
    if( s%26 != cf.charCodeAt(15)-'A'.charCodeAt(0) ){
    	return "il codice di controllo non corrisponde.\n";
    }

    return "OK";
}

function select2luoghi(luoghi)
{
    provincia = $('#inputProvincia');
    comune = $('#inputComune');
    cap = $('#inputCap');

    comune.select2({
        language: 'it',
        width: '100%',
        placeholder: 'Seleziona il comune',
        dropdownParent: comune.parent(),
        closeOnSelect: true,
        val: '',
        tags:true,
        allowClear: true,
    });
    cap.select2({
        language: 'it',
        width: '100%',
        placeholder: 'Seleziona il cap',
        dropdownParent: cap.parent(),
        closeOnSelect: true,
        val: '',
        tags:true,
        allowClear: true,
    });
    comune.attr('disabled',true);
    cap.attr('disabled',true);
    
    provincia.append('<option value="'+luoghi.provincia+'">'+luoghi.provincia+'</option>');
    $.ajax({
        url : pathServer + "ws/getProvince/true",
        type: "GET",
        dataType: "json",
        success : function (data,stato) {
          provincia.select2({
              language: 'it',
              width: '100%',
              placeholder: 'Seleziona una provincia',
              dropdownParent: provincia.parent(),
              closeOnSelect: true,
              data: data.data,
              val: '',
              tags:true,
              allowClear: true,
          });

          provincia.val(luoghi.provincia).trigger('change');
        },
        error : function (data){

        }
    });
    provincia.change(function(){
      if($(this).val() != null && $(this).val() != ''){
          comune.attr('disabled',false);
      }else{
          comune.attr('disabled',true);
      }
      if(!window.fillingModal){
        $.ajax({
            url : pathServer + "ws/getLuoghi/true",
            type: "POST",
            data: { q:$(this).val() },
            dataType: "json",
            success : function (data,stato) {
              comune.select2('destroy').empty().select2({
                  language: 'it',
                  width: '100%',
                  placeholder: 'Seleziona il comune',
                  dropdownParent: comune.parent(),
                  closeOnSelect: true,
                  val: '',
                  tags:true,
                  data: data.data,
                  allowClear: true,
              });
              comune.val(luoghi.comune).trigger('change');
            },
            error : function (data){

            }
        });
      }
    });
    comune.change(function(){
       if($(this).val() != null && $(this).val() != ''){
           cap.attr('disabled',false);
       }else{
           cap.attr('disabled',true);
       }
       if(!window.fillingModal){
           $.ajax({
               url : pathServer + "ws/getCap/true",
               type: "POST",
               data: { q:$(this).val() },
               dataType: "json",
               success : function (data,stato) {
                 cap.select2('destroy').empty().select2({
                     language: 'it',
                     width: '100%',
                     placeholder: 'Seleziona il cap',
                     dropdownParent: cap.parent(),
                     closeOnSelect: true,
                     val: '',
                     tags:true,
                     data: data.data,
                     allowClear: true,
                 });
                 var val = '';
                 if(data.data.length == 1){
                    val = data.data[0].id;
                 }else{
                    val = luoghi.cap;
                 }
                 cap.val(val).trigger('change');

               },
               error : function (data){

               }
           });
         }
     });

}