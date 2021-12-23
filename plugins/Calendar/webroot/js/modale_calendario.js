var sTime;
var eTime;
var fillingModal = false;

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

  $('#idAzienda').change(function(){
		//alert($(this).val());
		$('#idOrder').html('');
		if($(this).val() != "" && fillingModal == false){ 
      //$('#inputTitle').val($('#inputTitle').val()+$("#idAzienda option[value='"+$(this).val()+"']").text());
			loadContattiAzienda($(this).val());
      loadOrdersAzienda($(this).val());
		}

	});
  $('#inputTitle').click(function(){
      //console.log($('#idAzienda').val())
      var title = '';
      if($(this).val() == "" && $('#idAzienda').val() != null ){
          title += $("#idAzienda option[value='"+$('#idAzienda').val()+"']").text();
          if(  $('#idOrder').val() != null){
            title += ' - '+$("#idOrder option[value='"+$('#idOrder').val()+"']").text() + ' :';

          }
          $('#inputTitle').val(title);
      }
  });
  $('#idOrder').change(function(){

		var idContatto = $('#order-num-'+$(this).val()).attr('idcontatto');
    if(fillingModal == false){
        //$('#inputTitle').val($('#inputTitle').val()+' - '+$("#idOrder option[value='"+$(this).val()+"']").text());
    }
		if(idContatto != "" && idContatto != undefined ){
        $('#idContatto').val(idContatto);

		}

	});

  $('#idOrder').select2({
     language: 'it',
     width: '100%',
     placeholder: 'Seleziona un ordine',
     dropdownParent: $("#idOrderParent"),
   });

  $('#idAzienda').select2({
     language: 'it',
     width: '100%',
     placeholder: 'Selezione un azienda',
     closeOnSelect: true,
     dropdownParent: $("#idAziendaParent"),
     minimumInputLength: 3,
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
     }
   });

   $('#idTags').select2({
      language: 'it',
      width: '100%',
      placeholder: 'Aggiungi tag',
      tags:true,
      tokenSeparators: [',', ' '],
      dropdownParent: $("#idTagsParent"),
      minimumInputLength: 2,
      ajax: {
        url: pathServer+'ws/autocompleteTags',
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

    $('#checkBoxRepeated').change(function(){
        if($(this).prop('checked') ){
            $('#collapseRepeated').collapse('show');
        }else{
            $('#collapseRepeated').collapse('hide');
        }
    });

    $('#repeatedEndType').change(function(){
        repeatedEndChange($(this).val());
    });

    $('#salvaNuovoEvento').click(function(){

        var id = $('#idEvent').val();
        if($('#checkBoxRepeated').is(':checked') && id !== '' && id !== null){
            $('#myModalRepeatedCalendar').modal('show');
        }else{
            saveCalendarEventModal();
        }

    });
    
    $('.repeatedModifyEvents').click(function(){

        //console.log($(this).val());
        saveCalendarEventModal($(this).val());


	});

    $('#eliminaEvento').click(function(){

        var id = $('#idEvent').val();
        if($('#checkBoxRepeated').is(':checked') && id !== '' && id !== null){
            $('#myModalRepeatedDelete').modal('show');
        }else{
            deleteCalendarEventModal();
        }


    });

    $('.repeatedDeleteEvents').click(function(){

        var data = '';
        if($(this).val() == 'thisEvent'){
          var d1 = $('#inputStartDate').val().split('/');
          data = d1[2] + '-' + d1[1] + '-' + d1[0] + " " + $('#inputStartTime').val();

        }
        deleteCalendarEventModal(data);


  	});


    //Gestione date picker

    $('#inputStartDate').datepicker({
        language: 'it',
        autoclose:true,
        todayHighlight:true

    });

    $('#inputEndDate').datepicker({
        language: 'it',
        autoclose:true,
        todayHighlight:true
    });

    $('#UNTIL').datepicker({
        language: 'it',
        autoclose:true,
        todayHighlight:true
    });



    $('#inputStartDate').datepicker().on('changeDate' , function(e){
        ckDate();
        if($('#UNTIL').val() == '' || $('#UNTIL').val() == null){
          $('#UNTIL').val($('#inputStartDate').val());
          $('#UNTIL').datepicker('update');
        }
    });

    $('#inputEndDate').datepicker().on('changeDate' , function(e){
        ckDate();
    });

    $('#UNTIL').datepicker().on('changeDate' , function(e){
        ckDate();
    });

    $('#inputStartTime, #inputEndTime').focusin(function(){
        sTime = $('#inputStartTime').val();
        eTime = $('#inputEndTime').val();
    });

    $('#inputStartTime').focusout(function(){
        ckTime('start');
    });

    $('#inputEndTime').focusout(function(){
        ckTime('end');
    });

    $('#checkAllDay').change(function(){

        //$(this).attr('checked',false);
        if($(this).is(':checked')){
            $('#myModalCalendar .ora-a').hide();
            $('#myModalCalendar .ora-da').hide();
            $('#inputStartTime').val('00:00');
            $('#inputEndTime').val('00:00');
        }else{

            $('#inputStartTime').val('08:00');
            $('#inputEndTime').val('09:00');
            $('#myModalCalendar .ora-a').show();
            $('#myModalCalendar .ora-da').show();
        }

    });

    $("[data-mask]").inputmask();

    /*$(".my-colorpicker2").colorpicker({
        color: '#3a87ad'
    });*/
     $('#inputColor').colorselector("setValue", '#3a87ad');

    //caricamento evento da timetask
    $('#loadTask').click(function(e){
        e.preventDefault();
        if($('#taskNumber').val() != ''){
            var task_number = $('#taskNumber').val();
            task_number = task_number.replace('#', '');

            $.ajax({
                url : pathServer + "calendar/Ws/getTaskFromTimetask/" + task_number,
                type: "GET",
                dataType: "json",
            }).done(function (res,stato) {
                if(res.response == "OK"){
                    console.log(res.data);
                    $('#idTask').val(res.data.task[0].id);
                    $('#taskClient').val(res.data.task[0].client);
                    $('#taskProject').val(res.data.task[0].project);
                    $('#inputTitle').val(res.data.task[0].title);
                    $('#inputNote').val(res.data.task[0].summary);
                    $('#timetask-data').show();
                }else{
                    alert(res.msg);
                }
            }).fail(function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }else{
            alert('Inserire il numero di un task per poter caricare i dati da Timetask.');
        }
    });


    //invio tempo a timetask
    $('#sendTimeTimetask').click(function(e){

        if($('#user_start_date').val() != '' && $('#user_start_time').val() != '' && 
        $('#user_stop_date').val() != '' && $('#user_stop_time').val() != ''){
            if($('#idTask').val() != ''){
                var data = {
                    id_event: $('#idEvent').val(),
                    id_time_timetask: $('#idTimeTimetask').val(),
                    id_task: $('#idTask').val(),
                    start_date: $('#user_start_date').val(),
                    start_time: $('#user_start_time').val(),
                    stop_date: $('#user_stop_date').val(),
                    stop_time: $('#user_stop_time').val(),
                    id_user: $('#idUser').val(),
                    note: $('#event_details_note').val()
                };

                $.ajax({
                    url : pathServer + "calendar/Ws/sendTimeTimetask/",
                    type: "POST",
                    data: data,
                    dataType: "json",
                }).done(function (res,stato) {
                    if(res.response == "OK"){
                        alert(res.msg);
                        $('#idTimeTimetask').val(res.data.time.id);
                        $('#calendar').fullCalendar( 'refetchEvents' );
                    }else{
                        alert(res.msg);
                    }
                }).fail(function (richiesta,stato,errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                });
            }else{
                alert('L\'evento deve essere collegato a un task su timetask per poter inviare il tempo a timetask.');
            }
        }else{
            alert('Le date e gli orari di start e stop devono essere compilati per poter inviare il tempo a timetask.');
        }
    });

});

/********************************************
 *  GENERAL FUNCTIONS
 *********************************************/
function ckDate(){

    var dStart = $('#inputStartDate').val();
    var tStart = $('#inputStartTime').val();
    var dEnd = $('#inputEndDate').val();
    var tEnd = $('#inputEndTime').val();

    //alert("Start: " + dStart + " " + tStart + " | End: " + dEnd + " " + tEnd);

    var ds = dStart.split('/');
    var de = dEnd.split('/');

    var start = ds[2] + "-" + ds[1] + "-" + ds[0] + " " + tStart;
    var end = de[2] + "-" + de[1] + "-" + de[0] + " " + tEnd;

    //alert("Start: " + start + " | End: " + end);

    var a = moment(start,'YYYY/MM/DD hh:mm');
    var b = moment(end,'YYYY/MM/DD hh:mm');
    var diffDays = b.diff(a, 'days');
    //alert(diffDays);
    if(diffDays < 0){
        //alert('Aumento di un giorno');
        //alert("Non puoi inserire la fine dell'evento precedente all'inizio!");
        var mEnd = moment(start);
        //mEnd.add(1,'d');
        $('#inputEndDate').val(mEnd.format('DD/MM/YYYY'));
        $('#inputEndDate').datepicker('update');
    }

}

function ckTime(typeTime){

    //alert("Start: " + sTime + " | End: " + eTime);

    var oldSTime = sTime;
    var oldETime = eTime;
    var newSTime = $('#inputStartTime').val();
    var newETime = $('#inputEndTime').val();

    var st = newSTime.split(':');
    var et = newETime.split(':');

    if(typeTime == "start"){

        //Verifico cosa è stato scritto nel campo
        if (typeof st[1] != 'undefined') {
            //Ho sia le ore che i minuti
            //alert('Ho le ore ed i minuti');

            //Se sono più lunghi di 2 caratteri tengo solo i primi 2 se sono vuoti uso i valori vecchi
            if(st[0].length > 2){
                st[0] = st[0].substring(0,2);
            }

            if(st[1].length > 2){
                st[1] = st[1].substring(0,2);
            }

            if(st[0].length == 0){
                st[0] = oldSTime.substring(0,2);
            }

            if(st[1].length == 0){
                st[1] = oldSTime.substring(3,5);
            }

            //alert('Dopo controlli: ' + st[0] + ":" + st[1]);

        }else{
            //Ho solo le ore
            alert('Ho solo le ore');

            //Controllo la lunghezza di quanto mi è stato scritto
            if(st[0].length == 0){
                st[0] = oldSTime.substring(0,2);
                st[1] = oldSTime.substring(3,5);
            }

            if(st[0].length > 0 && st[0].length < 3){
                st[1] = "00";
            }

            if(st[0].length > 2){
                st[1] = st[0].substring(2);
                st[0] = st[0].substring(0,2);
            }

            if(st[1].length > 2){
                st[1] = st[1].substring(0,2);
            }

            //alert('Dopo controlli: ' + st[0] + ":" + st[1]);

        }

        //Controllo se le ore sono un numero valido
        var hs = parseInt(st[0]);
        var ms = parseInt(st[1]);

        if(hs > 23){
            hs = 23;
        }

        if(hs < 0){
            hs = 0;
        }

        //Controllo se i minuti sono un numeor valido
        if(ms > 59){
            ms = 59;
        }

        if(ms < 0){
            ms = 0;
        }

        //alert('Dopo controlli: ' + hs + ":" + ms);

        if(hs.toString().length < 2){
            st[0] = "0" + hs.toString();
        }else{
            st[0] = hs.toString();
        }

        if(ms.toString().length < 2){
            st[1] = "0" + ms.toString();
        }else{
            st[1] = ms.toString();
        }

        $('#inputStartTime').val(st[0] + ":" + st[1]);

        //A questo punto devo verificare se l'ora è valida rispetto quella finale...ovvero deve essere precedente se è superiore
        //devo incrementare qualla di fine

        var he = parseInt(et[0]);
        var me = parseInt(et[1]);

        //Calcolo la diff delle vecchie date
        var ost = oldSTime.split(':');
        var oet = oldETime.split(':');

        var ohs = parseInt(ost[0]);
        var oms = parseInt(ost[1]);
        var ohe = parseInt(oet[0]);
        var ome = parseInt(oet[1]);

        var hdiff = 1;
        var mdiff = 0;
        var addDay = 0;

        //devo fare la diff tra date!!!!!

        var a = moment($('#inputStartDate').val(),'D/M/YYYY');
        var b = moment($('#inputEndDate').val(),'D/M/YYYY');
        var diffDays = b.diff(a, 'days');
        //alert(diffDays);

        if(ohe >= ohs){
            hdiff = ohe - ohs;
        }else{
            hdiff = (24 - ohs) + ohe;

            if(diffDays > 0){
                hdiff -= 24;
            }

        }

        //alert(hdiff);

        if(ome > oms){
            mdiff = ome - oms;
        }

        //controllo l'ora
        if(he < hs){
            hs += hdiff;
            if(hs > 24){
                hs -= 24;
                addDay = 1;
            }

            if(hs.toString().length < 2){
                et[0] = "0" + hs.toString();
            }else{
                et[0] = hs.toString();
            }
        }

        //Controllo i minuti
        if(me < ms){
            ms += mdiff;
            if(ms.toString().length < 2){
                et[1] = "0" + ms.toString();
            }else{
                et[1] = hs.toString();
            }
        }

        $('#inputEndTime').val(et[0] + ":" + et[1]);
        b.add(addDay,'d');
        $('#inputEndDate').val(b.format('DD/MM/YYYY'));
        $('#inputEndDate').datepicker('update');

    }else if(typeTime == "end"){

        //Verifico cosa è stato scritto nel campo
        if (typeof et[1] != 'undefined') {
            //Ho sia le ore che i minuti
            //alert('Ho le ore ed i minuti');

            //Se sono più lunghi di 2 caratteri tengo solo i primi 2 se sono vuoti uso i valori vecchi
            if(et[0].length > 2){
                et[0] = et[0].substring(0,2);
            }

            if(et[1].length > 2){
                et[1] = et[1].substring(0,2);
            }

            if(et[0].length == 0){
                et[0] = oldSTime.substring(0,2);
            }

            if(et[1].length == 0){
                et[1] = oldSTime.substring(3,5);
            }

        }else{
            //Ho solo le ore
            //alert('Ho solo le ore');

            //Controllo la lunghezza di quanto mi è stato scritto
            if(et[0].length == 0){
                et[0] = oldETime.substring(0,2);
                et[1] = oldETime.substring(3,5);
            }

            if(et[0].length > 0 && et[0].length < 3){
                et[1] = "00";
            }

            if(et[0].length > 2){
                et[1] = et[0].substring(2);
                et[0] = et[0].substring(0,2);
            }

            if(et[1].length > 2){
                et[1] = et[1].substring(0,2);
            }

        }

        //alert('Dopo controlli: ' + et[0] + ":" + et[1]);

        //Controllo se le ore sono un numero valido
        var he = parseInt(et[0]);
        var me = parseInt(et[1]);

        if(he > 23){
            he = 23;
        }

        if(he < 0){
            he = 0;
        }

        //Controllo se i minuti sono un numero valido
        if(me > 59){
            me = 59;
        }

        if(me < 0){
            me = 0;
        }

        //alert('Dopo controlli: ' + he + ":" + me);

        if(he.toString().length < 2){
            et[0] = "0" + he.toString();
        }else{
            et[0] = he.toString();
        }

        if(me.toString().length < 2){
            et[1] = "0" + me.toString();
        }else{
            et[1] = me.toString();
        }

        $('#inputEndTime').val(et[0] + ":" + et[1]);

        //A questo punto devo verificare se l'ora di inizio è compatibile con quella che ho appena inserito

        var hs = parseInt(st[0]);
        var ms = parseInt(st[1]);

        //Se l'ora di inizio è più alta rispetto quelal scritta come ora di fine devo presupporrre che questa data di fine sia del giorno successivo
        if(he < hs){
            var b = moment($('#inputEndDate').val(),'DD/MM/YYYY');
            b.add(1,'d');
            //alert(b.format('DD/MM/YYYY'));
            var newDate = b.format('DD/MM/YYYY');
            $('#inputEndDate').val(newDate);
            $('#inputEndDate').datepicker('update');
        }

    }


}

function updateEvents(data)
{
    var events = $('#calendar').fullCalendar( 'clientEvents' ,data.id );

    for(var i = 0; i<events.length; i++){

         events[i].title = data.title;
         events[i].start = data.start;
         events[i].end = data.end;
         events[i].allDay = data.allDay;
         events[i].note = data.note;
         events[i].backgroundColor = data.backgroundColor;
         events[i].borderColor = data.borderColor;
    }

    $('#calendar').fullCalendar( 'updateEvents', events );
}

function repeatedEndChange(val)
{
    switch (val) {
      case 'COUNT':
        $('#repeatedEndUntil').hide();
        $('#repeatedEndCount').show();
        break;
      case 'UNTIL':
        $('#repeatedEndUntil').show();
        $('#repeatedEndCount').hide();
        break;
      default:
        $('#repeatedEndUntil').hide();
        $('#repeatedEndCount').hide();
      break;

    }
}

function loadContattiAzienda(id, selectedId)
{
  if(id != null && id != undefined){
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

    	        	$('#idContatto').html(option);

    						if(selectedId !== undefined){
    							$('#idContatto').val(selectedId);
    						}

    	        }

    	    },
    	    error : function (richiesta,stato,errori) {
    	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    	    }
    	});
    }

}

