var cloningEvent = {};
$(document).ready(function(){
  // Carico nel caso sia presente un utente e il tipo di utente di cui si stava visualizzando il calendario
  var tmpUserId = localStorage.getItem('companee-calendar-idUser');
  var tmpUserType = localStorage.getItem('companee-calendar-userType');
  var frozen = localStorage.getItem('companee-calendar-frozen');
  if(tmpUserType != undefined && tmpUserId != undefined){
      switch (tmpUserType) {
        case '1':
          $('#user-caledar-view').val(tmpUserId);
          break;
        case '2':
          $('#radio-serviceType').hide();
          $('#user-type').val(2);
          $('.select-filter-1').hide();
          $('.select-filter-2').show();
          $('#person-caledar-view').val(tmpUserId);
          break;
      }
  }else if(tmpUserId != undefined){
      $('#user-caledar-view').val(tmpUserId);
  }
  var action = 'getCalendarEvents';
  if(frozen == true || frozen == 'true'){
      action = 'getCalendarEventsFrozen';
      $('.showIfFrozen').show();
      $('.showIfLive').hide();
  }
  var initData = loadTempCalendar();
  var calendarDurations = ['01:00:00','00:30:00','00:20:00','00:10:00','00:05:00'];
  /* initialize the calendar
   -----------------------------------------------------------------*/
  //Date for the calendar events (dummy data)
  var date = new Date();
  var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();
  $('#calendar').fullCalendar({
    defaultView: initData.view,
    defaultDate: initData.date,
    customButtons: {
        zoomIn: {
            text: '+',
            click: function() {
                var duration = $('#calendar').fullCalendar('option', 'slotDuration');
                var find = false;
                for(var i=0; i<(calendarDurations.length-1) && !find; i++){
                    if(calendarDurations[i] == duration){
                        find = true;
                        duration = calendarDurations[i+1];
                    }
                }
                $('#calendar').fullCalendar('option', 'slotDuration',duration);
            }
        },
        zoomOut: {
            text: '-',
            click: function() {
                var duration = $('#calendar').fullCalendar('option', 'slotDuration');
                var find = false;
                for(var i=(calendarDurations.length-1); i > 0 && !find; i--){
                    if(calendarDurations[i] == duration){
                        find = true;
                        duration = calendarDurations[i-1];
                    }
                }
                $('#calendar').fullCalendar('option', 'slotDuration',duration);
            }
        },
    },
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'zoomIn,zoomOut month,agendaWeek,agendaDay'
    },
    buttonText: {
      today: 'oggi',
      month: 'mese',
      week: 'settimana',
      day: 'giorno'
    },
    height: 700,
    //Random default events
    events: pathServer + 'calendar/ws/'+action+'/'+$('#user-type').val()+'/'+($('#user-type').val() == 1 ? $('#user-caledar-view').val():$('#person-caledar-view').val() ),
    locale: 'it',
    editable: true,
    droppable: true, // this allows things to be dropped onto the calendar !!!
    selectable: true,
    defaultTimedEventDuration: "01:00:00",
    forceEventDuration: true,
    displayEventEnd: true,
    minTime: "07:00:00",
    maxTime: "21:00:00",//defaultView:'agendaWeek',
    slotDuration: "00:30:00",
    defaultEventDuration: defaultEventDuration ,
    eventRender: function(event, element) {
      // In caso di visualizzazione del calendario della persona metto come titolo in nome dell'operatore
      if($('#user-type').val() == 2){
        $(element).find('.fc-title').html(event.operatore);
      }
      // Se l'evento ha un id_group vuol dire che c'è una compresenza
      if(event.id_group != 0){
        // Metto l'asterisco della compresenza
        $(element).find('.fc-time').append('<div style="float:right; font-size:26px; font-weight: bold;" >*</div>');
        // Metti il titolo all'evento nel caso di visualizzazione di una persona, perchè l'evento non ha un contatto_id
        // di conseguenza avrà la voce event.operatore vuoto.
        if($('#user-type').val() == 2){
            if(event.group != undefined && event.group != null && event.group.operatori != undefined){
                var operatore = event.group.operatori[0].cognome+' '+event.group.operatori[0].nome;
                $(element).find('.fc-title').html(operatore);
            }
        }
      }

    },
    loading: function(isLoading){
        if(isLoading){
          showHideLoadingSpinner();
        }else{
          showHideLoadingSpinner();
        }
    },
    drop: function (date, allDay) { // this function is called when something is dropped

      // retrieve the dropped element's stored Event Object
      var originalEventObject = $(this).data('eventObject');

      // we need to copy it, so that multiple events don't have a reference to the same object
      var copiedEventObject = $.extend({}, originalEventObject);

      // assign it the date that was reported
      copiedEventObject.start = date;
      copiedEventObject.allDay = allDay;
      copiedEventObject.backgroundColor = $(this).css("background-color");
      copiedEventObject.borderColor = $(this).css("border-color");

      // render the event on the calendar
      // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
      $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

      // is the "remove after drop" checkbox checked?
      if ($('#drop-remove').is(':checked')) {
        // if so, remove the element from the "Draggable Events" list
        $(this).remove();
      }

    },
    eventRightclick: function (event, jsEvent, view) {

        if(!jQuery.isEmptyObject(cloningEvent)){
        		cloningEvent.borderColor = cloningEvent.backgroundColor;
        }
        if(cloningEvent == event){
        		cloningEvent = {};
        }else{
        		event.borderColor = "#000000";
        		cloningEvent = event;
        }

        $("#calendar").fullCalendar( 'rerenderEvents' );

        return false;
    },
    eventClick:  function(event, jsEvent, view) {
      //alert('click su evento');

      clearModale();
      fillModaleCalendar(event);

    },
    eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) {
      //alert('drop');
      if(event.repeated == 1){
        if(confirm("Stai modificando un evento ripetuto, per modificare tutta la serie clicca sull'evento. Proseguendo modificherai solo l'evento selezionato.")){
            if(mySaveRepeatedEvent(event,delta)){
              $('#calendar').fullCalendar( 'refetchEvents' );
            }else{
              revertFunc();
            }
        }else{
          revertFunc();
        }
      }else{
          mySaveEvent(event,delta,revertFunc);
      }
    },
    eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
      if(event.repeated == 1){
        if(confirm("Stai modificando un evento ripetuto, per modificare tutta la serie clicca sull'evento. Proseguendo modificherai solo l'evento selezionato.")){
            if(mySaveRepeatedEvent(event,delta)){
              $('#calendar').fullCalendar( 'refetchEvents' );
            }else{
              revertFunc();
            }
        }else{
          revertFunc();
        }
      }else{
          mySaveEvent(event,delta,revertFunc);
      }

    },
    select: function(start, end, allDay) {

      clearModale();
      if(jQuery.isEmptyObject(cloningEvent)){
          var dStart = start.format('DD/MM/YYYY');
          var dEnd = end.format('DD/MM/YYYY');

          $('#myModalCalendar #checkBoxRepeated').prop('checked',false);
          $('#myModalCalendar #checkBoxRepeated').change();
          $('#myModalCalendar #idUser').val($('#user-caledar-view').val()).trigger("change");
          $('#myModalCalendar #inputStartDate').val(dStart);
          $('#myModalCalendar #inputEndDate').val(dEnd);
          $('#myModalCalendar #UNTIL').val(dStart);
          //inizializzaDatePicker('#inputStartDate',start);

          if(start.hasTime()){

            //alert('ora');
            end = start.clone();
            end.add(moment.duration(this.options.defaultEventDuration));
            var hStart = start.format("HH:mm");
            var hEnd = end.format("HH:mm");
            $('#myModalCalendar #inputStartTime').val(hStart);
            $('#myModalCalendar #inputEndTime').val(hEnd);
            if($('#myModalCalendar #checkAllDay').is(':checked')){
              $('#myModalCalendar #checkAllDay').prop('checked',false);
            }
            $('#myModalCalendar .ora-a').show();
            $('#myModalCalendar .ora-da').show();

          }else{
            //alert('giorno');
            //Se ho un solo giorno di diff vuol dire che ho scelto un giorno solo ad ogni modo le data di end deve essere diminuita di un giorno
            end = end.subtract(1,'days');
            $('#myModalCalendar #inputEndDate').val(end.format('DD/MM/YYYY'));

            $('#myModalCalendar #inputStartTime').val('00:00');
            $('#myModalCalendar #inputEndTime').val('00:00');
            if($('#myModalCalendar #checkAllDay').not(':checked')){
              $('#myModalCalendar #checkAllDay').prop('checked',true);
            }
            $('#myModalCalendar .ora-a').hide();
            $('#myModalCalendar .ora-da').hide();

          }

          $('#inputStartDate').datepicker('update');
          $('#inputEndDate').datepicker('update');
          $('#myModalCalendar #UNTIL').datepicker('update');

          $('#eliminaEvento').hide();
          $('#myModalCalendar').modal('show');
          if($('#user-type').val() == 2){
              var idPerson = $('#person-caledar-view').val();
              if(idPerson != null && idPerson != ''){
                  var textPerson = $('#person-caledar-view option[value="'+idPerson+'"]').text();
                  $('#myModalCalendar #idPerson').append('<option value="'+idPerson+'">'+textPerson+'</option>')
                    .val(idPerson).trigger('change');
              }

          }

      }else{

          fillModaleCalendar(cloningEvent);
          var dStart = start.format('DD/MM/YYYY');
          $('#myModalCalendar #inputStartDate').val(dStart);
          if(!start.hasTime()){
            end = end.subtract(1,'days');
          }else{
            $('#myModalCalendar #inputStartTime').val(start.format("HH:mm"));

            if(cloningEvent.start.hasTime()){
              var duration = moment.duration(cloningEvent.end.diff(cloningEvent.start));
              $('#myModalCalendar #inputEndTime').val(start.add(duration).format("HH:mm"));
            }else{
              $('#myModalCalendar #inputEndTime').val(end.format("HH:mm"));
            }
          }

          $('#myModalCalendar #inputEndDate').val(end.format('DD/MM/YYYY'));
          $('#myModalCalendar #idEvent').val('');
          $('#myModalCalendar #idGoogle').val('');
          $('#myModalCalendar #idGroup').val('');
          $('#inputStartDate').prop('disabled', false);
          $('#inputEndDate').prop('disabled', false);
          $('#checkBoxRepeated').prop('disabled', false);
      }


    },
    viewRender: function( view, element ){
          /*$('.eventsNoteList').html('');
          $('.eventsCompresenzeList').html('');*/
          var temp = localStorage.getItem('companee-calendar-temp');
          var viewType = view.type;
          var date = view.calendar.getDate().toString();
          var userType = $('#user-type').val();
          var userId = '';

          if(temp == undefined || temp == null){
            temp = {};
          }else{
            temp = JSON.parse(temp);
          }

          switch (userType) {
            case '1':
              userId = $('#user-caledar-view').val();
              break;
            case '2':
              userId = $('#person-caledar-view').val();
              break;
          }
          temp[userType+'-'+userId] = {
              view : viewType,
              date : date,
          };
          localStorage.setItem('companee-calendar-temp',JSON.stringify(temp));

    },
    eventAfterAllRender: function(view){
        appendNotesAndCompresenze();
    }
  });

  /* ADDING EVENTS */
  var currColor = "#3c8dbc"; //Red by default
  //Color chooser button
  var colorChooser = $("#color-chooser-btn");
  $("#color-chooser > li > a").click(function (e) {
    e.preventDefault();
    //Save color
    currColor = $(this).css("color");
    //Add color effect to button
    $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
  });
  $("#add-new-event").click(function (e) {
    e.preventDefault();
    //Get value and make sure it is not null
    var val = $("#new-event").val();
    if (val.length == 0) {
      return;
    }

    //Create events
    var event = $("<div />");
    event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
    event.html(val);
    $('#external-events').prepend(event);

    //Add draggable funtionality
    ini_events(event);

    //Remove event from text input
    $("#new-event").val("");
  });

});

