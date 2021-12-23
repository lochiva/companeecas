$(document).ready(function(){

	$("[data-mask]").inputmask();


	countTotalTime();

	/*#####################################################################################################################################*/
	/*Gestione cambio anno, devo ricaricare la pagina in modo da ricaricare i dati in modo corretto*/
	$('#select-year').change(function(){
		var year = $(this).val();
		var aziendaId = $('#azienda_id').val();
		window.open(pathServer + 'consulenza/pianificazione/edit/' + aziendaId + '/' + year,"_self");
	});

	/*#####################################################################################################################################*/
	/*Gestione visibilità dei job*/
	hideJobs();

	var attrId = $('[name="jobsattribute_id"]:checked').val();
	//alert(attrId);
	if(attrId == undefined){
		$('[name="jobsattribute_id"]:first').click();
		attrId = $('[name="jobsattribute_id"]:checked').val();
		//alert(attrId);
	}
	showHideJob(attrId);
	checkButtonCreateTasks(attrId);

	$('[name="jobsattribute_id"]').click(function(){

		var attrId = $(this).val();
		//alert('qui');
		showHideJob(attrId);

		//Verifico se posos abilitare i tasti di generazione
		checkButtonCreateTasks(attrId);

	});

	/*#####################################################################################################################################*/
	/*Gestione click su genera*/

	$('.create-tasks').click(function(){

		//Per prima cosa devo verificare se questi record esistono e non ho giocato con i radio per decidere se chiamare il save via ajax
		//per aggiornare il record oppure se non fare nulla e salvare poi con la post

		var justSaved = $('#order_id').val();

		if(justSaved != undefined){
			//Ho già eseguito un save pertanto i record dovrebbero già esistere, salvo abbia cambiato da radio

			var attrId = $('[name="jobsattribute_id"]:checked').val();
			var savedAttrId = $('#savedjobsattribute_id').val();

			if(savedAttrId == attrId){

				//sto effettivamente lavorando su dei record che nel db esistono pertanto posso salvare via ajax
				var ckErrore = false;
				var ckMsg = "";

				var idJob = $(this).attr('data-id');
				//alert('potrei salvare ' + idJob);

				//recupero i dati da salvare
				var id = $('[name="jobs[' + idJob + '][id]"]').val();
				var job_id = $('[name="jobs[' + idJob + '][job_id]"]').val();
				var totalTime = $('[name="jobs[' + idJob + '][totalTime]"]').val();
				var user_id = $('[name="jobs[' + idJob + '][user_id]"]').val();
				var process_id = $('[name="jobs[' + idJob + '][process_id]"]').val();

				//alert('id:' + id + ' time:' + totalTime + ' user:' + user_id + ' process:' + process_id);

				//Eseguo i controlli di validità sui dati inseriti
				if(!ckErrore && (totalTime == "" || totalTime == "00:00")){
					ckErrore = true;
					ckMsg = "Inserire un numero di ore da assegnare a questa causale.";
				}

				if(!ckErrore && process_id == 0){
					ckErrore = true;
					ckMsg = "Selezionare una periodicità.";
				}

				if(!ckErrore && user_id == 0){
					ckErrore = true;
					ckMsg = "Selezionare una assegnatario.";
				}

				if(!ckErrore){
					var objToSave = {id:id, job_id:job_id, totalTime:totalTime, user_id:user_id, process_id:process_id};

					saveJobsOrderData(objToSave);
					createTasks(objToSave);

				}else{
					alert(ckMsg);
				}

			}

		}

	});

	/*#####################################################################################################################################*/
	//Gestione del click su cancella

	$('.delete-tasks').click(function(){

		var id = $('[name="jobs[' + $(this).attr('data-id') + '][id]"]').val();
		var job_id = $('[name="jobs[' + $(this).attr('data-id') + '][job_id]"]').val();
		deleteTasksPlanned({id:id,job_id:job_id});

	});

	/*#####################################################################################################################################*/
	//Gestione del salvataggio automatico dei dati dall'uscita del campo dei jobs order

	$('.auto-save').change(function(){

		//alert('dovrei salvare');

		var justSaved = $('#order_id').val();

		if(justSaved != undefined){
			//Ho già eseguito un save pertanto i record dovrebbero già esistere, salvo abbia cambiato da radio

			var attrId = $('[name="jobsattribute_id"]:checked').val();
			var savedAttrId = $('#savedjobsattribute_id').val();

			if(savedAttrId == attrId){

				//alert('posso salvare');
				//sto effettivamente lavorando su dei record che nel db esistono pertanto posso salvare via ajax
				var ckErrore = false;
				var ckMsg = "";

				var id = $(this).attr('data-id');
				var job_id = $(this).attr('data-job-id');
				var field = $(this).attr('data-field');
				var value = $(this).val();
				var order_id = $(this).attr('data-order');

				//alert("id: " + id + "; field: " + field + "; value: " + value + "; job_id: " + job_id + "; order_id: " + order_id);

				if(field == "totalTime" && value == ""){
					ckErrore = true;
					ckMsg = "Inserire un numero di ore da assegnare a questa causale.";
				}

				/*
				//QUesto controllo non serve più.....
				if(field == "process_id" && value == 0){
					ckErrore = true;
					ckMsg = "Selezionare una periodicità.";
				}
				*/

				if(!ckErrore){
					//var objToSave = {id:id, field:value};
					var objToSave = {id:id, job_id:job_id, order_id:order_id};
					objToSave[field] = value;

					saveJobsOrderData(objToSave);

				}else{
					alert(ckMsg);
				}

			}
		}

	});

	/*############################################################################################################################################*/
	//Gestione del click sulla data di consegna del bilancino

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

	$('[name="dataConsegnaBilancino"]').datepicker({
        //beforeShowDay: DisableSunday,
        language: 'it',
        autoclose:true,
        todayHighlight:true

    });


	/*#####################################################################################################################################*/
	//Gestione del click su sblocca azienda
		$('#sblocca_azienda').click(function(){
			countTotalTime();
			if(confirm('Si è sicuri di voler sbloccare il cliente ?')){
				if($('p.total-time span').html() == '0:00'){
					sbloccaAzienda();
					$("#sblocca_azienda").hide();
				}else{
					alert("Il numero di ore totale deve essere a 0");
				}
			}


		});

});

