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
  /***************************************************
  * INITIALIZZAZIONE SELECT2
  *****************************************************/
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
   $('#idPerson').select2({
      language: 'it',
      width: '100%',
      placeholder: 'Seleziona una persona',
      closeOnSelect: true,
      dropdownParent: $("#idPersonParent"),
      minimumInputLength: 3,
      ajax: {
        url: pathServer+'progest/ws/people/autocomplete/active',
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
 $('#idUser').select2({
     language: 'it',
     width: '100%',
     placeholder: 'Seleziona un operatore',
     dropdownParent: $("#idUserParent"),
  });
  /***************************************************
  * EVENTI CHANGE
  *****************************************************/
  $('[name="optCategory"]').change(function(){
       switch ($(this).val()) {
         case '1':
           $('#idUser').html('').trigger("change");
           $('#idService').html('').trigger("change");
           $('#inputTitle').val('');
           $('.only-cat-1').show();
           $('.add-operatore').removeAttr('disabled');
           break;
         case '2':
           loadSecondCategory();
           $('.only-cat-1').hide();
           $('#idPerson').html('').trigger("change");
           $('#idOrder').html('').trigger("change");
           $('#inputTitle').val('');
           $('.add-operatore').attr('disabled','disabled');
           break;
       }
       if($("#idService option:selected").attr('editable') == 'true'){
           $('#inputTitle').removeAttr('readonly');
       }else{
           $('#inputTitle').attr('readonly',true);
       }
       var color = $('[name="optCategory"]').filter(':checked').attr('data-color');
       if(color == '' || color == null){
           color = '#3a87ad';
       }
       if($("#inputColor option[value='"+color+"']").length == 0){
         $('#inputColor').append('<option value="'+color+'" data-color="'+color+'">'+color+'</option>');
       }
       $('#inputColor').colorselector("setValue", color);
  });

  $('#idOrder').change(function(){
    var idOrder = $(this).val();
    if(!fillingModal && idOrder != '' && idOrder != undefined){
       loadOrderServices(idOrder);
    }

	});
  $('#idPerson').change(function(){
      var idPerson = $(this).val();
      if(!fillingModal && idPerson != '' && idPerson != undefined){
          var person = $("#idPerson option[value='"+idPerson+"']").text();
          name = person.replace(/nato\/a il+.+/g, "");
          $('#inputTitle').val(name.toUpperCase());
          $('#inputTitle').trigger('change');
         loadOrdersPerson(idPerson);

      }
  });
  $('#idService').change(function(){
      var idService = $(this).val();
      if(!fillingModal && idService != '' && idService != undefined ){
        switch ($('[name="optCategory"]').filter(':checked').val()) {
          case '1':
            var idUserView = $('#user-caledar-view').val();
            loadContattiService(idService,'#idUser',idUserView);
            break;
          case '2':
            $('#inputTitle').val($("#idService option[value='"+idService+"']").text().toUpperCase());
            break;

        }
        if($("#idService option:selected").attr('editable') == 'true'){
            $('#inputTitle').removeAttr('readonly');
        }else{
            $('#inputTitle').attr('readonly',true);
        }
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

  $('#idUser').change(function(){
      disableOperatori();
  });

  /***************************************************
  *  EVENTI CLICK
  *****************************************************/
	$('#salvaNuovoEvento').click(function(){

    var id = $('#idEvent').val();
    if($('#checkBoxRepeated').is(':checked') && id !== '' && id !== null){
        $('#myModalRepeatedCalendar').modal('show');
    }else{
        saveCalendarEventModal();
    }
	});

	$('#salvaEventoDettagli').click(function(){
		saveEventDetailsModal();
	});

  $('.repeatedModifyEvents').click(function(){
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

	//Mostra/nascondi textarea note al check

	 $(document).on('click', '.checkbox_activity', function() {
		var activityId = $(this).val();
 	    if ($(this).prop('checked') == true) {
     		$('#textarea_activity_' + activityId).show();
 	    }
 	    else {
 	        $('#textarea_activity_' + activityId).hide();
 	    }
	});

	$('#myModalCalendar').on('show.bs.modal', function() {
	    $('.nav-tabs a[href="#evento"]').tab('show')
	});

    /***************************************************
    * GESTIONE DATEPICKERS
    *****************************************************/
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

    $('.add-operatore').click(function(){
        addOperatore();
    });

});
/********************************************
 *  EVENTI SUL DOCUMENT
 *********************************************/
$(document).on('click','.remove-operatore',function(){
    $(this).parent().parent().remove();
    disableOperatori();
});

$(document).on('change', 'input, select', function(){
    $(this).parentsUntil('div.form-group, div.input').parent().removeClass('has-error');
});

$(document).on('change','select[name^="operatore"]', function(){
    disableOperatori();
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
                et[1] = "0" + ms.toString() ;
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
            et[1] = "0" + me.toString() ;
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

function loadContattiService(id, elemet, selectedId)
{
  var d1 = $('#inputStartDate').val().split('/');
  var start = d1[2] + '-' + d1[1] + '-' + d1[0] + " " + $('#inputStartTime').val();
  var d2 = $('#inputEndDate').val().split('/');
  var end = d2[2] + '-' + d2[1] + '-' + d2[0] + " " + $('#inputEndTime').val();
  if($('#checkAllDay').is(':checked')){
      var a = moment(end,'YYYY-MM-DD hh:mm');
      a.add(1,'d');
      end = a.format('YYYY-MM-DD hh:mm');
  }
  if(id != null && id != undefined && elemet != undefined){
    	$.ajax({
    	    url : pathServer + "progest/ws/services/contactsForService/" + id  ,
    	    type: "GET",
    	    async: false,
    	    dataType: "json",
    	    data:{start:start,end:end, users:[selectedId]},
    	    success : function (data,stato) {

    	        if(data.response == "OK"){
    						//console.log(data);
    	        	var option = '<option value=""></option>';

    	        	for (var item in data.data) {
    								option += '<option value="' + data.data[item].id+ '">' + data.data[item].text + '</option>';
    						}

    	        	$(elemet).html(option);

    						if(selectedId !== undefined){
    							$(elemet).val(selectedId).trigger('change');
    						}
                disableOperatori();

    	        }

    	    },
    	    error : function (richiesta,stato,errori) {
    	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    	    }
    	});
    }

}

function loadOrdersPerson(id, selectedId)
{
  if(id != null && id != undefined){
    	$.ajax({
    	    url : pathServer + "progest/ws/people/orders/" + id  ,
    	    type: "GET",
    	    async: false,
    	    dataType: "json",
    	    data:{},
    	    success : function (data,stato) {

    	        if(data.response == "OK"){
                fillingModal = true;
    	        	var option = '<option value=""></option>';
    	        	for (var item in data.data) {

    								option += '<option value="' + data.data[item].id+ '">'+'Committente: "'+
                      data.data[item].azienda.denominazione +'" Oggetto: "'+ data.data[item].name + '"</option>';

    						}

    	        	$('#idOrder').html(option);
                if(data.data.length == 1){
                    $('#idOrder').val(data.data[0].id);
                    option = '<option value=""></option>';
                    $.each(data.data[0].ServicesOrders,function(index,servicesOrder){
                        option += '<option value="' + servicesOrder.service.id+
                          '">'+ servicesOrder.service.name + '</option>';
                    });
                    $('#idService').html(option);
                    if(data.data[0].ServicesOrders.length == 1){
                        fillingModal = false;
                        $('#idService').val(data.data[0].ServicesOrders[0].service.id).trigger('change');
                    }

                }
                fillingModal = true;
    						if(selectedId !== undefined){
    							$('#idOrder').val(selectedId);

    						}
                $('#idOrder').change();
                fillingModal = false;
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

    var idOperatore = '';
      if($('#idUser').val() !== undefined && $('#idUser').val() > 0 ){
        idOperatore = $('#idUser').val();
      }else{
        idOperatore = $('#user-caledar-view').val();
      }
    var idAzienda = ($('#idAzienda').val()!==null ?$('#idAzienda').val() : 0);
    var idOrder = ($('#idOrder').val()!==null ?$('#idOrder').val() : 0);
    var idTags = ($('#idTags').val()!==null ?$('#idTags').val() : []);
    var idService = ($('#idService').val()!==null ?$('#idService').val() : 0);
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
    var operatori = '';
    $('select[name^="operatore"]').each(function() {
        operatori += '&operatore[]='+$(this).val();
    });
    var idGroup = ($('#idGroup').val()!==null ?$('#idGroup').val() : 0);
    if(idGroup != 0 && ( operatori == '' || operatori == null)){
        if(!confirm('Confermi di voler trasformare un evento di compresenza in evento singolo?')){
           return;
        }
    }
      //Eseguo i controlli di validità
      //Il titolo è obbligatorio
    if($('[name="optCategory"]').filter(':checked').val() == '1'){
        if(idOrder == 0){
            ckErrore = 1;
            msgErrore = "Devi selezionare una persona e un buono d'ordine.";
            $('#idOrder').parent().parent().addClass('has-error');
        }
        if($('#idPerson').val() == null || $('#idPerson').val() == '' ){
            $('#idPerson').parent().parent().addClass('has-error');
        }
    }
      if(ckErrore == 0 && idOperatore ==''){
          ckErrore = 1;
          //msgErrore = "La selezione di un operatore è obbligatorio.";
      }
      if(ckErrore == 0 && idService == 0){
          ckErrore = 1;
          //msgErrore = "La selezione di un servizio è obbligatorio.";
      }
      if(ckErrore == 0 && title == ""){
          ckErrore = 1;
          //msgErrore = "Il titolo dell'evento è obbligatorio.";
      }
      var validForm = formValidation('myModalCalendarForm');
      if(ckErrore == 0 && validForm && confRepeatedUpdate){
      showHideLoadingSpinner();
      //console.log(idContatto);return;
      $.ajax({
              url : pathServer + "calendar/ws/saveEvent",
              type  : "post",
              data : "start=" + start + "&end=" + end + "&allDay=" + allDay + "&title=" + title + "&id=" + id + "&note=" + note + "&backgroundColor=" + backgroundColor +
                      "&borderColor=" + borderColor+"&id_service="+idService+"&id_azienda="+idAzienda+"&id_order="+idOrder+"&id_contatto="+idOperatore+"&id_google="+idGoogle+
                      "&repeated="+repeated+'&FREQ='+freq+'&INTERVAL='+interval+'&EXDATE='+exdate+
                      "&repeatedEndType="+repeatedEndType+"&UNTIL="+until+"&COUNT="+count+"&repeatedToModify="+repeatedToModify+"&tags="+idTags+operatori+"&id_group="+idGroup,
              dataType : "json",
              success : function (data,stato) {
                  showHideLoadingSpinner();
                  if(data.response == "OK"){

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
        if(msgErrore != '' && validForm ){
          alert(msgErrore);
        }

      }

}

function saveEventDetailsModal(){

	var id = $('#idEventDetail').val();
	var idEvent = $('#idEvento').val();
	var idOperatore = $('#idOperatore').val();
    var userStartDate = $('#startData').val();
	var userStartTime = $('#startOra').val();
    var userStopDate = $('#stopData').val();
	var userStopTime = $('#stopOra').val();
    var eventNote = $('#eventDetailsNote').val();
	var eventNoteImportanza = '';

	if($('#eventDetailsNoteImportanza').prop('checked') === true){
		eventNoteImportanza = '1';
	}else{
		eventNoteImportanza = '0';
	}


    var userStart = userStartDate + ' ' + userStartTime + ':00';
    var userStop = userStopDate + ' ' + userStopTime + ':00';

	var objectCheckboxes = {};
	var activityCheckboxes = $('#eventDetailsActivities').find('.checkbox_activity');
	for(var i = 0; i < activityCheckboxes.length; i++){
		var activityId = $(activityCheckboxes[i]).val();
		var activityStatus = $(activityCheckboxes[i]).prop('checked');
		var activityNote = '';
		if($('#textarea_activity_' + activityId)){
			activityNote = $('#textarea_activity_' + activityId).val();
		}
        objectCheckboxes[activityId] = [activityStatus, activityNote];
    }

	var arrayCheckboxes = JSON.stringify(objectCheckboxes);

    $.ajax({
        url : pathServer + "calendar/ws/saveEventDetails",
        type  : "post",
        data : "id=" + id + "&id_event=" + idEvent + "&id_operatore=" + idOperatore + "&userStart=" + userStart + "&userStop=" + userStop + "&eventNote=" + eventNote + "&eventNoteImportanza=" + eventNoteImportanza + "&arrayCheckboxes=" + arrayCheckboxes,
        dataType : "json",
        success : function (data) {
            showHideLoadingSpinner();
        	if(data.response == "OK"){
                $('#calendar').fullCalendar( 'refetchEvents' );
				$('#event-id').val("");
				$('#myModalCalendar').modal('hide');
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
  var idOperatore = 0;
  if($('#user-type').val() == 1){
    idOperatore = $('#user-caledar-view').val();
  }
  //alert('Elimino evento id: ' + id);
  if(confirm('Si è sicuri di voler eliminare l\'evento?')){

      $.ajax({
          url : pathServer + "calendar/ws/deleteEvent/" + id+'/'+idOperatore,
          type  : "post",
          data : "id=" + id+"&data="+data,
          dataType : "json",
          success : function (data,stato) {

              if(data.response == "OK"){
                  if(data == ''){
                    $('#calendar').fullCalendar( 'removeEvents', id );
                  }else{
                    cloningEvent = {};
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
	var startPosition = {lat: parseFloat(coordinates.latStart), lng: parseFloat(coordinates.longStart)};
	var stopPosition = {lat: parseFloat(coordinates.latStop), lng: parseFloat(coordinates.longStop)};
	var addressPosition = {lat: parseFloat(coordinates.latAddress), lng: parseFloat(coordinates.longAddress)};

	var mapCanvas = document.getElementById('eventDetailsMap');
    var mapOptions = {
    	zoom: 13,
        center: {lat: 45.0757819, lng:7.6759009},
		mapTypeId: google.maps.MapTypeId.ROADMAP
    };

	var map = new google.maps.Map(mapCanvas, mapOptions);

    var markerStart = new google.maps.Marker({
        position: startPosition,
        map: map,
		icon : 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
    });
	var markerStop = new google.maps.Marker({
        position: stopPosition,
        map: map,
		icon : 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
    });
	var markerAddress = new google.maps.Marker({
        position: addressPosition,
        map: map,
		icon : 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
    });

	$('#myModalCalendar').on('shown.bs.modal', function() {
		google.maps.event.trigger(map, "resize");
		map.setCenter(mapOptions.center);
	  });

}


function loadEventDetail(id,frozen)
{
  if(frozen == undefined || frozen == 'false'){
    frozen = false;
  }
  $.ajax({
      url : pathServer + "calendar/ws/eventDetail/" + id +'/'+frozen ,
      type: "GET",
      async: false,
      dataType: "json",
      data:{},
      success : function (data,stato) {

          if(data.response == 'OK'){
            fillingModal = true;
            var evento = data.data.evento;
            var order = evento.ordine;
            // Riempio la persona
            var dataNascita = '--';
            if(moment(order.persona.birthdate, moment.ISO_8601, true).isValid() ){
                dataNascita = moment(order.persona.birthdate).format('DD/MM/YYYY');
            }
            // se l'ordine non è attivo eseguo un alert
            if(order.id_status != 1){
               setTimeout(function(){ alert('Il buono d\'ordine selezionato non è attivo.'); }, 1000);
            }
            var option = '<option value=""></option>';
            option += '<option value="'+order.persona.id+'">'+order.persona.surname+' '+order.persona.name+' nato/a il '+dataNascita+'</option>';
            $('#idPerson').html(option).val(order.persona.id).trigger("change");
            // Riempio l'ordine
            option = '<option value=""></option>';
            option += '<option value="' + order.id+'">'+'Committente: "'+
              order.azienda.denominazione +'" Oggetto: "'+ order.name + '"</option>';
            $('#idOrder').html(option).val(evento.id_order);
            // Riempio i servizi
            option = '<option value=""></option>';
            $.each(order.ServicesOrders,function(index,servicesOrder){
                option += '<option value="' + servicesOrder.service.id+
                  '">'+ servicesOrder.service.name + '</option>';
            });
            $('#idService').html(option).val(evento.id_service);
            // Riempio gli operatori
            option = '<option value=""></option>';
            for (var item in data.data.contatti) {
                option += '<option value="' + data.data.contatti[item].id+ '">' + data.data.contatti[item].text + '</option>';
            }
            if(evento.id_group == 0){
                $('#idUser').html(option).val(evento.id_contatto);
            }else{
                for(var i = 0; i < evento.operatori.length; i++){
                    if(i == 0){
                       $('#idUser').html(option).val(evento.operatori[i]);
                    }else{
                        addOperatore(evento.operatori[i],option);
                    }
                }
                if(evento.operatori.length == 1){
                    addOperatore();
                }
            }
            fillingModal = false;

			if(data.data.dettagli != null){
				var dettagli = data.data.dettagli;
				$('#idEventDetail').val(dettagli.id);
				$('#idEvento').val(evento.id);
				$('#idOperatore').val(evento.id_contatto);
				$('#startData').val(dettagli.user_start_date);
				$('#startOra').val(dettagli.user_start_time);
				$('#startRealOra').html(dettagli.real_start);
				$('#startLat').html(dettagli.start_lat);
				$('#startLong').html(dettagli.start_long);
				$('#stopData').val(dettagli.user_end_date);
				$('#stopOra').val(dettagli.user_end_time);
				$('#stopRealOra').html(dettagli.real_end);
				$('#stopLat').html(dettagli.stop_lat);
				$('#stopLong').html(dettagli.stop_long);
				$('#eventDetailsNote').val(dettagli.note);
				if(dettagli.note_importanza){
					$('#eventDetailsNoteImportanza').prop('checked', true);
				}
				if(dettagli.signature == 'Non disponibile'){
					$('#eventDetailsFirma').html(dettagli.signature);
				}else{
					$('#eventDetailsFirma').html('<img src="' + dettagli.signature + '"/>');
				}

				/*var coordinates = [];
				coordinates.latStart = dettagli.start_lat;
				coordinates.longStart = dettagli.start_long;
				coordinates.latStop = dettagli.stop_lat;
				coordinates.longStop = dettagli.stop_long;
				coordinates.latAddress = dettagli.address_lat;
				coordinates.longAddress = dettagli.address_long;

				initMap(coordinates);*/

			}
			if(data.data.attivita != null){
				var input_checkbox = '';
				var input_textarea = '';
				data.data.attivita.forEach(function(attivita){
					if(attivita.checked_activity == true){
						input_checkbox += '<input type="checkbox" class="checkbox_activity" name="checkbox_activity" value="' + attivita.id + '" checked /> ' + attivita.name + '</br>';
						if(attivita.hasNote == 1){
							input_checkbox += '<textarea rows="2" cols="70" class="form-control" id="textarea_activity_' + attivita.id + '" name="textarea_activity' + attivita.id + '">' + attivita.note + '</textarea>';
						}
					}else{
						input_checkbox += '<input type="checkbox" class="checkbox_activity" name="checkbox_activity" value="' + attivita.id + '" /> ' + attivita.name + '</br>';
						if(attivita.hasNote == 1){
							input_checkbox += '<textarea rows="2" cols="70" class="form-control" id="textarea_activity_' + attivita.id + '" name="textarea_activity' + attivita.id + '" style="display:none;">' + attivita.note + '</textarea>';
						}
					}
				});

				$('#eventDetailsActivities').html(input_checkbox);
				$('#activitiesDiv').show();
			}

          }
      },
      error: function(data,stato) {
          alert("E' avvenuto un errore. Stato della chiamata: "+stato);
      }
    });

}

function loadSecondCategory(idService,idContact, idEvent)
{
  $.ajax({
      url : pathServer + "progest/ws/services/secondCategory/" + idEvent ,
      type: "GET",
      async: false,
      dataType: "json",
      data:{},
      success : function (data,stato) {

          if(data.response == 'OK'){
            // Riempio i servizi
            option = '<option value=""></option>';
            $.each(data.data.services,function(index,service){
                option += '<option  editable="'+service.editable+'" value="' + service.id+
                  '">'+ service.name + '</option>';
            });
            $('#idService').html(option).val(idService);
            // Riempio gli operatori
            option = '<option value=""></option>';
            for (var item in data.data.contatti) {
                option += '<option value="' + data.data.contatti[item].id+ '">' +
                data.data.contatti[item].cognome +' '+data.data.contatti[item].nome  + '</option>';
            }
            if(idContact !== undefined){
                $('#idUser').html(option).val(idContact);
            }else{
                $('#idUser').html(option).val($('#user-caledar-view').val());
            }

			if(data.data.dettagli != null){
				var dettagli = data.data.dettagli;
				$('#idEventDetail').val(dettagli.id);
				$('#idEvento').val(idEvent);
				$('#idOperatore').val(idContact);
				$('#startData').val(dettagli.user_start_date);
				$('#startOra').val(dettagli.user_start_time);
				$('#startRealOra').html(dettagli.real_start);
				$('#startLat').html(dettagli.start_lat);
				$('#startLong').html(dettagli.start_long);
				$('#stopData').val(dettagli.user_end_date);
				$('#stopOra').val(dettagli.user_end_time);
				$('#stopRealOra').html(dettagli.real_end);
				$('#stopLat').html(dettagli.stop_lat);
				$('#stopLong').html(dettagli.stop_long);
				$('#eventDetailsNote').val(dettagli.note);
				if(dettagli.note_importanza){
					$('#eventDetailsNoteImportanza').prop('checked', true);
				}
				if(dettagli.signature == 'Non disponibile'){
					$('#eventDetailsFirma').html(dettagli.signature);
				}else{
					$('#eventDetailsFirma').html('<img src="' + dettagli.signature + '"/>');
				}

				/*var coordinates = [];
				coordinates.latStart = dettagli.start_lat;
				coordinates.longStart = dettagli.start_long;
				coordinates.latStop = dettagli.stop_lat;
				coordinates.longStop = dettagli.stop_long;
				coordinates.latAddress = dettagli.address_lat;
				coordinates.longAddress = dettagli.address_long;

				initMap(coordinates);*/

			}

			$('#activitiesDiv').hide();

			/*if(data.data.attivita != null){
				var input_checkbox = '';
				var input_textarea = '';
				data.data.attivita.forEach(function(attivita){
					if(attivita.checked_activity == true){
						input_checkbox += '<input type="checkbox" name="activities" value="' + attivita.id + '" checked /> ' + attivita.name + '</br>'
						 			       + '<textarea rows="2" cols="70" class="form-control" name="textarea_activity' + attivita.id + '">' + attivita.note + '</textarea>' + '</br>';
					}else{
						input_checkbox += '<input type="checkbox" name="activities" value="' + attivita.id + '" /> ' + attivita.name + '</br>'
										   + '<textarea rows="2" cols="70" class="form-control" name="textarea_activity' + attivita.id + '">' + attivita.note + '</textarea>' + '</br>';
					}
				});

				$('#eventDetailsActivities').html(input_checkbox);
			}*/

		  	$('#template-spinner').hide();
		}
      },
      error: function(data,stato) {
          alert("E' avvenuto un errore. Stato della chiamata: "+stato);
      }
    });
}

function loadOrderServices(id)
{
    $.ajax({
        url : pathServer + "progest/ws/services/getOrderServices/"+id  ,
        type: "GET",
        async: false,
        dataType: "json",
        success : function (data,stato) {

            if(data.response == 'OK'){
              // Riempio i servizi
              option = '<option value=""></option>';
              $.each(data.data ,function(index,servicesOrder){
                  option += '<option value="' + servicesOrder.service.id+
                    '">'+ servicesOrder.service.name + '</option>';
              });
              $('#idService').html(option);
              if(data.data.length == 1){
                  $('#idService').val(data.data[0].service.id).trigger('change');
              }

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
    var idService = $('#idService').val();
    // Se mi arriva la lista delle opzioni degli operatori da appendere lo aggiungo, altrimenti li carico via ajax
    if(operatori !== undefined){
        $('.operatore'+operatoreNum).append(operatori);
    }else{
        loadContattiService(idService,'.operatore'+operatoreNum);
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

function disableOperatori()
{
    var toDisableArray = [];
    $('#idUser > option').attr('disabled',false);
    $('select[name^="operatore"] > option').attr('disabled',false);

    toDisableArray.push($('#idUser').val());
    $('select[name^="operatore"]').each(function() {
        toDisableArray.push($(this).val());
    });
    for (var i = 0; i < toDisableArray.length; i++) {
        var id = $('#idUser').val();
        if(id != toDisableArray[i]){
            $('#idUser > option[value="'+toDisableArray[i]+'"]').attr('disabled',true);
        }
    }
    $('select[name^="operatore"]').each(function() {
        for (var i = 0; i < toDisableArray.length; i++) {
            var id = $(this).val();
            if(id != toDisableArray[i]){
                $(this).find('option[value="'+toDisableArray[i]+'"]').attr('disabled',true);
            }
        }
    });
    $('select[name^="operatore"]').each(function() {
      if($(this).hasClass("select2-hidden-accessible")){
          $(this).trigger("change.select2");
      }
    });
    if($('#idUser').hasClass("select2-hidden-accessible")){
        $('#idUser').trigger("change.select2");
    }
}