/********************************************
 *  GENERAL FUNCTIONS
 *********************************************/

function myDeleteEvent(event){

  var idEvent = event.id;
  var deleted = false;
  var idOperatore = 0;
  if($('#user-type').val() == 1){
    idOperatore = $('#user-caledar-view').val();
  }

  //alert(idEvent);
  deleted = $.ajax({
              url : pathServer + "calendar/ws/deleteEvent/" + idEvent+'/'+idOperatore,
              type  : "post",
              data : "idEvent=" + idEvent,
              dataType : "json",
              async: false,
              success : function (data,stato) {

                  if(data.response == "OK"){
                    deleted = true;
                  }else{
                    alert(data.result.msg);
                    deleted = false;
                  }
                  return deleted;

              },
              error : function (richiesta,stato,errori) {
                  alert("E' avvenuto un errore. Stato della chiamata: "+stato);
              }
          });

  return deleted;

}

function mySaveEvent(event,delta,revertFunc){
  var frozen = localStorage.getItem('companee-calendar-frozen');
  if(frozen == true || frozen == 'true'){
      alert('Non puoi modificare un evento congelato!');
      return false;
  }
  var saved = false;
  var id = event.id;
  var id_google = event.id_google;
  var title = event.title;
  var allDay = event.allDay;
  var start = event.start.format('YYYY-MM-DD HH:mm');
  var end = start;
  var repeated = event.repeated;
  var id_user = event.id_user;
  var backgroundColor = event.backgroundColor;
  var operatori = [];
  if(event.group !== null && event.group.operatori !== undefined){
      $.each(event.group.operatori, function(index,value){
          operatori.push(value.id);
      });
  }else{
      operatori.push(event.id_contatto);
  }
  if(allDay === false){
    if(event.end === null){
      end = event.start.add(1,'hour');
      end = end.format('YYYY-MM-DD HH:mm');
    }else{
      end = event.end.format('YYYY-MM-DD HH:mm');
    }
    allDay = 0;
  }else{
    allDay = 1;
  }

  var note = event.note;

  saved = $.ajax({
              url : pathServer + "calendar/ws/saveEvent/",
              type  : "post",
              data : {id:id,title:title,allDay:allDay,start:start,end:end,note:note,repeated:repeated,id_google:id_google,
                  id_user:id_user, backgroundColor:backgroundColor,operatori:operatori},
              dataType : "json",
              async: false,
              success : function (data,stato) {

                  if(data.response == "OK"){
                    saved = true;
                  }else{
                    saved = false;
                    revertFunc();
                    alert(data.msg);
                  }

                  return saved;

              },
              error : function (richiesta,stato,errori) {
                  alert("E' avvenuto un errore. Stato della chiamata: "+stato);
              }
          });

  return saved;

}

