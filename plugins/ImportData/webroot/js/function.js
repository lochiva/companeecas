function preElaborazione(){

	var form_data = new FormData();

	var file_data = $('#upload-data').prop('files')[0];
	form_data.append('file', file_data);

	var table = $('#table-name').val();
	form_data.append('table', table);

	var heading = false;
	if($('#heading').is(':checked')){
		heading = true;
	}
	form_data.append('heading', heading);

	var delimiter = $('#delimiter').val();
	form_data.append('delimiter', delimiter);

	var success = false;

	$.ajax({
		url : pathServer + "import-data/Ws/preElaborazione",
		type: "POST",
		dataType: "json",
		data: form_data,
		contentType: false,
		processData: false,
		success : function (data,stato) {

			if(data.response == "OK"){
				success = true;

				var html = '';

				//Tabella per l'assegnazione dei campi file ai campi tabella
				html += '<table class="table table-bordered table-fields">';
				html += '<thead><tr>';
				html += '<th>Campi tabella</th>';
				html += '<th><span style="font-size:20px; color:red;">*</span></th>';
				html += '<th>Campi file</th>';
				html += '<th>Filtri</th>';
				html += '<th>Valore file</th>';
				html += '</tr></thead>';
				html += '<tbody>';

				//Valori default prima riga file (non modificati dai filtri)
				html += '<tr class="default-values" hidden><td>';
				i = 0;
				data.data.columns.forEach(function(columns){
					html += '<span data-column-value="'+i+'">'+columns.value+'</span>';
					i += 1;
				});
				html += '</td></tr>';

				data.data.fields.forEach(function(field){

						if(field.active == true){
							html += '<tr data-field="'+field.field+'">';
						}else{
							html += '<tr data-field="'+field.field+'" class="no-import">';
						}

						//Nome campo tabella e relativo tipo
						html += '<td class="table-field" style="width:24%;">'+field.field+' <br /> [<span class="field-type">'+field.field_type+'</span>]</td>';

						//Obbligatorietà campo
						if(field.active == true){
							html += '<td class="required-field" style="width:4%;"><input type="checkbox" name="required-field" ></td>';
						}else{
							html += '<td class="required-field" style="width:4%;"></td>';
						}

						//Colonne del file
						html += '<td class="file-column" style="width:24%;">';
						if(field.active == true){
							html += '<select class="form-control" name="column-number">';
							html += '<option value="">Seleziona colonna</option>';
							var i = 0;
							data.data.columns.forEach(function(column){
								html += '<option value="'+i+'">'+column.field+'</option>';
								i += 1;
							});
							html += '</select>';
						}else{
							html += 'Campo non importabile';
						}
						html += '</td>';

						//Filtri applicabili
						html += '<td class="file-filters" style="width:24%;">';
						if(field.active == true){
							html += '<select class="form-control" name="filter">';
							html += '<option value="">Seleziona filtro</option>';
							data.data.filters.forEach(function(filter){
								if(data.data.filter_labels[data.data.filters.indexOf(filter)] == undefined){
									filter_label = filter;
								}else{
									filter_label = data.data.filter_labels[data.data.filters.indexOf(filter)];
								}
								html += '<option value="'+filter+'">'+filter_label+'</option>';
							});
							html += '</select>';
							html += '<span class="filter-param" hidden></span>'
						}else{
							html += 'Campo non importabile';
						}
						html += '</td>';

						//Valori prima riga del file (modificati dai filtri)
						html += '<td class="file-first-value" style="width:24%;">';
						if(field.active == true){
							i = 0;
							data.data.columns.forEach(function(columns){
								html += '<span data-column-value="'+i+'" class="hide">'+columns.value+'</span>';
								i += 1;
							});
							html += '<i class="glyphicon glyphicon-remove-sign hide" style="color:red; font-size:18px;"></i>';
							html += '<i class="glyphicon glyphicon-ok-sign hide" style="color:green; font-size:18px;"></i>';
						}else{
							html += 'Campo non importabile';
						}
						html += '</td></tr>';

				});

				html += '</tbody>';
				html += '</table>';
				html += '<input type="text" name="file_csv" value="'+data.data.file+'" hidden />';
				html += '<button class="btn btn-primary btn-elaborazione" id="elaborazione" type="button">ELABORA '+data.data.total_rows+' RIGHE</button>';
				html += '<button class="btn btn-light btn-configuration" id="save-configuration" type="button">SALVA CONFIGURAZIONE</button>';

				$('#data-fields').html(html);

				$('#div-configs').show();

			}else{
				success = false;
				alert(data.msg);
			}

		},
		error : function (richiesta,stato,errori) {
			success = false;
			alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
		}
	});

	return success;

}

