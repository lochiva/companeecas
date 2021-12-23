$(document).ready(function(){

  // ###################################################################################################################################################
  // recupero l'elenco dei Destinatari

  loadClients();

  // ###################################################################################################################################################
  // recupero l'elenco dei tipi di mailing che posso gestire per questo tipologia di invio "SCHEDINI"

  loadType('SCHEDINI');

  // ###################################################################################################################################################
  // recupero la mail dell'utente in sessione per il sender_email

  loadSenderEmail();

  // ###################################################################################################################################################
  // Gestione reload

  $('#action-reload').click(function(){
    loadClients();
  });

  // ###################################################################################################################################################
  // Gestione save Type

  $('#save-type').click(function(){
    saveNewType('SCHEDINI');
  });

  // ###################################################################################################################################################
  // Gestione cerca errori

  $('.btn-find-error').click(function(){

    //alert($("div#box-table-clients .box-body .status-error:first").position().top);
    var pos = $("div#box-table-clients .box-body .status-error:first").position().top - 50;
    $("div#box-table-clients .box-body").scrollTop($("div#box-table-clients .box-body").scrollTop() + pos);

  });

});

// ###################################################################################################################################################
// Gestione delete file

$(document).on('click', '.delete-file-schedino' , function(e){

  e.preventDefault();

  if(confirm("Si è sicuri di voler eliminare il file dall'elenco degli invii?")){

    var file = $(this).attr('data-file');
    console.log('Elimino il file ' + file);

    deleteFile(file);

  }

});

function createList(list){

  $('#table-clients tbody').html('');

  var ckStato = 0;

  $.each(list, function(index, client){

    //alert(client.filename);

    var stato = "";
    var azioni = "";

    azioni = '<a class="delete-file-schedino" href="#" title="Elimina file da inviare" data-file="'+client.filename+'"><span class="badge bg-red"><i class="fa fa-trash"></i></span></a>';

    if(client.denominazione == "" || client.cod_sispac == "" || client.email_contabilita == "" || client.filename == ""){

      stato = '<span class="badge bg-red status-error"><i class="fa fa-close"></i></span>';

      if(client.email_contabilita == "" && client.denominazione != ""){
        azioni += ' <a title="Inserisci la mail di contabilità" href="' + urlEditProfiloAzienda + '/' + client.id + '" target="_blank"><span class="badge bg-blue"><i class="fa fa-pencil-square-o"></i></span></a>';
      }
      ckStato += 1;
    }else{
      stato = '<span class="badge bg-green"><i class="fa fa-check"></i></span>';
    }

    var tr = "";
    tr += '<tr>';
    tr += '<td>';
    tr += (index+1) + '.';
    tr += '<input type="hidden" name="id" value="' + client.id + '" />';
    tr += '<input type="hidden" name="customPath" value="' + client.id + '/" />';
    tr += '</td>';

    tr += '<td>';
    tr += (client.denominazione == ""?'<span class="badge bg-orange"><i class="fa fa-question-circle "></i></span>':client.denominazione);
    tr += '<input type="hidden" name="name" value="' + client.denominazione + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += (client.cod_sispac == ""?'<span class="badge bg-orange"><i class="fa fa-question-circle "></i></span>':client.cod_sispac);
    tr += '</td>';

    tr += '<td>';
    tr += (client.email_contabilita == ""?'<span class="badge bg-orange"><i class="fa fa-question-circle "></i></span>':client.email_contabilita);
    tr += '<input type="hidden" name="email" value="' + client.email_contabilita + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += (client.filename == ""?'<span class="badge bg-orange"><i class="fa fa-question-circle "></i></span>':client.filename);
    tr += '<input type="hidden" name="filename" value="' + client.filename + '" />';
    tr += '</td>';

    tr += '<td>' + stato + '</td>';
    tr += '<td>' + azioni + '</td>';
    tr += '</tr>';

    $('#table-clients tbody').append(tr);

  });

  if(list.length > 0){
    if(ckStato > 0){
      $('#box-table-clients').addClass('box-danger');
      var msg = '<i class="fa fa-exclamation-triangle "></i> ATTENZIONE: ' + ckStato + ' destinatari risultano incompleti o non è stato possibile riconoscerli!';
      $('#box-table-clients span.error-msg').html(msg).fadeIn();
      $('.btn-find-error').fadeIn();
    }else{
      $('#box-table-clients').addClass('box-success');
      $('#save-send').removeAttr('disabled');
      $('#save-test').removeAttr('disabled');
      $('#save').removeAttr('disabled');
    }

  }else{
    $('#box-table-clients').addClass('box-danger');
    var msg = '<i class="fa fa-exclamation-triangle "></i> ATTENZIONE: non ci sono file per questo invio!';
    $('#box-table-clients span.error-msg').html(msg).fadeIn();
  }

}

function loadClients(){

  $("div#box-table-clients .box-body").scrollTop();
  $('#box-table-clients span.error-msg').hide();
  $('.btn-find-error').hide();
  var tr = "";
  tr += '<tr><td id="tr-loading" colspan="100"> Caricamento dati ...</td></tr>';
  $('#table-clients tbody').html(tr);

  var loadingClients = setInterval(loading,1000);

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlClientsSchedini,
    // passo dei dati alla risorsa remota
    data: "",
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        clearInterval(loadingClients);
        createList(res.data);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function deleteFile(file){

  console.log('deleteFile()');

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlDeleteFile,
    // passo dei dati alla risorsa remota
    data: {file:file},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        loadClients();

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}