function mySaveRepeatedEvent(event,delta)
{
  var frozen = localStorage.getItem('companee-calendar-frozen');
  if(frozen == true || frozen == 'true'){
      alert('Non puoi modificare un evento congelato!');
      return false;
  }
  var saved = false;
  var id = event.id;
  var id_google = event.id_google;
  var title = event.title;
  var allDay = event.allDay;
  var start = event.start.format('YYYY-MM-DD HH:mm');
  var end = start;
  var repeated = event.repeated;
  var id_azienda = event.id_azienda;
  var id_user = event.id_user;
  var id_order = event.id_order;
  var id_contatto = event.id_contatto;
  var tags = '';
  var backgroundColor = event.backgroundColor;
  if(event.tags.length !== 0){
    for(var i=0; i<event.tags.length;i++){
      if(i>0){
        tags+=',';
      }
      tags += event.tags[i].id;
    }
  }

  if(allDay === false){
    if(event.end === null){
      end = event.start.add(1,'hour');
      end = end.format('YYYY-MM-DD HH:mm');
    }else{
      end = event.end.format('YYYY-MM-DD HH:mm');
    }
    allDay = 0;
  }else{
    allDay = 1;
  }

  if(repeated == 1 && delta._days != 0){
      alert("Non puoi cambiare giorno ad un evento ripetuto!");
      return false;
  }
  var rrule = parseRRULE(event.vobject);
  var note = event.note;

  saved = $.ajax({
              url : pathServer + "calendar/ws/saveEvent/",
              type  : "post",
              data : {id:id,title:title,allDay:allDay,start:start,end:end,note:note,repeated:repeated,repeatedToModify:'thisEvent',
                      id_contatto:id_contatto,id_user:id_user,id_order:id_order,id_azienda:id_azienda,tags:tags,id_google:id_google,
                      EXDATE:rrule.exdate,FREQ:rrule.freq,INTERVAL:rrule.interval,UNTIL:rrule.until,COUNT:rrule.count,
                      repeatedEndType:rrule.repeatedEndType,backgroundColor:backgroundColor},
              dataType : "json",
              async: false,
              success : function (data,stato) {

                  if(data.response == "OK"){
                    saved = true;
                  }else{
                    alert(data.result.msg);
                    saved = false;
                  }

                  return saved;

              },
              error : function (richiesta,stato,errori) {
                  alert("E' avvenuto un errore. Stato della chiamata: "+stato);
              }
          });

  return saved;


}

