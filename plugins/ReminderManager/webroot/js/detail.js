$(document).ready(function(){

  // ###################################################################################################################################################
  // Caricamento dati e abilitazione interfaccia in base al tipo di submission
  if(idSubmission != 0){
    // Sto aprendo un una submission già esistente
    $('[name="idSubmission"]').val(idSubmission);
    loadSubmissionDetail(idSubmission);

  }else{
    //Nuova submissions
    console.log('new submissions ' + attribute);



    // ###################################################################################################################################################
    // Carico i tipi di tempalte in base all'attributo

    loadType(attribute,0);

    // ###################################################################################################################################################
    // Carico i bottoni

    showButtonsSubmission(0, attribute)

    // ###################################################################################################################################################
    // Abilito il bottone per il salvataggio del tipo

    $('#save-type').removeAttr('disabled');
    $('#save-type').removeAttr('title');

    // ###################################################################################################################################################
    // Carico il sender

    loadSenderEmail()

    // ###################################################################################################################################################
    // Gestione save Type

    $('[name="attribute"]').val(attribute);


    // ###################################################################################################################################################
    // Tolgo la label di caricamento che non serve....

    $('#table-recipent-saved tbody').html('');


  }

  //####################################################################################################################################################
  // Caricamento destinatari
  loadPossibleRecipients(); 

  // ###################################################################################################################################################
  // Gestione caricamento uffici

  //loadOffices();

  // ###################################################################################################################################################
  // Gestione caricamento soci di riferimento

  //loadPartners();

  // ###################################################################################################################################################
  // Gestione cambio filtri

  /*$('.filter-recipients').change(function(){
    loadPossibleRecipients();
  });*/

  // ###################################################################################################################################################
  // Gestione save Type

  $('#save-type').click(function(){
    saveNewType($('[name="attribute"]').val());
  });

  // ###################################################################################################################################################
  // Gestione return to saved

  $('#return-saved').click(function(){
    var newStatus = 0;
    changeStatusSubmission(newStatus,idSubmission);
  });

  // ###################################################################################################################################################
  // Gestione stop

  $('#stop').click(function(){
    var newStatus = 4;
    changeStatusSubmission(newStatus,idSubmission);
  });

  // ###################################################################################################################################################
  // Gestione riavvio

  $('#restart').click(function(){
    var newStatus = 1;
    changeStatusSubmission(newStatus,idSubmission);
  });

  // ###################################################################################################################################################
  // Gestione clona

  $('#clone').click(function(){
    cloneSubmission(idSubmission);
  });

});


