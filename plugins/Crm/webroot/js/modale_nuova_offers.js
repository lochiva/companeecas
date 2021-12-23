var fireChangeOffers = true;
$(document).ready(function(){

	$('[name="id_azienda_emit"]').select2({
		//language: 'it',
		width: '100%',
		placeholder: 'Seleziona un azienda',
		//dropdownParent: $("#id_azienda_emitParent"),
		closeOnSelect: true
	}).change(function(){
		if(fireChangeOffers){
			loadContattiAzienda($(this).val(),'[name="id_contatto_emit"]');
		}

	});

	$('[name="id_azienda_emit"]').trigger('change');

	$('[name="id_contatto_emit"]').select2({
		//language: 'it',
		width: '100%',
		placeholder: 'Seleziona un contatto',
		//dropdownParent: $("#id_contatto_emitParent"),
		closeOnSelect: true
	});

	$('[name="id_azienda_dest"]').select2({
		//language: 'it',
		width: '100%',
		placeholder: 'Seleziona un azienda',
		//dropdownParent: $("#id_azienda_destParent"),
		closeOnSelect: true,
		minimumInputLength: 3,
		/*"language": {
			"noResults": function(){
				return "Nessun risultato <a href='#' class='btn btn-info'>Inserisci</a>";
			}
		},*/
		escapeMarkup: function (markup) {
			return markup;
		},
		ajax: {
			url: pathServer+'aziende/ws/autocompleteAziende',
			dataType: 'json',
			quietMillis: 250,
			data: function (term, page) {
				return {
					q: term
				};
			},
			results: function (data, page) { 
				return { 
					results: data.data 
				};
			},
			cache: true
		}/*
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
		}*/
	}).change(function(e){
		if(fireChangeOffers){
			loadContattiAzienda(e.val,'[name="id_contatto_dest"]');
			loadSediAzienda(e.val,'[name="id_sede_dest"]');
		}

	});

	$('[name="id_contatto_dest"]').select2({
		//language: 'it',
		width: '100%',
		placeholder: 'Seleziona un contatto',
		//dropdownParent: $("#id_contatto_destParent"),
		closeOnSelect: true
	 });

	$('[name="id_sede_dest"]').select2({
		//language: 'it',
		width: '100%',
		placeholder: 'Seleziona una sede',
		//dropdownParent: $("#id_sede_destParent"),
		closeOnSelect: true
	});

	$('#saveOffer').click(function(){

 		//alert('salverò');

 		var ckError = false;
 		var msgError = "";
     var firstElem = '';

     $('#myModalOffer input.required, #myModalOffer select.required').each(function(){
       if(ckError == false && ($(this).val() == "" || $(this).val() == null )){
         ckError = true;
         msgError = "Si prega di compilare tutti i campi obbligatori";
         $(this).parentsUntil('div.form-group').parent().addClass('has-error');
         if(firstElem == ""){
           firstElem = this;
         }
       }

     });
 		//Controllo i campi obbligatori
 		$('#myModalOffer input.required').each(function(){

 			if(ckError == false && $(this).val() == ""){
 				ckError = true;
 				msgError = "Si prega di compilare tutti i campi obbligatori";
 				$(this).parentsUntil('div.form-group').parent().addClass('has-error');
         if(firstElem == ""){
           firstElem = this;
         }
 			}

 		});

 		if(ckError == true){
 			alert(msgError);
       $(firstElem).focus();
 		}else{
 			//alert('salvo');
 			saveFormOffer('myFormOffer');
 		}

 	});

 	$('input').change(function(){
 		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
 	});
   $('select').change(function(){
 		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
 	});


});

$(document).on('hidden.bs.modal','#myModalOffer', function (e) {
	  clearModale();
  	//reloadTableSedi();
});


function saveFormOffer(idForm)
{

	$(".inputNumber").each(function(){
       var val = $(this).val().replace(/,/g, ".");
       $(this).val(val);
   });
	var formData= new FormData(document.getElementById(idForm) );
	$.ajax({
	    url : pathServer + "crm/ws/offers/save/",
	    type: "POST",
	    dataType: "json",
	    data:formData,
			processData: false,
      contentType: false,
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close').click();
						if(data.msg != ''){
              alert(data.msg);
            }
			if($("#table-offers").length){
				reloadTableOffers();
			}else{
				location.reload();
			}
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadContattiAzienda(id,elem,selectedId)
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

				$(elem).html(option);
				
				$(elem).select2('val', '');

				if(selectedId !== undefined){
					$(elem).select2('val', selectedId);
				}

				$(elem).trigger('change');

	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadSediAzienda(id,elem,selectedId){

	$.ajax({
	    url : pathServer + "aziende/Ws/getSedi/" + id + "/json",
	    type: "GET",
	    async: false,
	    dataType: "json",
	    data:{},
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	var option = '<option style="color: graytext;" value="0">Nessuno</option>';

	        	for (var item in data.data) {

					option += '<option value="' + data.data[item].id+ '">' + data.data[item].indirizzo +' '+data.data[item].num_civico+'</option>';
				
				}

				$(elem).html(option);

				$(elem).select2('val', '');

				if(selectedId !== undefined){
					$(elem).select2('val', selectedId);
				}

				$(elem).trigger('change');

	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function clearModale(){
	fireChangeOffers = false;
	$('#myFormOffer')[0].reset();
	$('[name="id"]').val("");
	$('.select2-3').each(function( index, value ) {
		var name = $(this).attr('name');
		if(name != 'id_azienda_emit'){
			$('[name="'+name+'"]').html("<option></option>");
			$('[name="'+name+'"]').select2("val", "");
		}

	});
	$('#numOffer').hide();
	$('.select2-3').trigger("change");
	fireChangeOffers = true;
}