/**
 * clearModale , funzione che svuota la modale
 * @return void
 */
function clearModale(){

  $('#myModalCalendar #idEvent').val('');
  $('#myModalCalendar #idGoogle').val('');
  $('#myModalCalendar #idGroup').val('');
  $('#myModalCalendar #inputTitle').val('');
  $('#myModalCalendar #inputStartDate').val('');
  $('#myModalCalendar #inputEndDate').val('');
  $('#myModalCalendar #inputStartTime').val('');
  $('#myModalCalendar #inputEndTime').val('');
  $('#myModalCalendar #inputNote').val('');

  if($('#myModalCalendar #checkAllDay').is(':checked')){
    $('#myModalCalendar #checkAllDay').prop('checked',false);
  }
  $('#inputStartDate').prop('disabled', false);
  $('#inputEndDate').prop('disabled', false);
  $('#checkBoxRepeated').prop('disabled', false);
  $('#myModalCalendar .ora-a').show();
  $('#myModalCalendar .ora-da').show();
  document.getElementById("myModalCalendarForm").reset();
  var color = $('[name="optCategory"]').filter(':checked').attr('data-color');
  if(color == '' || color == null){
      color = '#3a87ad';
  }
  if($("#inputColor option[value='"+color+"']").length == 0){
    $('#inputColor').append('<option value="'+color+'" data-color="'+color+'">'+color+'</option>');
  }
  $('#inputColor').colorselector("setValue", color);
  $('#repeatedEndType').change();
  //$('#collapseRepeated').collapse('hide');
  // svuoto i select 2
  $("#idAzienda").html('').trigger("change");
  $("#idOrder").html('').trigger("change");
  $("#idContatto").html('').trigger("change");
  $("#idTags").html('').trigger("change");
  $('#idUser').html('').trigger("change");
  $("#idPerson").html('').trigger("change");
  $("#idService").html('').trigger("change");
  $('.only-cat-1').show();
  $('.add-operatore').removeAttr('disabled');
  $('#compresenze-list').html('');
  $('#inputTitle').attr('readonly',true);

  //Svuoto tab dettagli evento e firma
  $('#myModalCalendar #idEventDetail').val('');
  $('#myModalCalendar #idEvento').val('');
  $('#myModalCalendar #idOperatore').val('');
  $('#myModalCalendar #startData').val('');
  $('#myModalCalendar #startOra').val('');
  $('#myModalCalendar #startRealOra').html('');
  $('#myModalCalendar #startLat').html('');
  $('#myModalCalendar #startLong').html('');
  $('#myModalCalendar #stopData').val('');
  $('#myModalCalendar #stopOra').val('');
  $('#myModalCalendar #stopRealOra').html('');
  $('#myModalCalendar #stopLat').html('');
  $('#myModalCalendar #stopLong').html('');
  $('#myModalCalendar #eventDetailsNote').val('');
  $('#myModalCalendar #eventDetailsNoteImportanza').prop('checked', false);
  $('#myModalCalendar #eventDetailsFirma').html('');
  $('#myModalCalendar #eventDetailsActivities').html('');

}