function loadSubmissionDetail(id){

  var loadingClients = setInterval(loading,1000);

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlSubscriptionDetail,
    // passo dei dati alla risorsa remota
    data: {id:id},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        console.log(res);

        showMessageStatusSubmission(res.data.status, res.data.status_text, res.data.attribute);
        showButtonsSubmission(res.data.status, res.data.attribute);
        enableDisableFilter(res.data.status, res.data.attribute);

        clearInterval(loadingClients);
        createListRecipientSaved(res.data.SubmissionsEmails);
        showBtnActionRecipient(res.data.status, res.data.attribute);

        if(res.data.status != 0){
          loadFormSubmissionDisabled(res.data);
        }else{
          loadFormSubmission(res.data);
        }

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

function refreshRecipient(id){

  var loadingClients = setInterval(loading,1000);

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlSubscriptionDetail,
    // passo dei dati alla risorsa remota
    data: {id:id},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        clearInterval(loadingClients);
        createListRecipientSaved(res.data.SubmissionsEmails);
        showBtnActionRecipient(res.data.status, res.data.attribute);

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

function createListRecipientSaved(list){

  //console.log(list);

  $('#table-recipent-saved tbody').html('');

  $.each(list, function(index, client){
    var id = 0;
    $.each(client.SubmissionsEmailsCustoms, function(index2, custom){
      if(custom.custom_key == "id_azienda"){
        id = custom.custom_value;
      }
    });

    emailTosend.push(id);

    var tr = "";
    tr += '<tr id="email-to-send-'+id+'">';

    tr += '<td>';
    tr += (index+1) + '.';
    // Devo inserire l'hidden con l'id azienda che mi arriva dai custom_key
    tr += '<input type="hidden" name="id" value="' + id + '" />';
    tr += '<input type="hidden" name="id_email" value="' + client.id + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += client.name;
    tr += '<input type="hidden" name="name" value="' + client.name + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += client.email;
    tr += '<input type="hidden" name="email" value="' + client.email + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += client.sended == 1 ? '<span class="badge bg-green" title="Inviata"><i class="fa fa-check"></i></span>' : client.sended == 0 ? '<span class="badge bg-blue" title="Da Inviare"><i class="fa fa-envelope-o"></i></span>' : '<span class="badge bg-orange" title="Esclusa"><i class="fa fa-envelope-o"></i></span>' ;
    tr += '</td>';

    tr += '<td>';
    if(client.SubmissionsEmailsAttachements.length > 0){
      tr += '<a class="show-attahement get-attachments-mail" href="#" title="Visualizza l\'allegato" data-id="' + client.id + '"><span class="badge bg-info"><i class="fa  fa-file-pdf-o"></i></span>' ;
    }
    if(client.sended == 0){

      tr += '<a class="btn-action-recipient skip-mail" href="#" title="Salta invio" data-id="' + client.id + '"><span class="badge bg-orange"><i class="fa  fa-close"></i></span>' ;
      if(attribute != "SCHEDINI" ){
        tr += '<a class="btn-delete-email-to-send" data-id="'+id+'"><span class="badge bg-red" title="Elimina"><i class="fa fa-trash"></i></span></a>';
      }
    }else if(client.sended == 2){
      tr += '<a class="btn-action-recipient restore-mail" href="#" title="Riattiva invio" data-id="' + client.id + '"><span class="badge bg-green"><i class="fa  fa-plus"></i></span>' ;
    }
    tr += '</td>';

    tr += '</tr>';

    $('#table-recipent-saved tbody').append(tr);

    $('#save').removeAttr('disabled');
    $('#save').prop('title','Salva');
    $('#save-send').removeAttr('disabled');
    $('#save-send').prop('title','Salva e Invia');
    $('#save-test').removeAttr('disabled');
    $('#save-test').prop('title','Salva e Invia Test');

  });

}

function showMessageStatusSubmission(status, status_text, attribute){

  if(status == 2){
    $('#invio-in-corso').show();
  }else if(status == 1){
    $('#invio-da-inviare').show();
  }else if(status == 3){
    $('#invio-inviato').show();
  }else if(status == 0 && attribute == "SCHEDINI"){
    $('#invio-da-inviare-schedini').show();
  }else if(status == 0 && attribute == "GENERIC"){
    $('#invio-da-inviare-generic').show();
  }else if(status == 4){
    $('#invio-stop').show();
  }else if(status == 5){
    $('#invio-error').show();
    $('#invio-error #status_text').html(status_text);
  }

}

function enableDisableFilter(status, attribute){

  if(attribute == "SCHEDINI" || (attribute == "GENERIC" && status > 0)){
    $('.box-row-one-filter').addClass('disabled');
    $('.box-row-one-filter').hide();
    $('.box-row-one-filter-results').hide();
    $('.box-row-one-recipent').parent().removeClass('col-md-5').addClass('col-md-12');
  }

}

function showButtonsSubmission(status, attribute){

  if(status == 1){ // da inviare

    $('#save-type').hide();
    $('#save').hide();
    $('#save-test').hide();
    $('#save-send').hide();
    $('#stop').show();
    $('#stop').removeAttr('disabled');
    $('#return-saved').show();
    $('#return-saved').removeAttr('disabled');
    $('#clone').hide();
    $('#restart').hide();

    $('.btn-action-recipient').show();

  }else if(status == 2){ // in corso

    $('#save-type').hide();
    $('#save').hide();
    $('#save-test').hide();
    $('#save-send').hide();
    $('#stop').show();
    $('#stop').removeAttr('disabled');
    $('#return-saved').hide();
    $('#clone').hide();
    $('#restart').hide();

    $('.btn-action-recipient').hide();

  }else if(status == 3){ // inviato

    $('#save-type').hide();
    $('#save').hide();
    $('#save-test').hide();
    $('#save-send').hide();
    $('#stop').hide();
    $('#return-saved').hide();
    $('#clone').show();
    $('#clone').removeAttr('disabled');
    $('#restart').hide();

    $('.btn-action-recipient').hide();

  }else if(status == 0){ // salvato

    $('#save-type').show();
    $('#save').show();
    if(emailTosend.length>0){
      $('#save').removeAttr('disabled');
      $('#save').prop('title','Salva');
    }else{
      $('#save').prop('title','Non ci sono destinatari');
    }
    $('#save-test').show();
    if(emailTosend.length>0){
      $('#save-test').removeAttr('disabled');
      $('#save-test').prop('title','Salva e Invia Test');
    }else{
      $('#save-test').prop('title','Non ci sono destinatari');
    }
    $('#save-send').show();
    if(emailTosend.length>0){
      $('#save-send').removeAttr('disabled');
      $('#save-send').prop('title','Salva e Invia');
    }else{
      $('#save-send').prop('title','Non ci sono destinatari');
    }
    $('#stop').hide();
    $('#return-saved').hide();
    $('#clone').hide();
    $('#restart').hide();

    $('.btn-action-recipient').show();

  }else if(status == 4){ // sospeso

    $('#save-type').hide();
    $('#save').hide();
    $('#save-test').hide();
    $('#save-send').hide();
    $('#stop').hide();
    $('#return-saved').hide();
    $('#clone').show();
    $('#clone').removeAttr('disabled');
    $('#restart').show();
    $('#restart').removeAttr('disabled');

    $('.btn-action-recipient').show();

  }else if(status == 5){ //Errore

    $('#save-type').hide();
    $('#save').hide();
    $('#save-test').hide();
    $('#save-send').hide();
    $('#stop').hide();
    $('#return-saved').hide();
    $('#clone').hide();
    $('#restart').show();
    $('#restart').removeAttr('disabled');

    $('.btn-action-recipient').show();

  }

}

function showBtnActionRecipient(status, attribute){

  //alert(status);


  if(status == 1){ // da inviare

    $('.btn-action-recipient').show();
    $('.btn-delete-email-to-send').hide();
    $('.btn-delete-all-email-to-send').hide();

  }else if(status == 2){ // in corso

    $('.btn-action-recipient').hide();
    $('.btn-delete-email-to-send').hide();
    $('.btn-delete-all-email-to-send').hide();

  }else if(status == 3){ // inviato

    $('.btn-action-recipient').hide();
    $('.btn-delete-email-to-send').hide();
    $('.btn-delete-all-email-to-send').hide();

  }else if(status == 0 && attribute == "SCHEDINI"){ // salvato

    $('.btn-action-recipient').show();
    $('.btn-delete-email-to-send').hide();
    $('.btn-delete-all-email-to-send').hide();

  }else if(status == 0 && attribute == "GENERIC"){ // salvato

    $('.btn-action-recipient').hide();
    $('.btn-delete-all-email-to-send').show();

  }else if(status == 4){ // sospeso

    $('.btn-action-recipient').show();
    $('.btn-delete-email-to-send').hide();
    $('.btn-delete-all-email-to-send').hide();

  }else if(status == 5){ //Errore

    $('.btn-action-recipient').show();
    $('.btn-delete-email-to-send').hide();
    $('.btn-delete-all-email-to-send').hide();

  }

}

function loadFormSubmissionDisabled(data){

  list = [{id:data.id_submission_type, text:data.type_name}];
  createListType(list,true);

  $('[name="title"]').val(data.name).prop('disabled','disabled');
  $('[name="sender_email"]').val(data.sender_email).prop('disabled','disabled');
  $('[name="object"]').val(data.object).prop('disabled','disabled');

  //$('[name="body"]').prop('disabled','disabled');
  setTimeout(function () { 
    $('[name="body"]').html(data.text); 
  }, 1000);

  $('[name="template"]').filter('[value=' + data.template + ']').prop('checked', true);
  $('[name="template"]').prop('disabled', 'disabled');

  $('[name="attribute"]').val(data.attribute);

  loadAttachments(data.SubmissionsAttachements);

}

function loadFormSubmission(data){

  console.log(data.attribute);
  console.log(data.id_submission_type);

  loadType(data.attribute, data.id_submission_type);

  $('[name="title"]').val(data.name).removeAttr('disabled');
  $('[name="sender_email"]').val(data.sender_email).removeAttr('disabled');
  $('[name="object"]').val(data.object).removeAttr('disabled');

  //$('[name="body"]').html(data.text).removeAttr('disabled');
  setTimeout(function () { $('[name="body"]').html(data.text); }, 1000);

  $('[name="template"]').filter('[value=' + data.template + ']').prop('checked', true);
  $('[name="template"]').removeAttr('disabled');

  //$('iframe').contents().find('.wysihtml5-editor').html(data.text);
  //$('iframe').contents().find('.wysihtml5-editor').attr('contenteditable',true);

  $('[name="attribute"]').val(data.attribute);

  loadAttachments(data.SubmissionsAttachements);

}
