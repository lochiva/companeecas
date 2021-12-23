// $.fn.modal.Constructor.prototype.enforceFocus = function() {};
var fireChangeOrders = true;
var numServicesOrder = 0;
var numContactsOrder = 0;
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
$(document).ready(function(){

  luoghiAutocomplete('myFormOrder',{ all: true ,names:
    {provincia:'fatturazione_provincia',comune:'fatturazione_comune',cap:'fatturazione_cap'}
  });

	$('.datepicker-ready').datepicker({
			language: 'it',
			autoclose:true,
			todayHighlight:true

	});

	if(idAzienda == 'all'){
		$('#idAzienda').select2({
			 language: 'it',
			 width: '100%',
			 placeholder: 'Seleziona un committente',
			 closeOnSelect: true,
			 dropdownParent: $("#idAziendaParent"),
			 minimumInputLength: 3,
			 ajax: {
				 url: pathServer+'aziende/ws/autocompleteAziende/cliente',
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
	}
	$('#idPerson').select2({
		 language: 'it',
		 width: '100%',
		 placeholder: 'Seleziona una persona',
		 closeOnSelect: true,
		 dropdownParent: $("#idPersonParent"),
		 minimumInputLength: 3,
		 ajax: {
			 url: pathServer+'progest/ws/people/autocomplete',
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
  $.ajax({
	    url : pathServer + "progest/ws/orders/intModal",
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){
            $.each(data.data.person, function(index, value){
                $("#idPersonType").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
            $.each(data.data.invoice, function(index, value){
                $("#idInvoiceType").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
            $.each(data.data.services, function(index, value){
                $("[name='[id_service]']").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
            $.each(data.data.servicesFlexibility, function(index, value){
                $(".servicesFlexibility").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
            $.each(data.data.servicesApl, function(index, value){
                $(".servicesApl").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
            $.each(data.data.contactsRole, function(index, value){
                $(".contactsRole").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
            $.each(data.data.servicesFrequency, function(index, value){
                $(".servicesFrequency").append('<option value="'+value.id+'">'+value.text+'</option>');
            });
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

	$('#salvaNuovoOrder').click(function(){

	 if(formValidation('myFormOrder')){
		 //alert('salvo');
		saveFormOrder('myFormOrder');
	 }

 });

  $('#idPerson').change(function(){
      idPerson = $(this).val();
      if(idPerson != null && idPerson != '' && fireChangeOrders && !window.fillingModal){
        loadFamiliari(idPerson);
      }

  });
  $('#idInvoiceType').change(function(){
		var endDate = $('#myFormOrder [name="end_date"]').parent().parent().parent();
		if($(this).val() == 10){
        $('#myFormOrder [name="end_date"]').removeClass('required');
				$(endDate).hide();
		}else{
        $('#myFormOrder [name="end_date"]').addClass('required');
				$(endDate).show();
		}
  });
  $('.add-service').click(function(){
      addService();
  });

  $('.add-contacts').click(function(){
      addContact();
  });

});

$(document).on('change', 'input, select', function(){
    $(this).parentsUntil('div.form-group, div.input').parent().removeClass('has-error');
});

$(document).on('click', '.remove-service', function(){
  var idService = $(this).attr('data-id');
  var service = this;
  var callback = function(){
    numServicesOrder--;
    $(service).closest('.service-header').remove();
    if(numServicesOrder == 0){
        $('.service-bottom-button').hide();
    }
  };
  if(idService !== null && idService !== '' && idService !== undefined){
      if(confirm('Si è sicuri di voler rimuovere il servizio?')){
          deleteService(idService, callback);
      }
  }else{
      callback();
  }

});

$(document).on('click', '.remove-contact', function(){
  var idContact = $(this).attr('data-id');
  var contact = this;
  var callback = function(){
    numContactsOrder--;
    $(contact).closest('.contacts-header').remove();
    if(numContactsOrder == 0){
        $('.contacts-bottom-button').hide();
    }
  };
  if(idContact !== null && idContact !== '' && idContact !== undefined){
      if(confirm('Si è sicuri di voler rimuovere il contatto?')){
          deleteContact(idContact, callback);
      }
  }else{
      callback();
  }

});

$(document).on('hidden.bs.modal','#myModalOrder', function (e) {
	  clearModaleOrder();
  	//reloadTableSedi();
});

function saveFormOrder(idForm){

	var id = $('#'+idForm+' [name="id"]').val();
	$('#'+idForm+" .inputNumber").each(function(){
       var val = $(this).val().replace(/,/g, ".");
       $(this).val(val);
   });
	var formData= new FormData(document.getElementById(idForm) );

	$.ajax({
	    url : pathServer + "progest/ws/orders/save/" + id,
	    type: "POST",
	    dataType: "json",
			data:formData,
			processData: false,
      contentType: false,
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close').click();
            reloadTableOrder();
	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadContattiAzienda(element, role, selectedId)
{
  id = $('#idAzienda').val();
  if(id == null || id == '' || id == undefined){
    return;
  }
	$.ajax({
	    url : pathServer + "aziende/Ws/getContattiAzienda/" + id + '/' +role   ,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    data:{},
	    success : function (data,stato) {

	        if(data.response == "OK"){
						//console.log(data);
	        	var option = '<option style="color: graytext;" value="0"></option>';
	        	for (var item in data.data) {
								option += '<option value="' + data.data[item].id+ '">' + data.data[item].text + '</option>';
						}
	        	$(element).html(option);

						if(selectedId !== undefined){
							$(element).val(selectedId);
						}
            $(element).trigger('change');
	        }
	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function clearModaleOrder(){
  window.fillingModal = true;
  fireChangeOrders = false;
  $('.service-bottom-button').hide();
  $('.contacts-bottom-button').hide();
  $('.services-list').html('');
  $('.contacts-list').html('');
	$('#myFormOrder')[0].reset();
	$('#myFormOrder [name="id"]').val("");
  $('#myFormOrder [name="old_id"]').val("");
  $('.modal-duplicate').attr('data-id','');
  $('.modal-duplicate').hide();
	$('#myFormOrder .select2').each(function( index, value ) {
			var name = $(this).attr('name');
			if(name != undefined){
				$('[name="'+name+'"]').val('').trigger('change');
			}
	});
	$('#idContatto').html('<option style="color: graytext;" value="0">Seleziona contatto</option>');
  $('#myFormOrder select').trigger('change');
  $('#myFormOrder input').trigger('change');
  $('#idRichiedente').html('<option style="color:#999;" value="" selected>Seleziona un richiedente</option>');
  //$('.datepicker-ready').datepicker('startDate',new Date());
  $('.datepicker-ready').datepicker('update','');
  $('#click_tab_order_1').click();
	fireChangeOrders = true;
  numServicesOrder = 0;
  numContactsOrder = 0;
  window.fillingModal = false;

}

function addService(data)
{
  // Clono l'header, che è uguale per tutti, rimuovo la classe con cui clono e aggiungo alla lista dei servizi
  var clone = $('.clone-service-header').clone().removeClass('clone-service-header').removeAttr('hidden').attr('data',numServicesOrder);
  $(clone).find('[name="[id_service]"]').attr('name',"ServicesOrders["+numServicesOrder+"][id_service]").attr('data',numServicesOrder);
  $(clone).find('[name="[id_order]"]').attr('name',"ServicesOrders["+numServicesOrder+"][id_order]").attr('data',numServicesOrder);
  $('.services-list').append(clone);
  // Creo un evento al cambio del tipo di servizio, che clona l'apposita sezione e lo sostituisce nel div .fields del servizio
  $('[name="ServicesOrders['+numServicesOrder+'][id_service]"]').change(function(){
        var service = $(this).attr('data');
        var id = $(this).attr('data-id');
        var fieldsDiv = $(this).closest('.service-header').children('.fields');
        var fieldsClone = $('.clone-service-'+$(this).val()).clone().removeClass('clone-service-'+$(this).val());
        $(fieldsClone).find('input , textarea , select').each(function(){
            $(this).attr('name',"ServicesOrders["+service+"]"+$(this).attr('name'));
        });
        $(fieldsDiv).html(fieldsClone);
        if(id != undefined && id != null){
          $('[name="ServicesOrders['+service+'][id]"]').val(id);
        }
  });
  if(data !== undefined){
    $('[name="ServicesOrders['+numServicesOrder+'][id_service]"]').val(data.id_service).trigger('change');
    $('[name="ServicesOrders['+numServicesOrder+'][id_service]"]').attr('data-id',data.id);
    $('.service-header[data="'+numServicesOrder+'"]').find('.remove-service').attr('data-id',data.id);
    delete data.id_service;
    $.each(data, function(index,value){
        if($('[name="ServicesOrders['+numServicesOrder+']['+index+']"]').attr('type') == 'checkbox'){
            if(value == true || value == 1){
                $('[name="ServicesOrders['+numServicesOrder+']['+index+']"]').attr("checked",true);
            }
        }else if(typeof(value) == 'boolean'){
            $('[name="ServicesOrders['+numServicesOrder+']['+index+']"]').val(+value);
        }else{
            $('[name="ServicesOrders['+numServicesOrder+']['+index+']"]').val(value);
        }

    });
    numServicesOrder++;
  }else{
    $('[name="ServicesOrders['+numServicesOrder+'][id_service]"]').trigger('change');
    numServicesOrder++;
  }
  $('.service-bottom-button').show();

}

function deleteService(id,callback){

	$.ajax({
	    url : pathServer + "progest/ws/orders/deleteService/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {
	        if(data.response == "OK"){
              callback();
	        }else{
	        	  alert(data.msg);
	        }
	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function addContact(data)
{
  // Clono l'header, che è uguale per tutti, rimuovo la classe con cui clono e aggiungo alla lista dei servizi
  var clone = $('.clone-contacts').clone().removeClass('clone-contacts').removeAttr('hidden');
  $('.contacts-list').append(clone);
  $('.contacts-bottom-button').show();

  $(clone).find('input , textarea , select').each(function(){
      $(this).attr('name',"ContactsOrders["+numContactsOrder+"]"+$(this).attr('name')).attr('data',numContactsOrder);
  });
  $('[name="ContactsOrders['+numContactsOrder+'][id_role]"]').change(function(){
        num = $(this).attr('data');
        loadContattiAzienda('[name="ContactsOrders['+num+'][id_contatto]"]',$(this).val());
  });
  $('[name="ContactsOrders['+numContactsOrder+'][id_contatto]"]').change(function(){
        num = $(this).attr('data');
        loadDataContatto(num,$(this).val());
  });
  if(data !== undefined){

    $(clone).find('.remove-contact').attr('data-id',data.id);
    $.each(data, function(index,value){
        $('[name="ContactsOrders['+numContactsOrder+']['+index+']"]').val(value);
    });
    loadContattiAzienda('[name="ContactsOrders['+numContactsOrder+'][id_contatto]"]',data.id_role,data.id_contatto);
    numContactsOrder++;
  }else{
    $('[name="ContactsOrders['+numContactsOrder+'][id_role]"]').trigger('change');
    numContactsOrder++;
  }

}

function loadDataContatto(num,idContatto){
  $.ajax({
	    url : pathServer + "aziende/Ws/loadContatto/" + idContatto,
	    type: "GET",
	    async: false,
	    dataType: "json",
	    success : function (data,stato) {
          if($('[name="ContactsOrders['+num+'][tel]"]').val() == '' || $('[name="ContactsOrders['+num+'][tel]"]').val() == undefined){
            $('[name="ContactsOrders['+num+'][tel]"]').val(data.data.telefono);
          }
          if($('[name="ContactsOrders['+num+'][email]"]').val() == '' || $('[name="ContactsOrders['+num+'][email]"]').val() == undefined){
            $('[name="ContactsOrders['+num+'][email]"]').val(data.data.email);
          }

      },
      error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
    });
}

function deleteContact(id,callback){

	$.ajax({
	    url : pathServer + "progest/ws/orders/deleteContact/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {
	        if(data.response == "OK"){
              callback();
	        }else{
	        	  alert(data.msg);
	        }
	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}