function hideJobs(){

	$('#table-job tbody tr').hide();
	//$('#table-job tbody tr input, #table-job tbody tr select').attr('disabled','disabled');
}

function showHideJob(attrId){

	var data = "data-attr-" + attrId + '="1"';

	hideJobs()
	$('#table-job tbody tr[' + data + ']').show();
	//$('#table-job tbody tr[' + data + '] input, #table-job tbody tr[' + data + '] select').removeAttr('disabled');

}

function checkButtonCreateTasks(attrId){
	var savedAttrId = $('#savedjobsattribute_id').val();
	//alert(savedAttrId);

	var disabled = true;

	if(savedAttrId != undefined){
		if(savedAttrId == attrId){
			disabled = false;
		}
	}

	if(disabled){
		$('.create-tasks').addClass('disabled');
	}else{
		$('.create-tasks').removeClass('disabled');
		$('[data-locked="1"]').addClass('disabled');
	}

}

function saveJobsOrderData(objToSave){

	$.ajax({
		url: pathServer + "consulenza/ws/updateJobsOrderData",
		type: 'post',
		async: true,
		data: objToSave,
		dataType: 'json',
		success: function(result){

			if(result.response == "OK"){

				//Ho salvato quindi devo aggiornare il valore di ore da assegnare con quello che mi ha detto il server
				$('#toBeAssigned-' + objToSave.job_id).html(result.data.timeToBeAssigned);

				//A questo punto avendo salvato un dato strutturale devo disabilitare i radio
				$('[name="jobsattribute_id"]').attr('disabled','disabled');

				//Devo anche mettere il campo is locked a 1
				$('#isLocked').val('1');

				//Aggiorno il totale ore assegnato al cliente
				countTotalTime();

				//Se l'id era a zero devo aggiornarlo con quello che mi è arrivato per evitare che il prossimo edit facca un'altro insert
				if(objToSave.id == 0){

					if(result.data.id != undefined){

						$('[name="jobs[' + objToSave.job_id + '][totalTime]"]').attr('data-id',result.data.id);
						$('[name="jobs[' + objToSave.job_id + '][process_id]"]').attr('data-id',result.data.id);
						$('[name="jobs[' + objToSave.job_id + '][user_id]"]').attr('data-id',result.data.id);
						$('[name="jobs[' + objToSave.job_id + '][id]"]').val(result.data.id);

					}
				}

			}else{
				alert(result.msg);
			}

    	}
	});

}