/**
 * fillModaleCalendar , funzione che riempie la modale con i dati presi dall
 * oggetto dell'evento.
 * @param  {object} event oggetto dell'evento
 * @return void
 */
function fillModaleCalendar(event){

  var frozen = localStorage.getItem('companee-calendar-frozen');
  var start = event.start;
  var end = event.end;
  if( end === null){
    end = start;
  }
  var dStart = start.format('DD/MM/YYYY');
  var dEnd = end.format('DD/MM/YYYY');

  $('#myModalCalendar #idEvent').val(event.id);
  $('#myModalCalendar #idGoogle').val(event.id_google);
  $('#myModalCalendar #inputTitle').val(event.title);
  $('#myModalCalendar #inputNote').val(event.note);
  $('#myModalCalendar #inputStartDate').val(dStart);
  $('#myModalCalendar #inputEndDate').val(dEnd);
  $('#myModalCalendar #idUser').val(event.id_user).trigger("change");
  $('#myModalCalendar #idGroup').val(event.id_group);

  // Se l'evento a un azienda a cui è collegato carico anche i contatti e gli ordini e nel caso sono presenti
  // anche essi nell'evento gli assegno. Uso una variabile globale fillingModal per evitare che vengano eseguiti
  // gli eventi al change di ('#idAzienda') .
  if(event.id_order !== 0){
    //$("#idAzienda").append('<option value="'+event.id_azienda+'">'+event.azienda_denominazione+'</option>');
    fillingModal = true;
    loadEventDetail(event.id,frozen);
    fillingModal = false;
  }else{
    $("[name='optCategory'][value='2']").prop("checked",true);
    $('.only-cat-1').hide();
    $('.add-operatore').attr('disabled','disabled');
    loadSecondCategory(event.id_service,event.id_contatto, event.id);
  }
  // Se sono presenti dei tag li visualizzo
  if(event.tags !== undefined && event.tags !== null){
    var toSelect = [];
    $.each(event.tags, function( index, value ) {
      $("#idTags").append('<option value="'+value.id+'">'+value.name+'</option>');
      toSelect.push(value.id);
    });
    $('#idTags').val(toSelect).trigger("change");
  }
  // check se l'evento e di tipo AllDay
  if(event.start.hasTime()){

    //alert('ora');
    var hStart = start.format("HH:mm");
    var hEnd = end.format("HH:mm");

    $('#myModalCalendar #inputStartTime').val(hStart);
    $('#myModalCalendar #inputEndTime').val(hEnd);
    if($('#myModalCalendar #checkAllDay').is(':checked')){
      $('#myModalCalendar #checkAllDay').prop('checked',false);
      $('#myModalCalendar #checkAllDay').change();
    }

  }else{
    end = moment(end);
    end = end.subtract(1,'days');
    $('#myModalCalendar #inputEndDate').val(end.format('DD/MM/YYYY'));
    $('#myModalCalendar #inputStartTime').val('00:00');
    $('#myModalCalendar #inputEndTime').val('00:00');
    if(!$('#myModalCalendar #checkAllDay').is(':checked')){
      $('#myModalCalendar #checkAllDay').prop('checked',true);
      $('#myModalCalendar #checkAllDay').change();
    }
  }
  // Se l'evento è di tipo ripetuto riempio l'apposita sezione della modale. Uso la funzione parseRRULE per estrapolare
  // i dati di cui ho bisogno dalla rrule dell'oggetto icalendar.
  if(event.repeated == 1){
    var untilMoment;
    $('#myModalCalendar #checkBoxRepeated').prop('checked',true);
    $('#myModalCalendar #checkBoxRepeated').change();
    var rrule = parseRRULE(event.vobject);
    $('#myModalCalendar #INTERVAL').val(rrule.interval);
    $('#myModalCalendar #FREQ').val(rrule.freq);
    $('#myModalCalendar #COUNT').val(rrule.count);
    $('#myModalCalendar #EXDATE').val(rrule.exdate);
    if(rrule.until != ''){
       untilMoment = moment(rrule.until.substr(0,8),'YYYYMMDD');
       $('#myModalCalendar #UNTIL').val(untilMoment.format('DD/MM/YYYY'));
    }
    $('#myModalCalendar #repeatedEndType').val(rrule.repeatedEndType);
    $('#myModalCalendar #repeatedEndType').change();
    $('#inputStartDate').prop('disabled', true);
    $('#inputEndDate').prop('disabled', true);
    $('#checkBoxRepeated').prop('disabled', true);
  }else{
    $('#myModalCalendar #checkBoxRepeated').prop('checked',false);
    $('#myModalCalendar #checkBoxRepeated').change();
  }

  //$('#myModalCalendar #inputColor').val(event.borderColor);
  var color = event.backgroundColor;
  if(color == ""){
    color = "#3a87ad";
  }
  //$(".my-colorpicker2").colorpicker('destroy').colorpicker({color: color});
  if($("#inputColor option[value='"+color+"']").length == 0){
    $('#inputColor').append('<option value="'+color+'" data-color="'+color+'">'+color+'</option>');
    //$('#inputColor').colorselector('destroy');
  }
  $('#inputColor').colorselector("setValue", color);
  //$('#createEventModal #checkAllDay').val(allDay);
  $('#inputStartDate').datepicker('update');
  $('#inputEndDate').datepicker('update');
  $('#UNTIL').datepicker('update');

  $('#eliminaEvento').show();
  if(frozen == 'true' || frozen == true){
      $('.showIfLive').hide();
  }
  $('#myModalCalendar').modal('show');
  if($("#idService option:selected").attr('editable') == 'true'){
      $('#inputTitle').removeAttr('readonly');
  }else{
      $('#inputTitle').attr('readonly',true);
  }
}

