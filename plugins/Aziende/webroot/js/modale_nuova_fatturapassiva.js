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
		$('#myModalFatturaPassiva #idFornitore').select2({
       		 language: 'it',
			 width: '100%',
			 placeholder: 'Seleziona un azienda',
			 closeOnSelect: true,
			 dropdownParent: $("#myModalFatturaPassiva #idFornitoreParent"),
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

   $('#myModalFatturaPassiva #idOrder').select2({
      language: 'it',
      width: '100%',
	  placeholder: 'Seleziona un ordine',
      allowClear: true,
      closeOnSelect: true,
      dropdownParent: $("#myModalFatturaPassiva #idOrderParent"),
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
        saveFormFatturaFornitore('myFormFatturaPassiva');
    }

	});

	$('input').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});
  $('select').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});


	$('#importXml').change(function(){
			if($(this).val() != ''){
					var formData= new FormData($('#myFormFatturaPassiva')[0]);

					$.ajax({
							url : pathServer + "aziende/Ws/importXmlPassiveInvoice/",
							type: "POST",
							data:formData,
							processData: false,
							contentType: false,
							dataType: 'json'
					}).done(function(res) {
							if(res.result == 'OK'){ 
								if(res.data.id_issuer != ''){ 
									$("#myFormFatturaPassiva #idFornitore").append('<option value="'+res.data.id_issuer+'">'+res.data.denominazione_issuer+'</option>');
									$('#myFormFatturaPassiva #idFornitore').val(res.data.id_issuer).trigger("change");
								}

								$('input[name="emission_date"]').val(res.data.emission_date);
								$('input[name="num"]').val(res.data.num);
								$('input[name="amount_noiva"]').val(res.data.amount_noiva);
								$('input[name="amount_iva"]').val(res.data.amount_iva);
								$('input[name="amount"]').val(res.data.amount);
								$('input[name="amount_topay"]').val(res.data.amount_topay);
								$('input[name="description"]').val(res.data.description);
								$('input[name="due_date"]').val(res.data.due_date);
							}else{
									$('#importXml').val('');
									alert(res.msg);
							}
					}).fail(function(richiesta,stato,errori) {
							alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
					});
			}
	});


});

$(document).on('hidden.bs.modal','#myModalFatturaPassiva', function (e) {
	  clearModale();
  	//reloadTableSedi();
});

function saveFormFatturaFornitore(idForm){
  $(".inputNumber").each(function(){
       var val = $(this).val().replace(/,/g, ".");
       $(this).val(val);
   });
	var formData= new FormData(document.getElementById(idForm) );

    if(formData.get('metodo') == ''){
        formData.set('metodo', 'not');
    }else{
        metodo = $('select[name="metodo"] option[value="'+$('select[name="metodo"]').val()+'"]').html();
        formData.set('metodo', metodo);
    }

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
                if($("#table-invoicepayable").length){
					reloadTableFatturePassive();
				}else{
					location.reload();
				}
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

    	        	$('#myFormFatturaPassiva #idOrder').html(option);

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