function elaborazione(){

	var file = $('input[name="file_csv"]').val();

	var table = $('#table-name').val();

	//Check prima riga del file = intestazione
	var heading = false;
	if($('#heading').is(':checked')){
		heading = true;
	}

	//Check elimina vecchio contenuto tabella
	var overwrite = false;
	if($('#overwrite').is(':checked')){
		overwrite = true;
	}

	var delimiter = $('#delimiter').val();

	//Costruisco oggetto con i valori da inserire nei vari campi della tabella e i filtri da applicare
	var data_fields = [];
	var i = 0;
	$('.table-fields tr[data-field]').each(function(){
		if(!$(this).hasClass('no-import')){
			data = {
				table_field: $(this).attr('data-field'),
				required_field: $(this).find($('td.required-field input')).is(':checked'),
				file_column: $(this).find($('td.file-column select')).val(),
				filter: $(this).find($('td.file-filters select')).val(),
				param: $(this).find($('td.file-filters span.filter-param')).html()
			};
			data_fields[i] = data;
			i += 1;
		}
	});

	var request_data = {
		file: file,
		table: table,
		heading: heading,
		overwrite: overwrite,
		delimiter: delimiter,
		data_fields: data_fields
	};

	$.ajax({
		url : pathServer + "import-data/Ws/elaborazione",
		type: "POST",
		dataType: "json",
		data: request_data,
		success : function (data,stato) {

			if(data.response == "OK"){

				var html = '<h3>Importazione dei dati eseguita.</h3>';
				html += '<h3>'+data.data.executed_rows+' righe su un totale di '+data.data.total_rows+' sono state caricate.</h3>'

				//Mostro messaggio di avvenuto caricamento e resetto il resto
				$('#data-fields').html(html);

				$('#table-name').val('');
				$('#upload-data').val('');
				$('#heading').prop('checked', false);
				$('#overwrite').prop('checked', false);
				$('#pre-elaborazione').hide();
				$('#div-configs').hide();

			}else{
				alert(data.msg);
			}

		},
		error : function (richiesta,stato,errori) {
			alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
		}
	});

}

function checkValue(data_field){

	var value = $('tr[data-field="'+data_field+'"] span.show').html();
	var field_type = $('tr[data-field="'+data_field+'"] span.field-type').html();

	$.ajax({
		url : pathServer + "import-data/Ws/checkValue",
		type: "POST",
		dataType: "json",
		data: {value:value, field_type:field_type},
		success : function (data,stato) {

			if(data.response == "OK"){

				if(data.data){
					//Tipologia valore nel file corrisponde alla tipologia del campo della tabella
					//Nascondo qualsiasi icona precedentemente mostrata e mostro icona OK verde
					$('tr[data-field="'+data_field+'"] i.show').addClass('hide');
					$('tr[data-field="'+data_field+'"] i.show').removeClass('show');
					$('tr[data-field="'+data_field+'"] i.glyphicon-ok-sign').removeClass('hide');
					$('tr[data-field="'+data_field+'"] i.glyphicon-ok-sign').addClass('show');
				}else{
					//Tipologia valore nel file NON corrisponde alla tipologia del campo della tabella
					//Nascondo qualsiasi icona precedentemente mostrata e mostro icona KO rossa
					$('tr[data-field="'+data_field+'"] i.show').addClass('hide');
					$('tr[data-field="'+data_field+'"] i.show').removeClass('show');
					$('tr[data-field="'+data_field+'"] i.glyphicon-remove-sign').removeClass('hide');
					$('tr[data-field="'+data_field+'"] i.glyphicon-remove-sign').addClass('show');
				}

				//Controllo, per le righe la cui select colonna file ha un valore, se tutte le icone mostrate sono OK verde
				var elaborazione_abilitata = true;
				$('select[name="column-number"]').each(function(){
					var data_field = $(this).parent().parent().attr('data-field');
					if($(this).val() != ''){
						if($('tr[data-field="'+data_field+'"] i.glyphicon-ok-sign').hasClass('hide')){
							elaborazione_abilitata = false;
						}
					}
				});

				//Se tutte icone verdi abilito button per l'elaborazione
				if(!elaborazione_abilitata){
					$('#elaborazione').prop('disabled','disabled');
					$('#elaborazione').prop('title','Errore con la tipologia dei valori rispetto ai campi del database. ');
				}else{
					$('#elaborazione').prop('disabled', false);
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

function applyFilter(filter, value, data_field, param){

	$.ajax({
		url : pathServer + "import-data/Ws/applyFilter",
		type: "POST",
		dataType: "json",
		data: {filter:filter, value:value, param:param},
		success : function (data,stato) {

			if(data.response == "OK"){
				$('tr[data-field="'+data_field+'"] span.show').html(data.data);
				checkValue(data_field);
			}else{
				alert(data.msg);
			}

		},
		error : function (richiesta,stato,errori) {
			alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
		}
	});

}


function getConfigurations(table){
	$.ajax({
		url : pathServer + "import-data/Ws/getConfigurations/"+table,
		type: "GET",
		dataType: "json",
		success : function (data,stato) {

			$('#configurations').html('');

			if(data.response == "OK"){
				var html = '<option value=""></option>';
				data.data.forEach(function(config){
					html += '<option value="'+config.id+'">'+config.name+'</option>';
				});
				$('#configurations').append(html);
			}

		},
		error : function (richiesta,stato,errori) {
			alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
		}
	});
}

function loadConfiguration(id_configuration){

	$.ajax({
		url : pathServer + "import-data/Ws/loadConfiguration/"+id_configuration,
		type: "GET",
		dataType: "json",
		success : function (data,stato) {

			if(data.response == "OK"){

				//Setto i check dell'obbligatorietà
				required = data.data.required;
				Object.keys(required).forEach(function(field_table){
					if(required[field_table] == 'true')
						$('tr[data-field="'+field_table+'"] input[name="required-field"]').prop('checked', true);
					else{
						$('tr[data-field="'+field_table+'"] input[name="required-field"]').prop('checked', false);
					}
				});

				//Setto le select delle colonne da file
				fields = data.data.fields;
				Object.keys(fields).forEach(function(field_table){
					$('tr[data-field="'+field_table+'"] select[name="column-number"]').val(fields[field_table]).trigger('change');
				});

				//Setto le select dei filtri
				functions = data.data.functions;
				Object.keys(functions).forEach(function(field_table){
					if(functions[field_table] != ''){
						$('tr[data-field="'+field_table+'"] select[name="filter"]').val(functions[field_table]).trigger('change');
					}

				});

			}else{
				alert(data.msg);
			}

		},
		error : function (richiesta,stato,errori) {
			alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
		}
	});

}