function appendNotesAndCompresenze()
{
   var events = $('#calendar').fullCalendar('clientEvents');
   $('.eventsNoteList').html('');
   $('.eventsCompresenzeList').html('');

   $.each(events, function(index, event){
       var titolo = event.title;
       if($('#user-type').val() == 2){
           titolo = event.operatore;
       }
       // Se l'evento ha un id_group vuol dire che c'è una compresenza
       if(event.id_group != 0){
         var operatori = '';
         var first = true;
         // Creo la lista delle compresenze togliendo l'operatore di cui sto visualizzando il calendario
         if(event.group != undefined && event.group != null && event.group.operatori != undefined){
             $.each(event.group.operatori, function(index,operatore){
                 if(operatore.id != $('#user-caledar-view').val() || $('#user-type').val() == 2){
                   if(!first){
                       operatori+= ', ';
                   }
                   operatori += operatore.cognome+' '+operatore.nome;
                   if(first && $('#user-type').val() == 2){
                       titolo = operatori;
                   }
                   first = false;
                 }
             });
         }
         var toAppend = '<div class="box-body"><div class="margin10-bot"><p><span class="text-muted">'+event.start.format('DD/MM/YYYY HH:mm - ')+event.end.format('HH:mm')+
             '</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'+titolo+'</b><br /><i>'+operatori+'</i></p></div></div>';
         $('.eventsCompresenzeList').append(toAppend);
       // Se è presente una nota lo appendo alla lista delle note
       if(event.note != ''){
          var toAppend = '<div class="box-body"><div class="margin10-bot"><p><span class="text-muted">'+event.start.format('DD/MM/YYYY HH:mm - ')+event.end.format('HH:mm')+
              '</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'+titolo+'</b><br /><i>'+event.note+'</i></p></div></div>';
          $('.eventsNoteList').append(toAppend);
       }

       }
   });
}

