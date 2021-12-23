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

	if(idCliente == 'all'){
		$('#myModalFatturaAttiva #idPayer').select2({
			language: 'it',
			width: '100%',
			placeholder: 'Seleziona un azienda',
			closeOnSelect: true,
			dropdownParent: $("#idPayerParent"),
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

	$('#myModalFatturaAttiva #idPayer').change(function(){
		$('#myModalFatturaAttiva #idOrder').select2({
			language: 'it',
			width: '100%',
			placeholder: 'Seleziona un ordine',
			closeOnSelect: true,
			dropdownParent: $("#myModalFatturaAttiva #idOrderParent"),
			minimumInputLength: 3,
			ajax: {
				url: pathServer+'aziende/ws/autocompleteOrders/'+$('#myModalFatturaAttiva #idPayer').val(),
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
	});


	//al cambio importi generali setta importo totale e da pagare
	$('[name="amount_noiva"], [name="amount_iva"], [name="bolli"]').change(function(){ 
		var amount = Number($('[name="amount_noiva"]').val().replace(/,/g, "."));
		amount += Number($('[name="amount_iva"]').val().replace(/,/g, "."));
		amount += Number($('[name="bolli"]').val().replace(/,/g, "."));
		$('[name="amount"]').val(amount.toFixed(2).toString().replace(/\./g, ","));
		$('[name="amount_topay"]').val($('[name="amount"]').val());
	});

	//al cambio importo netto di un articolo 
	$(document).on('change', 'input[data-input="amount_noiva"]', function(){
		//importo netto generale
		var amount_noiva = 0;
		$('input[data-input="amount_noiva"]').each(function(){
			var quantity = Number($(this).parentsUntil('.invoice-article').find('input[data-input="quantity"]').val().replace(/,/g, "."));
			amount_noiva += Number($(this).val().replace(/,/g, ".")) * quantity;
		})
		$('[name="amount_noiva"]').val(amount_noiva.toFixed(2).toString().replace(/\./g, ",")).trigger('change');

		//importo iva dell'articolo
		amount_noiva = Number($(this).val().replace(/,/g, ".")); 
		var iva_val = $(this).parentsUntil('.invoice-articles').find('[data-input="cod_iva"]').val();
		var iva_html = $(this).parentsUntil('.invoice-articles').find('[data-input="cod_iva"] option[value="'+iva_val+'"]').html(); 
		if(typeof iva_html != 'undefined'){
			iva = Number(iva_html.split(/(\s+)/)[0])/100;
		}else{
			iva = 0;
		}

		var amount_iva = amount_noiva * iva;
		
		$(this).parentsUntil('.invoice-articles').find('[data-input="amount_iva"]').val(amount_iva.toFixed(2).toString().replace(/\./g, ",")).trigger('change');

		//importo totale articolo
		var amount_tot = amount_noiva + amount_iva;
		
		$(this).parentsUntil('.invoice-articles').find('[data-input="amount_tot"]').val(amount_tot.toFixed(2).toString().replace(/\./g, ",")).trigger('change');
	});

	//al cambio importo iva di un articolo
	$(document).on('change', 'input[data-input="amount_iva"]', function(){
		//importo iva generale
		var amount_iva = 0;
		$('input[data-input="amount_iva"]').each(function(){
			var quantity = Number($(this).parentsUntil('.invoice-article').find('input[data-input="quantity"]').val().replace(/,/g, "."));
			amount_iva += Number($(this).val().replace(/,/g, ".")) * quantity;
		})
		$('[name="amount_iva"]').val(amount_iva.toFixed(2).toString().replace(/\./g, ",")).trigger('change');

		//importo totale articolo
		var amount_iva = Number($(this).val().replace(/,/g, "."));
		amount_noiva = Number($(this).parentsUntil('.invoice-articles').find('[data-input="amount_noiva"]').val().replace(/,/g, ".")); 

		var amount_tot = amount_noiva + amount_iva;
		
		$(this).parentsUntil('.invoice-articles').find('[data-input="amount_tot"]').val(amount_tot.toFixed(2).toString().replace(/\./g, ",")).trigger('change');
	});

	//al cambio dell'iva di un articolo 
	$(document).on('change', 'select[data-input="cod_iva"]', function(){
		//importo iva di un articolo 
		var amount_noiva = Number($(this).parentsUntil('.invoice-articles').find('[data-input="amount_noiva"]').val().replace(/,/g, ".")); 
		var iva_html = $(this).find('option[value="'+$(this).val()+'"]').html(); 
		if(typeof iva_html != 'undefined'){
			iva = Number(iva_html.split(/(\s+)/)[0])/100;
		}else{
			iva = 0;
		}

		var amount_iva = amount_noiva * iva;
		
		$(this).parentsUntil('.invoice-articles').find('[data-input="amount_iva"]').val(amount_iva.toFixed(2).toString().replace(/\./g, ",")).trigger('change');		
	});

	//al cambio quantità di un articolo 
	$(document).on('change', 'input[data-input="quantity"]', function(){
		//importo netto generale
		var amount_noiva = 0;
		$('input[data-input="amount_noiva"]').each(function(){
			var quantity = Number($(this).parentsUntil('.invoice-article').find('input[data-input="quantity"]').val().replace(/,/g, "."));
			amount_noiva += Number($(this).val().replace(/,/g, ".")) * quantity;
		})
		$('[name="amount_noiva"]').val(amount_noiva.toFixed(2).toString().replace(/\./g, ",")).trigger('change');

		//importo iva generale
		var amount_iva = 0;
		$('input[data-input="amount_iva"]').each(function(){
			var quantity = Number($(this).parentsUntil('.invoice-article').find('input[data-input="quantity"]').val().replace(/,/g, "."));
			amount_iva += Number($(this).val().replace(/,/g, ".")) * quantity;
		})
		$('[name="amount_iva"]').val(amount_iva.toFixed(2).toString().replace(/\./g, ",")).trigger('change');

		//importo totale articolo
		var amount_tot = amount_noiva + amount_iva;
		
		$(this).parentsUntil('.invoice-articles').find('[name="amount_tot"]').val(amount_tot.toFixed(2).toString().replace(/\./g, ",")).trigger('change');
	});

	$('#salvaFatturaAttiva').click(function(){

		if(formValidation('myFormFatturaAttiva')){
        saveFormFatturaCliente('myFormFatturaAttiva');
    }

	});

	$('input').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});

  	$('select').change(function(){
		$(this).parentsUntil('div.form-group').parent().removeClass('has-error');
	});

	//Accordion articoli
	$(document).on('click', '.article-accordion', function(e){
		e.preventDefault();

		this.classList.toggle("active");

		var panel = $(this).parent().find('.article-accordion-panel')[0];
		if (panel.style.maxHeight){
			panel.style.maxHeight = null;
		} else {
			panel.style.maxHeight = panel.scrollHeight + "px";
		} 
	});

	//Aggiunta articolo
	$('.add-article').click(function(e){
		e.preventDefault();

		var last_article = $('.invoice-article').last();
		var counter = parseInt(last_article.attr('data-counter'), 10) + 1;

		var html = '<div class="invoice-article" data-counter="'+counter+'">';
		html += last_article.html();
		html += '</div>';

		$('.invoice-articles').append(html);

		var new_article = $('.invoice-article').last();

		new_article.find('input, select').each(function(){
			var field = $(this).attr('data-input');
			$(this).attr('name', 'articoli['+counter+']['+field+']');
			$(this).val('').trigger('change');
			if($(this).hasClass('inputNumber')){
				$(this).trigger('focusout');
			}
		});

		new_article.find('.article-accordion .article-title').html('Articolo ' + counter);	

		if(new_article.find('.article-accordion').hasClass('active')){
			new_article.find('.article-accordion').trigger('click');
		}

		new_article.find('.delete-article').attr('data-counter', counter);

		$('[name="articoli['+counter+'][id]"]').remove();
	});

	//Elimina articolo
	$(document).on('click', '.delete-article', function(){
		if($('.invoice-article').length == 1){
			alert('Impossibile eliminare l\'articolo. E\' obbligatorio che ci sia almeno un articolo.');
		}else{
			var confirmation = confirm('Si è sicuri di voler cancellare l\'articolo?');
			if(confirmation){
				var counter = $(this).attr('data-counter');
				var input_id = $('[name="articoli['+counter+'][id]"]');
				if(input_id.length > 0){
					var article_id = input_id.val();

					$.ajax({
						url: pathServer + "aziende/Ws/deleteArticleInvoice/",
						type: "POST",
						data: {article_id: article_id},
						dataType: 'json',
						success : function (res,stato) {
							if(res.response == "OK"){
								$('.invoice-article[data-counter="'+counter+'"]').remove();
								$('input[data-input="amount_noiva"]').trigger('change');
								$('input[data-input="amount_iva"]').trigger('change');
							}else{
								alert(res.msg);
							}
						},
						error : function (richiesta,stato,errori) {
							alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
						}
					});
				}else{
					$('.invoice-article[data-counter="'+counter+'"]').remove();
					$('input[data-input="amount_noiva"]').trigger('change');
					$('input[data-input="amount_iva"]').trigger('change');
				}
			}
		}
	});

	//Cambio titolo articolo al cambio del nome
	$(document).on('focusout', '.article-name', function(){
		var article = $(this).parentsUntil('.invoice-articles');
		article.find('.article-title').html($(this).val());
	}); 

});

$(document).on('hidden.bs.modal','#myModalFatturaAttiva', function (e) {
	  clearModale();
});

function saveFormFatturaCliente(idForm){
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
	    url : pathServer + "aziende/Ws/saveFatturaCliente/",
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
                if($("#table-invoicepayable-attiva").length){
					reloadTableFattureAttive();
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

function loadOrdersAzienda(id, selectedId){
  	if(id != null && id != undefined){
		$.ajax({
			url : pathServer + "aziende/Ws/getOrdersAzienda/" + id  ,
			type: "GET",
			async: false,
			dataType: "json",
			data:{},
			success : function (data,stato) {

				if(data.response == "OK"){
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