function loadOrdersAzienda(id, selectedId = '')
{
  if(id != null && id != undefined){
    	$.ajax({
    	    url : pathServer + "aziende/Ws/getOrdersAzienda/" + id + '/' + selectedId  ,
    	    type: "GET",
    	    async: false,
    	    dataType: "json",
    	    data:{},
    	    success : function (data,stato) {

    	        if(data.response == "OK"){
    						//console.log(data);

    	        	var option = '<option style="color: graytext;" value="0">Nessuno</option>';

                for (var item in data.data) { 
                  
                    if(data.data[item].stato.selectable){
    								    option += '<option id="order-num-'+data.data[item].id+'" idcontatto="'+data.data[item].id_contatto+'" value="' + data.data[item].id+ '">' + data.data[item].name + '</option>';
                    }else{
                      option += '<option id="order-num-'+data.data[item].id+'" idcontatto="'+data.data[item].id_contatto+'" value="' + data.data[item].id+ '">' + data.data[item].name + ' *</option>';
                    }

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

function saveCalendarEventModal(repeatedToModify)
{

  var ckErrore = 0;
  var msgErrore = "";

    var id = $('#idEvent').val();
    var idGoogle = $('#idGoogle').val();
    var title = $('#inputTitle').val();
      var allDay = 0;
      if($('#checkAllDay').is(':checked')){
          allDay = 1;
      }
      var d1 = $('#inputStartDate').val().split('/');
    var start = d1[2] + '-' + d1[1] + '-' + d1[0] + " " + $('#inputStartTime').val();

      var d2 = $('#inputEndDate').val().split('/');
    var end = d2[2] + '-' + d2[1] + '-' + d2[0] + " " + $('#inputEndTime').val();
      if(allDay == 1){
          var a = moment(end,'YYYY-MM-DD hh:mm');
          a.add(1,'d');
          end = a.format('YYYY-MM-DD hh:mm');
      }
    var note = $('#inputNote').val();
      var backgroundColor = $('#inputColor').val();
      var borderColor = $('#inputColor').val();

    var userId = '';
      if($('#idUser').val() !== undefined && $('#idUser').val() > 0 ){
        userId = $('#idUser').val();
      }else{
        userId = $('#user-caledar-view').val();
      }
    var idAzienda = ($('#idAzienda').val()!==null ?$('#idAzienda').val() : 0);
    var idContatto = ($('#idContatto').val()!==null ?$('#idContatto').val() : 0);
    var idOrder = ($('#idOrder').val()!==null ?$('#idOrder').val() : 0);
    var idTags = ($('#idTags').val()!==null ?$('#idTags').val() : []);

    if($('#idTask').val() != ''){
        var idTask = $('#idTask').val();
        var numberTask = $('#taskNumber').val();
        var clientTask = $('#taskClient').val();
        var projectTask = $('#taskProject').val();
    }else{
        var idTask = '';
        var numberTask = '';
        var clientTask = '';
        var projectTask = '';
    }

    // Parti per evento ripetuto
    if(repeatedToModify == undefined){
      repeatedToModify = '';
    }
    var repeated = 0;
    var confRepeatedUpdate = 1;
    if($('#checkBoxRepeated').is(':checked')){
       repeated = 1;
    }
    var freq = $('#FREQ').val();
    var interval = $('#INTERVAL').val();
    var repeatedEndType = $('#repeatedEndType').val();
    var until = $('#UNTIL').val();
    var exdate = $('#EXDATE').val();
    if(until != ''){
     var untilArray = until.split('/');
     untilArray = untilArray.reverse();
     until = untilArray.join('-');
    }
    var count = $('#COUNT').val();

      //Eseguo i controlli di validità
      //Il titolo è obbligatorio

      if(title == ""){
          ckErrore = 1;
          msgErrore = "Il titolo dell'evento è obbligatorio.";
      }

      if(ckErrore == 0 && confRepeatedUpdate){
      showHideLoadingSpinner();
      //console.log(idContatto);return;
      $.ajax({
              url : pathServer + "calendar/ws/saveEvent",
              type  : "post",
              data : {start:start, end:end, allDay:allDay, title:title, id:id, note:note, backgroundColor:backgroundColor,
                      borderColor:borderColor, id_user:userId, id_azienda:idAzienda, id_order:idOrder, id_contatto:idContatto, id_google:idGoogle,
                      id_timetask:idTask, number_timetask:numberTask, client_timetask:clientTask, project_timetask:projectTask, repeated:repeated,
                      FREQ:freq, INTERVAL:interval, EXDATE:exdate, repeatedEndType:repeatedEndType, UNTIL:until, COUNT:count, repeatedToModify:repeatedToModify, tags:idTags},
              dataType : "json",
              success : function (data,stato) {
                  showHideLoadingSpinner();
                  if(data.response == "OK"){

                      saveEventDetailsModal();

                      cloningEvent = {};
                      $('#calendar').fullCalendar( 'refetchEvents' );

                      $('#event-id').val("");
                      document.getElementById("myModalCalendarForm").reset();
                      $('#collapseRepeated').collapse('hide');

                      $('#myModalCalendar').modal('hide');

                  }else{
                      alert(data.msg);
                  }

              },
              error : function (richiesta,stato,errori) {
                  showHideLoadingSpinner();
                  alert("E' avvenuto un errore. Stato della chiamata: "+stato);
              }
          });

      }else{
        if(msgErrore != ''){
          alert(msgErrore);
        }

      }

}

function saveEventDetailsModal(){

    $.ajax({
        url : pathServer + "calendar/ws/saveEventDetails",
        type  : "post",
        data : $('#formEventDetails').serialize(),
        dataType : "json",
        success : function (data) {
            showHideLoadingSpinner();
          if(data.response == "OK"){
              
          }else{
              alert(data.msg);
          }
    },
        error : function () {
            showHideLoadingSpinner();
            alert("E' avvenuto un errore.");
        }
    });
}

function deleteCalendarEventModal(data)
{
  var id = $('#idEvent').val();
  if(data == undefined){
    data = '';
  }

  //alert('Elimino evento id: ' + id);
  if(confirm('Si è sicuri di voler eliminare l\'evento?')){

      $.ajax({
          url : pathServer + "calendar/ws/deleteEvent/" + id,
          type  : "post",
          data : "id=" + id+"&data="+data,
          dataType : "json",
          success : function (data,stato) {

              if(data.response == "OK"){
                  if(data == ''){
                    $('#calendar').fullCalendar( 'removeEvents', id );
                  }else{
                    $('#calendar').fullCalendar( 'refetchEvents' );
                  }
                  $('#myModalCalendar').modal('hide');
                  document.getElementById("myModalCalendarForm").reset();
                  $('#collapseRepeated').collapse('hide');

              }else{
                  alert(data.result.msg);
              }

          },
          error : function (richiesta,stato,errori) {
              alert("E' avvenuto un errore. Stato della chiamata: "+stato);
          }
      });

  }


}

function initMap(coordinates) {

    //console.log(coordinates);
  
    var startPosition = [];
    var stopPosition = [];
  
    $.each(coordinates, function(index, coords){
  
      startPosition.push({lat: parseFloat(coords.latStart), lng: parseFloat(coords.longStart), cognome: coords.cognome, nome: coords.nome});
      stopPosition.push({lat: parseFloat(coords.latStop), lng: parseFloat(coords.longStop), cognome: coords.cognome, nome: coords.nome});
  
    });
  
  
    var centerPosition = startPosition[0];
  
    var mapOptions = {
        zoom: 13,
        center: centerPosition,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
  
    var map = new google.maps.Map(document.getElementById('eventDetailsMap'), mapOptions);
    var bounds = new google.maps.LatLngBounds();
  
  
    $.each(startPosition, function(index, startPos){
      var start = new google.maps.LatLng(startPos.lat, startPos.lng);
      bounds.extend(start);
      markerStart = new google.maps.Marker({
        position: start,
        map: map,
        icon : 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
      });
  
      var infowindowStart = new google.maps.InfoWindow({
        content: startPos.cognome + ' ' + startPos.nome
      });
  
      google.maps.event.addListener(markerStart, 'click', function(){
        infowindowStart.open(map, this);
      });
  
    });
  
  
    $.each(stopPosition, function(index, stopPos){
      var stop = new google.maps.LatLng(stopPos.lat, stopPos.lng);
      bounds.extend(stop);
      markerStop = new google.maps.Marker({
        position: stop,
        map: map,
        icon : 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
      });
  
      var infowindowStop = new google.maps.InfoWindow({
        content: stopPos.cognome + ' ' + stopPos.nome
      });
  
      google.maps.event.addListener(markerStop, 'click', function(){
        infowindowStop.open(map, this);
      });
  
    });
  
  }

  function loadEventDetail(id)
{
  $.ajax({
      url : pathServer + "calendar/ws/eventDetail/" + id,
      type: "GET",
      async: false,
      dataType: "json",
      data:{},
      success : function (data,stato) {

        if(data.response == 'OK'){
            fillingModal = true;
            var evento = data.data.evento;        
            fillingModal = false;


            // XXXXXXXXXXXXXXXXXXXXXXXXXXXo


            //console.log(data.data.dettagli);
            $('.form-cloned').remove();
            $('.firma-cloned').remove();

            var coordinates = [];

          if(data.data.dettagli.length > 0){
            $.each(data.data.dettagli, function(index, detail){

              var formHidden = $('.form-hidden').clone();
              var firmaHidden = $('.firma-hidden').clone();

              firmaHidden.removeClass('firma-hidden').removeAttr('hidden').addClass('firma-cloned');
              formHidden.removeClass('form-hidden').removeAttr('hidden').addClass('form-cloned');

              //formHidden.find('.operatorLine').show();
              /*
              var activities = data.data.attivita;

              if(activities != null){
                var input_checkbox = '';
                var input_textarea = '';
                activities.forEach(function(attivita){

                  var checked = "";
                  var note = "";
                  detail.EventiDettaglioAttivita.forEach(function(att){

                    if(att.id_activity == attivita.id){
                      checked = "checked";
                      note = att.note;
                    }

                  });

                  input_checkbox += '<input type="checkbox" class="checkbox_activity status-done" name="checkbox_activity][' + attivita.id + '][id" value="' + attivita.id + '" ' + checked + ' /> ' + attivita.name + '</br>';
                  if(attivita.hasNote == 1){
                    input_checkbox += '<textarea rows="2" cols="70" class="form-control status-done" id="textarea_activity_' + attivita.id + '" name="checkbox_activity][' + attivita.id + '][note" >' + note + '</textarea>';
                  }

                });

                formHidden.find('#listActivities').html(input_checkbox);

              }
              */

              formHidden.find('input, textarea, span').each(function(){
                $(this).attr('id',$(this).attr('name'));
                $(this).attr('name','detail[' + detail.operator_id + '][' + $(this).attr('name') + ']');

              });

              firmaHidden.find('span').each(function(){
                $(this).attr('id', $(this).attr('name'));
                $(this).attr('name', 'detail[' + detail.operator_id + '][' + $(this).attr('name') + ']');
              });

              if(data.data.evento.condiviso == 1){
                formHidden.find('.operatorP').show();
                formHidden.find('.operatorP').html(detail.Contatti.cognome + " " + detail.Contatti.nome);
                formHidden.find('.operatorLine').show();
                firmaHidden.find('operatorP').show();
                firmaHidden.find('.operatoreFirma').html(detail.Contatti.cognome + " " + detail.Contatti.nome);
                firmaHidden.find('.operatorLineFirma').show();
              }

              $('#formEventDetails').append(formHidden);
              $('#eventFirma').append(firmaHidden);

              //console.log('[name="detail[' + detail.operator_id + ']idEventDetail"]');
              //console.log(detail.id);

              $('#idEventDetail').val(detail.id);
              $('#idEvento').val(detail.event_id);
              $('#idOperatore').val(detail.operator_id);
              $('#user_start_date').val(detail.user_start_date);
              $('#user_start_time').val(detail.user_start_time);
              $('#user_start_lat').html(detail.start_lat);
              $('#user_start_long').html(detail.start_long);
              $('#user_stop_date').val(detail.user_end_date);
              $('#user_stop_time').val(detail.user_end_time);
              $('#user_stop_lat').html(detail.stop_lat);
              $('#user_stop_long').html(detail.stop_long);
              $('#user_real_start').html(detail.real_start);
              $('#user_real_stop').html(detail.real_end);
              $('#event_details_note').html(detail.note);
              if(detail.note_importanza){
                $('input[type="checkbox"]#note_importanza').attr('checked', 'checked');
              }
              if(detail.signature == 'Non disponibile'){
                $('[name="detail['+(index+1)+'][eventDetailsFirma]"]').html(detail.signature);
              }else{
                $('[name="detail['+(index+1)+'][eventDetailsFirma]"]').html('<img src="' + detail.signature + '" />');
              }


              coordinates.push({
                latStart : detail.start_lat,
                longStart : detail.start_long,
                latStop : detail.stop_lat,
                longStop : detail.stop_long,
                nome : detail.Contatti.nome,
                cognome : detail.Contatti.cognome
              });

            });
          }

          // Gestione stato evento

          if(evento.status != 'TODO'){
            $('.status-todo').attr('disabled','disabled');
            if(data.data.dettagli.length > 0){
                $('#eventDetails').removeAttr('disabled');
                $('#signatureDetails').removeAttr('disabled');
                $('#mapDetails').removeAttr('disabled');
            }else{
                $('#eventDetails').attr('disabled','disabled');
                $('#signatureDetails').attr('disabled','disabled');
                $('#mapDetails').attr('disabled','disabled');
            }
          }else{
            $('.status-todo').removeAttr('disabled');
            $('#eventDetails').attr('disabled','disabled');
            $('#signatureDetails').attr('disabled','disabled');
            $('#mapDetails').attr('disabled','disabled');
          }

          if(evento.status != 'DONE'){
            $('.status-done').attr('disabled','disabled');
          }else{
            $('.status-done').removeAttr('disabled');
          }

          $('.badge-event').hide();
          if(evento.status == 'TODO'){
            $('.badge-event-todo').show();
          }
          if(evento.status == 'DOING'){
            $('.badge-event-doing').show();
          }
          if(evento.status == 'DONE'){
            $('.badge-event-done').show();
          }

          // Fine gestione stato evento


          $('#mapDetails').off('click').on('click', function(){

            $.each(coordinates, function(index, coords){

              if(coords.latStart === 'Non disponibile' && coords.longStart === 'Non disponibile' && coords.latStop === 'Non disponibile' && coords.longStop === 'Non disponibile'){
                $('#eventDetailsMap').hide();
                $('#eventMapTitle').hide();
                $('#nonDisponibile').show();

              }else if(coords.latStart != 'Non disponibile' || coords.longStart != 'Non disponibile'){
                $('#eventDetailsMap').show();
                $('#eventMapTitle').show();
                $('#nonDisponibile').hide();

              }else if(coords.latStop != 'Non disponibile' || coords.longStop != 'Non disponibile'){
                $('#eventDetailsMap').show();
                $('#eventMapTitle').show();
                $('#nonDisponibile').hide();

              }
            });

            setTimeout(()=>{
              initMap(coordinates);
            }, 1000);

          });

        }
      },
      error: function(data,stato) {
          alert("E' avvenuto un errore. Stato della chiamata: "+stato);
      }
    });

}

function addOperatore(id,operatori)
{
    var operatoreNum = $('#compresenze-list').children().length;
    var operatore = '<div class="form-group input operatore'+operatoreNum+'Parent">'+
        '<label class="col-sm-2 control-label required">Operatore:</label>'+
        '<div class="col-sm-9">'+
          '<select name="operatore[]" class="form-control required operatore'+operatoreNum+'">'+
            '<option></option>'+
          '</select>'+
        '</div>'+
        '<div class="col-sm-1">'+
          '<a class="btn btn-default remove-operatore"><i class="fa fa-minus" aria-hidden="true"></i></a>'+
        '</div></div>';
    $('#compresenze-list').append(operatore);
    // Se mi arriva la lista delle opzioni degli operatori da appendere lo aggiungo, altrimenti li carico via ajax
    if(operatori !== undefined){
        $('.operatore'+operatoreNum).append(operatori);
    }

    $('.operatore'+operatoreNum).select2({
        language: 'it',
        width: '100%',
        placeholder: 'Seleziona un operatore',
        dropdownParent: $('.operatore'+operatoreNum+'Parent'),
        tags:true,
        allowClear: true,
     });
     if(id !== undefined){
        $('.operatore'+operatoreNum).val(id).trigger('change');
     }

}