/**
 * Funzione che carica i dati temporanei di visualizzazione presenti in localStorage
 *
 * @return {object}
 */
function loadTempCalendar()
{
  // Un volta settato l'utente carico il tipo di visualizzazione si stava usando con l'utente
  var temp = localStorage.getItem('companee-calendar-temp');
  var userId = $('#user-caledar-view').val();
  var userType = $('#user-type').val();
  if(userType == '2'){
    userId = $('#person-caledar-view').val();
  }
  var toRet = {
    view: 'agendaWeek',
    date: new Date(),
    default : true,
  };

  if(temp == undefined || temp == null){
    return toRet;
  }
  try {
      temp = JSON.parse(temp);
  } catch (e) {
      localStorage.removeItem('companee-calendar-temp');
      temp = {};
  }
  if(temp[userType+'-'+userId] == undefined || temp[userType+'-'+userId] == null){
    return toRet;
  }
  toRet.view = temp[userType+'-'+userId].view;
  toRet.date = new Date(temp[userType+'-'+userId].date);
  toRet.default = false;

  return toRet;

}

/**
 * [parseRRULE description]
 * Funzione che fa il parsing della rrule in dati che userò per rimepire la modale.
 * @param  {string} vobject stringa contenente l'oggetto icalendar
 * @return {object}         oggetto con i risultati
 */
