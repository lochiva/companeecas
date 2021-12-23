// $.fn.modal.Constructor.prototype.enforceFocus = function() {};
$.fn.datepicker.dates['it'] = {
    days: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'],
    today: "Oggi",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};
//########################################################################################################################
// All caricamento della pagina carico i gradi di parentela
$(document).ready(function(){
  $.ajax({
	    url : pathServer + "progest/ws/familiari/getParentele",
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
            $.each(data.data, function(index,value){
               $('#gradoParentela').append('<option value="'+htmlEntities(value.id)+'">'+htmlEntities(value.name)+'</option>');
            });

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});
  // Imposto l'helper dei luoghi nei due form. Funzione in general.js
  luoghiAutocomplete('myFormPerson',{ prefix: 'extension'});
  luoghiAutocomplete('myFormFamiliare',{ all: true });

	$('.datepicker-birthdate').datepicker({
			language: 'it',
			autoclose:true,
			todayHighlight:true,
      startView: 2

	});
  //########################################################################################################################
  // Salvataggio del Familiare
  $('#saveFormFamiliare').click(function(){

     if(formValidation('myFormFamiliare')){
         saveFormFamiliare('myFormFamiliare');
     }

  });

  //########################################################################################################################
  // Salvataggio della persona
	$('#salvaNuovaPersona').click(function(){

    if(formValidation('myFormPerson')){
        saveFormPerson('myFormPerson');
    }

 });
 //########################################################################################################################
 //Bind del change degli input e select per togliere la classe dell'errore
	 $('input').change(function(){
		 $(this).parentsUntil('div.input').parent().removeClass('has-error');
	 });
		$('select').change(function(){
		 $(this).parentsUntil('div.input').parent().removeClass('has-error');
	 });




});
// clear della modale in caso di chiusura della modale
$(document).on('hidden.bs.modal','#myModalPerson', function (e) {
	  clearModale();
});
// clear form familiare
$(document).on('click','.familiari-refresh', function (e) {
    window.fillingModal = true;
	  $('#myFormFamiliare')[0].reset();
    $('#myFormFamiliare select').trigger('change');
    $('#myFormFamiliare [name="id"]').val("");
    window.fillingModal = false;
});

//########################################################################################################################
//Gestione Cancella Familiare
$(document).on('click','.delete-familiare',function(e){

	e.preventDefault();
  var element = this;
	if(confirm('Si è sicuri di voler cancellare il familiare?')){
    var callback = function(){
      $(element).parent().parent().remove();
      if(!window.inPeoplePage){
        loadFamiliari($('#idPerson').val(),$('#idRichiedente').val());
      }
    };
		deleteFamiliare($(element).attr('data-id'),callback);

	}

});

//########################################################################################################################
//Gestione Edit Familiare
$(document).on('click','.edit-familiare',function(e){

	disableInputModale();
	var idFamiliare = $(this).attr('data-id');
	loadInputFamiliare(idFamiliare);

	enableInputModale();

});

