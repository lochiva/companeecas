$.fn.datepicker.dates['it'] = {
    days: ["Domenica", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};
$(document).ready(function(){

  $('.datepicker').datepicker({
      language: 'it',
      autoclose:true,
      todayHighlight:true

  });

	if(idFornitore == 'all'){
		$('#idFornitore').select2({
       language: 'it',
			 width: '100%',
			 placeholder: 'Seleziona un azienda',
			 closeOnSelect: true,
			 dropdownParent: $("#idFornitoreParent"),
			 minimumInputLength: 3,
			 ajax: {
				 url: pathServer+'aziende/ws/autocompleteAziende/fornitore',
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

   $('#idOrder').select2({
      language: 'it',
      width: '100%',
      placeholder: 'Seleziona un ordine',
      closeOnSelect: true,
      dropdownParent: $("#idOrderParent"),
      minimumInputLength: 3,
      ajax: {
        url: pathServer+'aziende/ws/autocompleteOrders',
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


   $('[name="amount"]').click(function(){
      var amount = Number($(this).val().replace(/,/g, "."));
      if(amount == '' || amount == 0 || amount == null ){
          var amount = Number($('[name="amount_noiva"]').val().replace(/,/g, "."));
          amount += Number($('[name="amount_iva"]').val().replace(/,/g, ".")) ;
          amount += Number($('[name="bolli"]').val().replace(/,/g, ".")) ;
          $(this).val(amount.toFixed(2).toString().replace(/\./g, ","));
      }
   });

	$('#salvaFatturaPassiva').click(function(){

		if(formValidation('myFormFatturaPassiva')){
        saveFormFattura('myFormFatturaPassiva');
    }

	});

	$('input').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});
  $('select').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});





});

$(document).on('hidden.bs.modal','#myModalFatturaPassiva', function (e) {
	  clearModale();
  	//reloadTableSedi();
});

function saveFormFattura(idForm){
  $(".inputNumber").each(function(){
       var val = $(this).val().replace(/,/g, ".");
       $(this).val(val);
   });
	var formData= new FormData(document.getElementById(idForm) );
	$.ajax({
	    url : pathServer + "aziende/Ws/saveFatturaFornitore/",
	    type: "POST",
	    data:formData,
      processData: false,
      contentType: false,
      dataType: 'json',
	    success : function (data,stato) {

	        if(data.response == "OK"){
	        	$('.close').click();
            if(data.msg != ''){
              alert(data.msg);
            }
            reloadTableFatture();
	        }else{
            $(".inputNumber").trigger('focusout');
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
          $(".inputNumber").trigger('focusout');
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function loadOrdersAzienda(id, selectedId)
{
  if(id != null && id != undefined){
    	$.ajax({
    	    url : pathServer + "aziende/Ws/getOrdersAzienda/" + id  ,
    	    type: "GET",
    	    async: false,
    	    dataType: "json",
    	    data:{},
    	    success : function (data,stato) {

    	        if(data.response == "OK"){
    						//console.log(data);
    	        	var option = '<option style="color: graytext;" value="0">Nessuno</option>';

    	        	for (var item in data.data) {

    								option += '<option id="order-num-'+data.data[item].id+'" idcontatto="'+data.data[item].id_contatto+'" value="' + data.data[item].id+ '">' + data.data[item].name + '</option>';

    						}

    	        	$('#idOrder').html(option);

    						if(selectedId !== undefined){
    							$('#idOrder').val(selectedId);

    						}
								$('#idOrder').change();

    	        }

    	    },
    	    error : function (richiesta,stato,errori) {
    	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    	    }
    	});
    }

}
