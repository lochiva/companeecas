$(document).ready(function(){
    $('#cloneEvents7-this').click(function(){
        if($('#user-type').val() != 1){
            alert('Non puoi clonare il calendario di una persona.');
            return;
        }
        var operatore = $('#user-caledar-view').val();
        if (confirm("  *ATTENZIONE \n  Sei sicura di copiare il calendario dell'operatore di una settimana fa nella settimana corrente ?  \n\n *Operazione irreversibile")){
          cloneEvents(operatore,7);
        }
    });

    $('#cloneEvents7-all').click(function(){
          if (confirm(" ***********ATTENZIONE \n  Sei sicura di copiare il calendario di TUTTI gli operatori  di una settimana fa nella settimana corrente ?  \n\n ******Operazione irreversibile")){
            cloneEvents('all',7);
          }
    });

/*#9135: aggiungere la voce duplica 2 settimane fa -*/
    $('#cloneEvents14-this').click(function(){
        if($('#user-type').val() != 1){
            alert('Non puoi clonare il calendario di una persona.');
            return;
        }
        var operatore = $('#user-caledar-view').val();
          if (confirm(" *ATTENZIONE \n Sei sicura di copiare il calendario dell'operatore di DUE  settimane fa nella settimana corrente ? \n\n *Operazione irreversibile")){
        cloneEvents(operatore, 14);
      }
    });

    $('#cloneEvents14-all').click(function(){
        if (confirm(" ****ATTENZIONE \n  Sei sicura di copiare il calendario di TUTTI gli operatori di DUE settimane fa nella settimana corrente ? \n\n ****Operazione irreversibile")){
        cloneEvents('all',14);
      }
    });



    $('.checkEvents').click(function(){
        $('#myModalCheck .modal-body').html('');
        checkEvents();
    });

    $('#view-frozenCalendar').click(function(){
          var eventsSource = pathServer + 'calendar/ws/getCalendarEventsFrozen/'+$('#user-type').val()+'/'+($('#user-type').val() == 1 ? $('#user-caledar-view').val():$('#person-caledar-view').val() );
          $('#calendar').fullCalendar( 'removeEvents');
          $('#calendar').fullCalendar( 'removeEventSources');
          $('#calendar').fullCalendar( 'addEventSource', eventsSource);
          localStorage.setItem('companee-calendar-frozen',true);
          $('.showIfFrozen').show();
          $('.showIfLive').hide();
    });
    $('#view-liveCalendar').click(function(){
          var eventsSource = pathServer + 'calendar/ws/getCalendarEvents/'+$('#user-type').val()+'/'+($('#user-type').val() == 1 ? $('#user-caledar-view').val():$('#person-caledar-view').val() );
          $('#calendar').fullCalendar( 'removeEvents');
          $('#calendar').fullCalendar( 'removeEventSources');
          $('#calendar').fullCalendar( 'addEventSource', eventsSource);
          localStorage.setItem('companee-calendar-frozen',false);
          $('.showIfFrozen').hide();
          $('.showIfLive').show();
    });

    $('#frozeCalendar').click(function(){
        var view = $('#calendar').fullCalendar( 'getView' );
        if(view.type !== 'agendaWeek'){
            alert('Devi essere in visualizzazione della settimana per congelare la settimana.');
            return false;
        }
        if(confirm('Sei sicuro di voler congelare la settimana? L\'azione non è reversibile!')){
            frozeCalendarEvents();
        }

    });

});


// Una volta che clicco su un errore, imposto nuovi dati temporanei nel local storage
// così una volta aperto in target blank il calendario carica la visualizzazione giusta
$(document).on('click','.btn-checkEvents',function(){

    var idPerson = $(this).attr('person-id');
    var view = $('#calendar').fullCalendar( 'getView' );
    var date = view.calendar.getDate().toString();
    var temp = localStorage.getItem('companee-calendar-temp')

    localStorage.setItem('companee-calendar-userType',2);
    localStorage.setItem('companee-calendar-idUser',idPerson);
    if(temp == undefined || temp == null){
      temp = {};
    }else{
      temp = JSON.parse(temp);
    }
    temp['2-'+idPerson] = {
        view : 'agendaWeek',
        date : date,
    };
    localStorage.setItem('companee-calendar-temp',JSON.stringify(temp));
    window.open(pathServer+'calendar/','_blank');
});

