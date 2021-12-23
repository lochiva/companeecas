$(document).ready(function(){

  // Per impedire refresh della pagina
  $(".operazioni-notifiche").submit(function(e) {
    e.preventDefault();
  });

  // Clono il contenuto di base dell'email
  var divClone = document.getElementById("email-content").innerHTML;

  /*
  * Funzione di invia notifica, che alla pressione del tasto fa una chiamata ajax
  * al web service inviaNotificaAzienda seguito dall'id dell'azienda. Nella chiamata
  * mette i dati secondo il tipo di notifica.
  */
  $('.inviaNotifica').click(function(){

    var tipoNotifica = $(this).val();
    var formData = '';
    var partnerId = $('input[name="partnerId"]').val();
    // In caso di email inserisco email e subjcet, atrimenti solo testo
    if(tipoNotifica == 'email'){
      formData = {tipo:tipoNotifica,email:$("#email-content").html(),subject:$('input[name="subject"]').val()};
    }else{
      formData = {tipo:tipoNotifica,testo:$('#testo-'+tipoNotifica).val()};
    }

    $.ajax({
        url : pathServer + 'crediti/Ws/inviaNotificaAzienda/'+$('#idAzienda').val()+'/'+( partnerId !== '' ? partnerId :'' ),
        type: "POST",
        data: formData,
        success: function(data, textStatus, jqXHR)
        {
            //$("#table-report").trigger("update");
            var res = $.parseJSON(data);


            if(res.response == 'OK'){
              // Aggiorno la tabella dei credits Totals
              $("#table-report").trigger("update");
              alert(res.msg);



            }else{

              alert(res.msg);
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert("Errore Server!");

        }
    });

  });

  $(document).on('click','.action-credit',function(){
    // Mostro la modale e svuoto la sua tabella
    $('#myModalComunicazione').modal('show');
    $("#table-credits-azienda tbody").html('');
    $("#table-notifiche-azienda tbody").html('');
    // Reinserisco il contenuto di base dell'email
    $("#email-content").html(divClone);
    // svuoto il textare del messaggio
    $(".notifica-testo").val('');

    // Leggo l'id dell'azienda e la sua denominazione
    var idAzienda = $(this).prop('value');
    var nomeAzienda = $('#denominazione-azienda').html();

    // Nel caso la modale venga aperta in info aziende, devo leggere l'attr invece del prorp di value
    if(idAzienda === undefined){
      idAzienda = $(this).attr('value');
    // In caso non sia in info azinde, la denominazione la prendo direttamente dalla tabella sortabile
    }else if(nomeAzienda === undefined){
      nomeAzienda = $(this).parent().parent().children()[4].innerHTML;
    }
    // Imposto l'id azienda in un attributo hidden nella modale
    $('#idAzienda').val(idAzienda);

    // Creo un oggetto della data odierna e ne realizzo una formattazione italiana.
    var today = new Date();
    formatToday = ('0' + today.getDate()).slice(-2) + '/'+ ('0' + (today.getMonth()+1)).slice(-2) + '/'+ today.getFullYear();
    // Imposto l'oggetto dell'email e il titolo della modale
    $('input[name="subject"]').val('Estratto contabile al '+formatToday);
    $('.modal-title').html('Gestione Crediti '+ nomeAzienda);
    $('#customerName').html(nomeAzienda);

    // Faccio la chiamata ajax al webservice che mi ritorna tutti i crediti
    $.ajax({
        url : pathServer + 'crediti/Ws/getCreditsAzienda/'+idAzienda,
        type: "GET",

        success: function(data, textStatus, jqXHR)
        {

            var res = $.parseJSON(data);


            if(res.response == 'OK'){

              /* In un foreach ciclo tutti i dati ricevuti e li uso per riempire la tabella nel tab correnti
              * con le opportune modifiche
              */
              $.each(res.data, function(i, item) {
                    //var d_emissione = new Date(item.data_emissione);
                    if(i !== 'total'){
                      var from = item.data_scadenza.split("/");
                      var d_scadenza = new Date(from[2], from[1] - 1, from[0]);

                      var data = '<tr><td>'+item.data_emissione+'</td>'+'<td>'+item.data_scadenza+'</td><td>'+item.num_documento+'</td>';

                      if(d_scadenza.getTime() < today.getTime()){
                         data +='<td class=" badge" >'+item.importo+'</td></tr>';
                      }else{
                        data +='<td>'+item.importo+'</td></tr>';
                      }

                      $("#table-credits-azienda tbody").append(data);
                    }else if(i == 'total'){
                      var totali = '<tr class="table-credits-azienda-footer"><td colspan="2" >Totali crediti : '+item.crediti+' </td><td colspan="2">Totali crediti scaduti: '+item.crediti_scaduti+'</td></tr>';
                      $("#table-credits-azienda tbody").append(totali);
                    }
                });

            }else{

              alert(res.msg);
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert("Errore Server!");

        }
    });
    // Faccio la chiamata ajax al webservice che mi ritorna tutti i credit_totals e le notifiche eseguite
    $.ajax({
        url : pathServer + 'crediti/Ws/getTotalsCreditsAziendaNotifiche/'+idAzienda,
        type: "GET",

        success: function(data, textStatus, jqXHR)
        {
            //$("#table-report").trigger("update");
            var res = $.parseJSON(data);


            if(res.response == 'OK'){


              /* In un foreach ciclo tutti i dati ricevuti e li uso per riempire la tabella nel tab storico
              * e faccio seguire ad ogni dato della tabella credits_totals le relative notifiche.
              */
              $.each(res.data, function(i, item) {

                    //var dConto = new Date(item.data_conto);

                    //var date_conto = ('0' + dConto.getDate()).slice(-2) + '/'+ ('0' + (dConto.getMonth()+1)).slice(-2) + '/'+ dConto.getFullYear();
                    //var date = d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear();
                    var data = '<tr><td>'+item.data_conto+'</td>'+'<td>'+item.rating+'</td><td>'+item.total+'</td><td>'+item.total_scaduti+'</td></tr>';


                    $("#table-notifiche-azienda-tbody").append(data);
                    if(!jQuery.isEmptyObject(item.Notifiche)){
                        $.each(item.Notifiche, function(i, item) {
                          var testo = $.parseJSON(item.testo);
                          var notifica = '';

                          var d = new Date(item.created);
                          var dateNotifica = ('0' + d.getDate()).slice(-2) + '/'+ ('0' + (d.getMonth()+1)).slice(-2) + '/'+ d.getFullYear();

                          var data = '<tr><td colspan="4"><b>Notifica inviata il: '+dateNotifica+' Tipo notifica: '+item.type+'</b>';
                          data += '<button notifica-id="'+item.id+'" class="fa fa-info btn btn-sm btn-flat btn-default expand-info pull-right"></button></tr>';

                          if(item.type == 'email'){
                            notifica = '<tr notifica-messaggio="'+item.id+'" hidden ><td  colspan="2" >Destinatario: '+testo.to+' </td><td  colspan="2" >Oggetto: '+testo.subject+' </td></tr>';
                            notifica += '<tr notifica-messaggio="'+item.id+'" hidden ><td  colspan="4" > '+testo.messaggio+' </td></tr>';
                          }else{
                            notifica = '<tr notifica-messaggio="'+item.id+'" hidden ><td  colspan="4" >Messaggio: '+testo.messaggio+'</td></tr>';
                          }


                          $("#table-notifiche-azienda-tbody").append(data+notifica);
                        });
                    }
                });
                // Functione per mostrare o nascondere i dettagli di una notifica al click del bottone
                $(".expand-info").click(function(){

                  var id = $(this).attr('notifica-id');

                  if( $('tr[notifica-messaggio="'+id+'"]').is(':hidden') ){
                    $('tr[notifica-messaggio="'+id+'"]').show();
                  }else{
                    $('tr[notifica-messaggio="'+id+'"]').hide();
                  }

                });

            }else{

              alert(res.msg);
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert("Errore Server!");

        }

    });
    // Faccio la chiamata ajax al webservice che mi ritorna tutti i dati che userò per riempire l'email
    $.ajax({
        url : pathServer + 'crediti/Ws/getAziendaInfoForNotifiche/'+idAzienda,
        type: "GET",

        success: function(data, textStatus, jqXHR)
        {

            var res = $.parseJSON(data);


            if(res.response == 'OK'){
              var table2 = '<p>Mentre sono in scadenza le seguenti competenze: </p>';
              table2 += '<table class="email-table"><thead><tr><th>Data</th><th>Numero</th><th>Importo</th></tr></thead><tbody>';
              var table2Rows = '';

              $.each(res.data.credits, function(i, item) {
                  var from = item.data_scadenza.split("/");
                  var d_scadenza = new Date(from[2], from[1] - 1, from[0]);
                  var data = '';

                  if(d_scadenza.getTime() < today.getTime()){
                    data = '<tr><td>'+item.data_emissione+'</td><td>'+item.num_documento+'</td>'+'<td>'+item.importo+'</td></tr>';
                  }else{
                    table2Rows += '<tr><td>'+item.data_emissione+'</td><td>'+item.num_documento+'</td>'+'<td>'+item.importo+'</td></tr>';
                  }


                  $("#email-table tbody").append(data);
                });
              var final = '<p> per un totale di '+res.data.somma+' .</p>';
              $("#email-content").append(final);

              if(table2Rows !== ''){
                table2 += table2Rows + '</tbody></table>';
                $("#email-content").append(table2);
              }
              $('input[name="partnerId"]').val(res.data.partnerId);
              $("#email-content").append('<br /><p>La preghiamo di considerare nulla la presente email nel caso abbia  già provveduto al pagamento.</p>');
              $("#email-content").append('<p>Un cordiale saluto</p><p>La segreteria </p>');

            }else{

              alert(res.msg);
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert("Errore Server!");

        }

    });

  });

  $('#myModalComunicazione .tab-modal-credits').click(function(){

      var id = $(this).attr('data-id');

      //Nascondo tutti e mostro solo quello giusto
      $('#myModalComunicazione .form-attuale').hide();
      $('#myModalComunicazione #' + id).show();

      //deseleziono tutti tab e seleziono solo quello giusto
      $('#myModalComunicazione .tab-modal-credits').removeClass('active');
      $(this).addClass('active');

  });
  // Cambio azione notifica
  $('input[name="azione-notifica"]').change(function(){

    var id = $(this).val();

    $('#myModalComunicazione .operazioni-notifiche').hide();
    $('#myModalComunicazione #' + id).show();

  });




});