function parseRRULE(vobject)
{
  var toRet = {};
  var rrule = matchResToStrign(vobject.match(/RRULE:.*$/m));

  toRet.freq = matchResToStrign(rrule.match(/FREQ=[^;]{4,7}/m)).replace('FREQ=','');
  toRet.interval = matchResToStrign(rrule.match(/INTERVAL=[^;\n]{1,10}/m)).replace('INTERVAL=','');
  toRet.count = matchResToStrign(rrule.match(/COUNT=[^;\n]{1,10}/m)).replace('COUNT=','');
  toRet.until = matchResToStrign(rrule.match(/UNTIL=[^;\n]{1,20}/m)).replace('UNTIL=','');
  toRet.exdate =  matchResToStrign(vobject.match(/EXDATE;[^;\n]{1,24}/mg)).replace(/EXDATE;VALUE=DATE:/mg,'');
  if(toRet.count !== ''){
    toRet.repeatedEndType = 'COUNT';
  }else if (toRet.until !== '') {
    toRet.repeatedEndType = 'UNTIL';
  }else{
    toRet.repeatedEndType = 'NEVER';
  }
  return toRet;
}
/**
 * [matchResToStrign description]
 * @param  {array} res  array dei risultati di un match
 * @return {string}     string del risultato, o dei risultati separati da ","
 */
function matchResToStrign(res)
{

  if(res !== null && res[0] !== undefined){
    if(res.length > 1){
      var string  = res.join(',');
      return string;
    }
    return res[0];
  }else{
    return '';
  }

}
