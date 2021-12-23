$(document).ready(function(){
	//Array contentente le funzioni che necessitano di un parametro
	var filtersWithParam = ['add_prefix'];

	//Setto il separatore dei valori del faile di default
  	$('#delimiter').val(';');

	//Staticamente e dinamicamente al cambio di uno dei due input,
	//controllo se select tabella e imput file sono settati per mostrare button di pre-elaborazione

	if($('#table-name').val() != '' && $('#upload-data').val() != ''){
		$('#pre-elaborazione').show();
	}else{
		$('#pre-elaborazione').hide();
	}

	$('#table-name').change(function(){
		if($(this).val() != '' && $('#upload-data').val() != ''){
			$('#pre-elaborazione').show();
		}else{
			$('#pre-elaborazione').hide();
			$('#div-configs').hide();
		}

		if($(this).val() != ''){
			getConfigurations($(this).val());
		}else{
			$('#configurations').html('');
		}
	});

	$('#upload-data').change(function(){

		if(typeof($(this)[0].files[0]) != 'undefined'){
			var ftype = $(this)[0].files[0].type;

			switch(ftype){
	            case 'text/csv':
				case 'application/vnd.ms-excel':
					$("#res-file-type").html('<p style="color:green;">File caricato correttamente e pronto per l\'elaborazione.</p>');
	                break;
	            default:
	                $("#res-file-type").html('<p style="color:red;">Il tipo di file non è supportato! Caricare solamente file con estensione CSV.</p>');
					$(this).val('');
	        }
		}

		if($(this).val() != '' && $('#table-name').val() != ''){
			$('#pre-elaborazione').show();
		}else{
			$('#pre-elaborazione').hide();
			$('#div-configs').hide();
		}

	});

	$('#pre-elaborazione').click(function(){
		if($('#delimiter').val().length == 0){
			alert('Il separatore dei valori non può essere vuoto.');
		}else{
			$("#res-file-type").html('');
			preElaborazione();
		}
	});

	//Se è gia avvenuta la pre-elaborazione (quindi viene gia mostrata la tabella con campi tabella e valori del file)
	//al cambio di uno degli input (tabella, file, check prima riga intestazione) rilangio l'elaborazione per aggiornare la tabella
	$('#table-name, #upload-data, #heading, #delimiter').change(function(){
		if($('input[name="file_csv"]').length > 0){
			if($('#table-name').val() == '' || $('#upload-data').val() == ''){
				$('#data-fields').html('');
			}else{
				$('#configurations').val('');
				if(!preElaborazione()){
		          	$('#data-fields').html('');
		          	$('#div-configs').hide();
		        }
			}
		}
	});

	//Al cambio della select sulla colonna del file, resetto select filtri e mostro nuovo valore della prima riga
	$('#data-fields').on('change', 'select[name="column-number"]', function(){
		var column_value = $(this).val();
		var data_field = $(this).parent().parent().attr('data-field');

		$('tr[data-field="'+data_field+'"] span.show').addClass('hide');
		$('tr[data-field="'+data_field+'"] span.show').removeClass('show');
		$('tr[data-field="'+data_field+'"] select[name="filter"]').val('');

		if(column_value != ''){
			$('tr[data-field="'+data_field+'"] span[data-column-value="'+column_value+'"]').removeClass('hide');
			$('tr[data-field="'+data_field+'"] span[data-column-value="'+column_value+'"]').addClass('show');
			var default_value = $('tr.default-values span[data-column-value="'+column_value+'"]').html();
			$('tr[data-field="'+data_field+'"] span.show').html(default_value);

			checkValue(data_field);

		}else{
			$('tr[data-field="'+data_field+'"] i.show').addClass('hide');
			$('tr[data-field="'+data_field+'"] i.show').removeClass('show');
		}

	});

	//Al cambio del filtro prendo il valore prima riga di default, applico il filtro, controllo se tipo valore filtrato = tipo campo tabella
	$('#data-fields').on('change', 'select[name="filter"]', function(){
		var data_field = $(this).parent().parent().attr('data-field');
		var column_value = $('tr[data-field="'+data_field+'"] select[name="column-number"]').val();
		var default_value = $('tr.default-values span[data-column-value="'+column_value+'"]').html();
		$('tr[data-field="'+data_field+'"] span.show').html(default_value);
		var filter = $(this).val();

		if(filter != ''){
			if(column_value != ''){
				var value = $('tr[data-field="'+data_field+'"] span.show').html();
				var param = '';
				if(filtersWithParam.includes(filter)){
					var filter_name = $(this).find('option[value="'+filter+'"]').html();
					param = prompt('Inserisci il parametro per il filtro \''+filter_name+'\' dei valori del campo \''+data_field+'\'.');
					if(param){
						$('tr[data-field="'+data_field+'"] span.filter-param').html(param);
					}else{
						$(this).val('');
						return;
					}

				}else{
					$('tr[data-field="'+data_field+'"] span.filter-param').html('');
				}
				applyFilter(filter, value, data_field, param);
			}else{
				alert('Selezionare un campo del file prima di applicare un filtro.')
				$(this).val('');
			}
		}else{
			$('tr[data-field="'+data_field+'"] span.filter-param').html('');
			checkValue(data_field);
		}
	});

	//Al click su ELABORA controllo se almeno un campo è stato selezionato e se è checkato ELIMINA VECCHIO CONTENUTO
	$('#data-fields').on('click','#elaborazione', function(){

		if($('#delimiter').val().length == 0){
	      	alert('Il separatore dei valori non può essere vuoto.');
	    }else{

			if($('#delimiter').val().length == 0){
				alert('Il separatore dei valori non può essere vuoto.');
			}else{

				var no_file_fields = true;
				$('select[name="column-number"]').each(function(){
					if($(this).val() != ''){
						no_file_fields = false;
					}
				})

				if(no_file_fields == true){
					alert('Attenzione! Non è stato selezionato nessun campo file. Selezionarne almeno uno per proseguire.');
				}else{
					var required_no_file_field = false;
					var table_field = '';
					$('.table-fields tr[data-field]').each(function(){
						if(!$(this).hasClass('no-import')){
							if($(this).find($('td.required-field input')).is(':checked') && $(this).find($('td.file-column select')).val() == ''){
								required_no_file_field = true;
								table_field = $(this).attr('data-field');
								return;
							}
						}
					})

					if(required_no_file_field == true){
						alert('Attenzione! Il campo tabella \''+table_field+'\' è segnato obbligatorio. Selezionare un corrispondente campo file per proseguire.');
					}else{
						var confirm_overwrite = true;
						if($('#overwrite').is(':checked')){
							confirm_overwrite = confirm("Attenzione! L'opzione 'Elimina vecchio contenuto' è selezionata. Tutto il contenuto della tabella selezionata verrà cancellato. Continuare?");
						}
						if(confirm_overwrite == true){
							elaborazione();
						}
					}
				}
			}

		}

	});

	//Salvataggio della configurazione
	$('#data-fields').on('click','#save-configuration', function(){

		var configuration_name = prompt('Inserisci il nome che desideri dare alla configurazione.');
		if(configuration_name){
			var table = $('#table-name').val();

			//Costruisco oggetto con le coppie di valori campo tabella : obbligatorio
			var required = {};
			$('.table-fields tr[data-field]').each(function(){
				if(!$(this).hasClass('no-import')){
					required[$(this).attr('data-field')] = $(this).find($('td.required-field input')).is(':checked');
				}
			});

			//Costruisco oggetto con le coppie di valori campo tabella : campo file
			var fields = {};
			$('.table-fields tr[data-field]').each(function(){
				if(!$(this).hasClass('no-import')){
					fields[$(this).attr('data-field')] = $(this).find($('td.file-column select')).val();
				}
			});

			//Costruisco oggetto con le coppie di valori campo tabella : filtro da applicare
			var functions = {};
			$('.table-fields tr[data-field]').each(function(){
				if(!$(this).hasClass('no-import')){
					functions[$(this).attr('data-field')] = $(this).find($('td.file-filters select')).val();
				}
			});

			$.ajax({
				url : pathServer + "import-data/Ws/saveConfiguration",
				type: "POST",
				dataType: "json",
				data: {name: configuration_name, table: table, required: required, fields: fields, functions: functions},
				success : function (data,stato) {

					if(data.response == "OK"){
						getConfigurations(table);
					}else{
						alert(data.msg);
					}

				},
				error : function (richiesta,stato,errori) {
					alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
				}
			});
		}

	});

	//Carico la configuarzione quando selezionata
	$('#configurations').change(function(){
		if($(this).val() != ''){
			loadConfiguration($(this).val());
		}else{
			$('tr[data-field] select[name="column-number"]').val('').trigger('change');
		}
	});
});