//chiamata al pulsante btn-checkIgnora che setta ignora_controllo a 1
$(document).on('click', '.btn-checkIgnora', function(){
  var ignora_note = $(this).parent().find('.commento').val();
  var ignora_controllo = 1;
  var id = $(this).attr('order-id');
  var success = 0;
  console.log(id);
  $.ajax({
    url : pathServer + 'calendar/ws/setIgnora/',
    type: "post",
    data: {'id_order' : id, 'ignora' : ignora_controllo, 'note' : ignora_note},
    dataType: "json",
    success: function(){
      success = 1;
    },
    error: function(){
      console.log('Errore');
    }
  });

  $(this).parent().fadeOut();

});

//chiamata al pulsante btn-salvaIgnora che setta ignora_controllo a 1
$(document).on('click', '.btn-salvaIgnora', function(){
  var ignora_note = $(this).parent().find('.commento').val();
  var ignora_controllo = 1;
  var id = $(this).attr('order-id');
  var success = 0;
  console.log(id);
  $.ajax({
    url : pathServer + 'calendar/ws/setIgnora/',
    type: "post",
    data: {'id_order' : id, 'ignora' : ignora_controllo, 'note' : ignora_note},
    dataType: "json",
    success: function(){
      success = 1;
    },
    error: function(){
      console.log('Errore');
    }
  });
  $(this).parent().parent().fadeOut();

  console.log(ignora_note);

});


//chiamata al pulsante btn-checkControlla che setta ignora_controllo a 0
$(document).on('click', '.btn-checkControlla', function(){
  var ignora_note = $(this).parent().find('.commentoControlla').val();
  var ignora_controllo = 0;
  var id = $(this).attr('order-id');
  var success = 0;
  console.log(id);
  $.ajax({
    url : pathServer + 'calendar/ws/setIgnora/',
    type: "post",
    data: {'id_order' : id, 'ignora' : ignora_controllo, 'note' : ignora_note},
    dataType: "json",
    success: function(){
      success = 1;
    },
    error: function(){
      console.log('Errore');
    }
  });

  $(this).parent().fadeOut();

});


//chiamata al pulsante btn-salvaControllo che setta ignora_controllo a 0
$(document).on('click', '.btn-salvaControllo', function(){
  var ignora_note = $(this).parent().find('.commentoControlla').val();
  var ignora_controllo = 0;
  var id = $(this).attr('order-id');
  var success = 0;
  console.log(id);
  $.ajax({
    url : pathServer + 'calendar/ws/setIgnora/',
    type: "post",
    data: {'id_order' : id, 'ignora' : ignora_controllo, 'note' : ignora_note},
    dataType: "json",
    success: function(){
      success = 1;
    },
    error: function(){
      console.log('Errore');
    }
  });
  $(this).parent().parent().fadeOut();

});


//mostro e nascondo la textarea
$(document).on('click', '.apriText', function(){
  $(this).next().toggle();
  $(this).next().next().toggle();
});



function frozeCalendarEvents(){
    var view = $('#calendar').fullCalendar( 'getView' );
    var start = view.start.format('YYYY-MM-DD');
    var end = view.end.format('YYYY-MM-DD');
    $.ajax({
        url : pathServer + "calendar/ws/frozeCaldendar/",
        type  : "get",
        data : {start:start, end:end},
        dataType : "json",
        success : function (data,stato) {
          showHideLoadingSpinner();
          if (data.response == 'OK'){
              $('#calendar').fullCalendar( 'refetchEvents' );
          }else{
              alert(data.msg);
          }
        },
        error: function(data,stato) {
          showHideLoadingSpinner();
          alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        }
      });
}


function cloneEvents(operatore, periodo){
  showHideLoadingSpinner();
  if(operatore === 'all' ){
    operatore = '';
  }
  var view = $('#calendar').fullCalendar( 'getView' );
  if(view.type !== 'agendaWeek'){
      alert('Devi essere in visualizzazione della settimana per clonare.');
      showHideLoadingSpinner();
      return false;
  }
  var start = view.start.clone().subtract(periodo, 'd').format('YYYY-MM-DD');
  var end = view.end.clone().subtract(periodo, 'd').format('YYYY-MM-DD');
  $.ajax({
      url : pathServer + "calendar/ws/cloneEvents/"+periodo +"/"+operatore  ,
      type: "GET",
      async: false,
      dataType: "json",
      data:{start:start, end:end},
      success : function (data,stato) {
          showHideLoadingSpinner();
          if(data.response == 'OK'){
              $('#calendar').fullCalendar( 'refetchEvents' );
          }
      },
      error: function(data,stato){
          showHideLoadingSpinner();
          alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
      }
    });
}