function saveFormPerson(idForm,callBack){

	$(".inputNumber").each(function(){
       var val = $(this).val().replace(/,/g, ".");
       $(this).val(val);
   });
	var formData= new FormData(document.getElementById(idForm) );

	$.ajax({
	    url : pathServer + "progest/ws/people/save",
	    type: "POST",
	    dataType: "json",
			data:formData,
			processData: false,
      contentType: false,
	    success : function (data,stato) {

	        if(data.response == "OK"){
            if(window.inPeoplePage){
                reloadTablePeople();
            }else{
                loadPersona(data.data.id,$('#idRichiedente').val());
            }
            $('#'+idForm+' [name="id"]').val(data.data.id);
            $('#toFamiliariTab').removeAttr('disabled');
            //$('.close-modal-person').click();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function saveFormFamiliare(idForm){

   var formData= new FormData(document.getElementById(idForm) );
   formData.set('id_person',$('#myFormPerson [name="id"]').val());
   $.ajax({
 	    url : pathServer + "progest/ws/familiari/save",
 	    type: "POST",
 	    dataType: "json",
 			data:formData,
 			processData: false,
      contentType: false,
 	    success : function (data,stato) {

 	        if(data.response == "OK"){
             appendFamiliareTable(data.data);
             $('#'+idForm)[0].reset();
             $('.familiari-collapse').click();
             if(!window.inPeoplePage){
                loadFamiliari($('#myFormPerson [name="id"]').val(),$('#idRichiedente').val());
             }
 	        }else{
 	        	alert(data.msg);
 	        }
 	    },
 	    error : function (richiesta,stato,errori) {
 	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
 	    }
 	});

}

function appendFamiliareTable(data)
{
    $('#table-familiari tbody').html('');
    var columns = '';
    $.each(data, function(index,familiari){
        var parentela = $("#gradoParentela > option[value='"+familiari.id_grado_parentela+"']").text();
        columns+='<tr><td>'+htmlEntities(familiari.name)+'</td><td>'+htmlEntities(familiari.surname)+'</td><td>'+parentela+'</td>';
        columns+='<td>'+htmlEntities(familiari.tel)+'</td><td>'+htmlEntities(familiari.cell)+'</td><td>'+htmlEntities(familiari.email)+'</td><td>';
        columns+='<a data-id="'+htmlEntities(familiari.id)+'" class="btn btn-xs btn-default edit-familiare"><i class="fa fa-pencil"></i></a>';
        columns+='<a data-id="'+htmlEntities(familiari.id)+'" class="btn btn-xs btn-default delete-familiare"><i class="fa fa-trash"></i></a></td></tr>';
    });
    $('#table-familiari tbody').append(columns);
}

function clearModale()
{
    window.fillingModal = true;
  	$('#myFormPerson')[0].reset();
    $('#myFormFamiliare')[0].reset();
  	$('#myFormFamiliare [name="id"], #myFormPerson [name="id"]').val("");
    $('.datepicker-birthdate').datepicker('update');
    $('#toFamiliariTab').attr('disabled','disabled');
    $('#table-familiari tbody').html('<td colspan="7">Nessun familiare o riferimento presente</td>');
    if($('.familiari-collapse i').hasClass('fa-minus')){
      $('.familiari-collapse').click();
    }
    $('#toPerosnTab').click();
    $('#myFormPerson input, #myFormFamiliare input').trigger('change');
    $('#myFormPerson select, #myFormFamiliare select').trigger('change');
    window.fillingModal = false;
}

function loadInputModalePerson(idPerson){

	$.ajax({
	    url : pathServer + "progest/ws/people/get/" + idPerson,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
						window.fillingModal = true;
            // Funzione di riempimnento del form. In general.js
						fillFormGeneral(data.data, 'myFormPerson');

						var extension = data.data.extension;
						if(extension !== undefined && extension !== null ){

              fillLuoghiAutocomplete({provincia:extension.provincia,comune:extension.comune,cap:extension.cap},
                {names:{provincia:'extension[provincia]',comune:'extension[comune]',cap:'extension[cap]'}});

								//$('.select2').trigger("change");
						}
            if(data.data.familiari != null && data.data.familiari != undefined && data.data.familiari.length > 0){
              appendFamiliareTable(data.data.familiari);
            }
            $('#toFamiliariTab').removeAttr('disabled');
						window.fillingModal = false;
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function deleteFamiliare(id,callback){

	$.ajax({
	    url : pathServer + "progest/ws/familiari/delete/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
  	        if(callback !== undefined){
              callback();
            }

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadInputFamiliare(idfamiliare){

	$.ajax({
	    url : pathServer + "progest/ws/familiari/get/" + idfamiliare,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
						window.fillingModal = true;
            // Funzione di riempimnento del form. In general.js
						fillFormGeneral(data.data, 'myFormFamiliare');
            fillLuoghiAutocomplete({provincia:data.data.provincia,comune:data.data.comune,cap:data.data.cap},
              {names:{provincia:'provincia',comune:'comune',cap:'cap'}, idForm:'myFormFamiliare'});

            if($('.familiari-collapse i').hasClass('fa-plus')){
              $('.familiari-collapse').click();
            }
            $('#myFormFamiliare select').trigger('change');
						window.fillingModal = false;
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}