function deleteTasksPlanned(objToDelete){

	$.ajax({
		url: pathServer + "consulenza/ws/deleteTasksPlanned",
		type: 'post',
		async: true,
		data: {id:objToDelete.id},
		dataType: 'json',
		success: function(result){

			if(result.response == "OK"){

				//Ho salvato quindi devo aggiornare il valore di ore da assegnare con quello che mi ha detto il server
				$('#toBeAssigned-' + objToDelete.job_id).html(result.data.timeToBeAssigned);

				//Posso aggiornare il numero di task programmati per il badge che sarebbe a zero
				$('span[data-id="' + objToDelete.job_id + '"]').html(result.data.taskPlanned);

				//devo anche riabilitare il tasto genera
				$('.create-tasks[data-id="' + objToDelete.job_id + '"]').removeClass('disabled').show();

				//devo riabilitare le select
				$('.select2[data-job-id="' + objToDelete.job_id + '"]').prop('disabled','');

				//devo anche disabilitare il tasto cancella
				$('.delete-tasks[data-id="' + objToDelete.job_id + '"]').hide();

				//devo nascondere il badge di task creati
				$('.badge.bg-aqua[data-id="' + objToDelete.job_id + '"]').hide();

			}else{
				alert(result.msg);
			}

    	}
	});

}

function createTasks(objToSave){

	$.ajax({
		url: pathServer + "consulenza/ws/createdTasks",
		type: 'post',
		async: true,
		data: objToSave,
		dataType: 'json',
		success: function(result){

			if(result.response == "OK"){

				//Ho salvato quindi devo aggiornare il valore di ore da assegnare con quello che mi ha detto il server
				$('#toBeAssigned-' + objToSave.job_id).html(result.data.timeToBeAssigned);

				//Posso aggiornare il numero di task programmati per il badge che sarebbe a zero
				$('span[data-id="' + objToSave.job_id + '"]').html(result.data.taskPlanned);

				//A questo punto avendo salvato un dato strutturale devo disabilitare i radio
				$('[name="jobsattribute_id"]').attr('disabled','disabled');

				//devo anche disabilitare il tasto genera e nasconderlo
				$('.create-tasks[data-id="' + objToSave.job_id + '"]').addClass('disabled').hide();

				//devo anche abilitare il tasto per cancellare
				$('.delete-tasks[data-id="' + objToSave.job_id + '"]').show();

				//devo disabilitare le select
				$('.select2[data-job-id="' + objToSave.job_id + '"]').prop('disabled','disabled');

				//Devo anche mettere il campo is locked a 1
				$('#isLocked').val('1');

				//devo mostrare il badge di task creati
				$('.badge.bg-aqua[data-id="' + objToSave.job_id + '"]').show();

			}else{
				alert(result.msg);
			}

    	}
	});

}

function countTotalTime(){

	var totH = 0;
	var totM = 0;

	$('[data-mask]').each(function(){

		var time = $(this).val();
		time = time.split(':');
		//alert(time);
		if(!isNaN(time[0])){
			totH += parseInt(time[0]);
		}

		if(!isNaN(time[1])){
			totM += parseInt(time[1]);
		}

		//alert(totH + " " + totM);
	});

	var h = parseInt(totM / 60);
	totH += h;

	totM = parseInt(((totM/60) - parseInt(totM / 60))*60);

	if(totM.toString().length < 2){
        totM = "0" + totM.toString();
    }

	$('p.total-time span').html(totH + ":" + totM);

	// Aggiunta per disabilitare il pulsate in caso di ore totali diverso da 0
	checkSbloccaAziendaBtn();

}
function checkSbloccaAziendaBtn()
{
	if($('p.total-time span').html() == '0:00'){
		$("#sblocca_azienda").attr('disabled',false);
		$("[data-toggle='tooltip']").tooltip('disable');

	}else{
		$("#sblocca_azienda").attr('disabled',true);
		$("[data-toggle='tooltip']").tooltip('enable');
	}
	if($('#isLocked').val() === ''){
		$("#sblocca_azienda").hide();
	}else{
		$("#sblocca_azienda").show();
	}

}

function sbloccaAzienda()
{
	var orderid = $('#order_id').val();
	$.ajax({
		url: pathServer + "consulenza/ws/sbloccaAzienda",
		type: 'post',
		async: true,
		data: {order_id:orderid},
		dataType: 'json',
		success: function(result){
			if(result.response == "OK"){
				// In caso di risposta affermativa riabilito i radio
				$('[name="jobsattribute_id"]').attr('disabled',false );
				$('.create-tasks').attr('disabled',true );

			}else{

				alert(result.msg);
			}
		}
	});


}