function checkEvents()
{
  showHideLoadingSpinner();
  var view = $('#calendar').fullCalendar( 'getView' );
  if(view.type !== 'agendaWeek'){
      alert('Devi essere in visualizzazione della settimana per fare il controllo.');
      showHideLoadingSpinner();
      return false;
  }
  var start = view.start.format('YYYY-MM-DD');
  var end = view.end.format('YYYY-MM-DD');
  $.ajax({
      url : pathServer + "calendar/ws/check/",
      type: "GET",
      async: false,
      dataType: "json",
      data:{start:start, end:end},
      success : function (data,stato) {
          showHideLoadingSpinner();
          if(data.response == 'OK'){
              appendCheckErrorsInTabs(data.data);
          }
      },
      error: function(data,stato){
          showHideLoadingSpinner();
          alert("E' avvenuto un errore. Lo stato della chiamata: "+stato);
      }
    });
}

/*function appendCheckErrors(data)
{
    var toAppend = '';
    if(data.length == 0){
      toAppend +='<div class="alert alert-success alert-dismissible">'+
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                '<h4><i class="icon fa fa-check"></i> Nessun errore!</h4>'+
                'Durante il controllo non è stato rivelato nessun errore.</div>';
    }

    $.each(data,function(index,value){
        var error = '<div class="alert alert-warning checkEvents-error pointer" person-id="'+value.id_person+
          '"><b>Persona: </b>'+value.person+' <br /><b>Errori:</b><ul>';
        for (var i = 0; i < value.errors.length; i++) {
           if(i > 0){
             //error += ', ';
           }
           error += '<li>'+value.errors[i]+'</li>';
        }
        error += '</ul><br /><b>Note: </b>'+ value.note;
        error += '</a></div>';
        toAppend += error;
    });
    $('#myModalCheck .modal-body').append(toAppend);
}*/


function appendCheckErrorsInTabs(data){
    $('#daControllare').html('');
    $('#daIgnorare').html('');
    var toAppend = '';
    if(data.length == 0){
      toAppend +='<div class="alert alert-success alert-dismissible">'+
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                '<h4><i class="icon fa fa-check"></i> Nessun errore!</h4>'+
                'Durante il controllo non è stato rivelato nessun errore.</div>';
    }

    $.each(data,function(index,value){
      if(value.ignora_controllo == 0){
        var error = '<div class="alert alert-warning checkEvents-error" person-id="'+value.id_person+
          '" order-id="'+value.id+'"><button style="float:right;" type="submit" class="btn btn-default btn-xs btn-checkEvents">Vai al calendario</button><button style="float:right; margin-right: 5px;" type="submit" class="btn btn-default btn-xs btn-checkIgnora" order-id="'+value.id+'">sposta in da ignorare</button><b>Persona: </b>'+value.person+' <br /><b>Errori:</b><ul>';
        for (var i = 0; i < value.errors.length; i++) {
           if(i > 0){
             //error += ', ';
           }
           error += '<li>'+value.errors[i]+'</li>';
        }
        error += '</ul><br /><b>Note: </b>'+ value.note;
        error += '</a><br/><br/><div class="form-group"><button class="btn btn-default btn-xs apriText" style="margin-bottom:5px;">Commenti</button><textarea class="form-control commento" style="display:none;" rows="2" placeholder="Scrivi qui...">'+ value.ignora_note +'</textarea><button style="float:right; margin-top:10px; display:none;" type="submit" class="btn btn-default btn-xs btn-salvaIgnora" order-id="'+value.id+'">sposta in da ignorare</button><br/></div></div>';
        toAppend += error;
      }
    });
    $('#daControllare').append(toAppend);

    toAppend = '';
    $.each(data,function(index,value){
      if(value.ignora_controllo == 1){
        var error = '<div class="alert alert-info checkEvents-error" person-id="'+value.id_person+
          '" order-id="'+value.id+'"><button style="float:right;" type="submit" class="btn btn-default btn-xs btn-checkControlla" order-id="'+value.id+'">sposta in da controllare</button><b>Persona: </b>'+value.person+' <br /><b>Errori:</b><ul>';
        for (var i = 0; i < value.errors.length; i++) {
           if(i > 0){
             //error += ', ';
           }
           error += '<li>'+value.errors[i]+'</li>';
        }
        error += '</ul><br /><b>Note: </b>'+ value.note;
        error += '</a><br/><br/><div class="form-group"><button class="btn btn-default btn-xs apriText" style="margin-bottom:5px;">Commenti</button><textarea class="form-control commentoControlla" style="display:none;" rows="2" placeholder="Scrivi qui...">'+ value.ignora_note +'</textarea><button style="float:right; margin-top:10px; display:none;" type="submit" class="btn btn-default btn-xs btn-salvaControllo" order-id="'+value.id+'">sposta in da controllare</button><br/></div></div>';
        toAppend += error;
      }
    });
    $('#daIgnorare').append(toAppend);
    toAppend = '';
}
