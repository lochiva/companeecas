$(document).ready(function () {
  $('select[name="companies[0][id]"]').on("change", function () {

    $('form#add-cost')[0].reset();
    $('form#add-cost input[required], form#add-costt select[required]').each(function() {
      $(this).parent().removeClass('has-error');
    });

    $("#accordion").text("");

    if ($(this).val() == "all") {
      $('#save-statment').prop('disabled', true);
      $("#company_specific").addClass("hidden");
      $('#add-cost').hide();
      $('#add-cost input[type=hidden][name=statement_company]').val('');
      $('#save-cat').prop('disabled', true);

      $('#comments').hide();
      $('#btn-actions').hide();
      $('#status-container').hide();

      $('#delete-statement').prop('disabled', true);
      $('#cost-headers').html("Spese (vista aggregata dell'ATI)");
      $.ajax({
        url:
          pathServer +
          "aziende/ws/getCosts/" +
          "all" +
          "/" +
          $('input[name="id"]').val(),
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            let cats = res.data;
            for (let cat in cats) {
              let toAppend = '<div class="panel panel-default">';

              if (cats[cat]['id'] == 'grandTotal') {
                toAppend += 
                `<div class="panel-heading" role="tab" style="background-color: white;">
                <h4>`;
              } else {
                toAppend +=
                `<div class="panel-heading" role="tab">
                <h4 class="panel-title">`;
              }
              toAppend +=
                cats[cat]["name"] +
                `<span class="f-right">&euro; ` + cats[cat]["tot"] + `</span>
                  </h4>
                    </div>
                    <div id="` +
                      cats[cat]["name"] +
                      `" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                    </div>
                </div>`;
              $("#accordion").append(toAppend);
            }
          } else {
            $("#accordion").append("<div>Nessuna spesa presente</div>");
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });
    } else {
      $('#cost-headers').html('Spese');
      $('#save-statment').prop('disabled', false);
      let id = $(this).val();
      $('#add-cost input[type=hidden][name=statement_company]').val(id);
      $('#save-cat').prop('disabled', false);
      $("#company_specific").removeClass("hidden");

      $('#status-container').show();

      // Dati
      $.ajax({
        url: pathServer + "aziende/ws/getStatementCompany/" + id,
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            $('input[name="companies[0][company_id]"]').val(res.data.company_id);
            $('input[name="companies[0][billing_reference]"]').val(
              res.data.billing_reference
            );
            $('input[name="companies[0][billing_date]"]').val(res.data.billing_date);
            $('input[name="companies[0][billing_net_amount]"]').val(
              res.data.billing_net_amount
            );
            $('input[name="companies[0][billing_vat_amount]"]').val(
              res.data.billing_vat_amount
            );

            if (res.data.uploaded_path.length) {
              $("#file_upload").removeClass("hidden");
              $("#uploaded_path").prop(
                "href",
                pathServer + "aziende/ws/downloadFileStatements/" + res.data.id
              );
            } else {
              $("#file_upload").addClass("hidden");
            }
            $('#status').text(res.data.status.name);
            $('#status').data('status_id', res.data.status_id);
            $('textarea[name=notes]').val(res.data.notes);
            $('#text-notes').text(res.data.notes);

            // In corso
            if (res.data.status_id == 1) {
              $('#status').removeClass().addClass('badge btn-default');

              if (role === 'ente') {
                $('#send').data('id', res.data.id);
                $('#send').prop('disabled', false);

                $('#btn-actions').show();
                $('#comments').hide();
                $('textarea[name=notes]').prop('disabled', true);

                $('#save-statment').prop('disabled', false);
                $('#delete-statement').prop('disabled', false);
              } else {
                $('textarea[name=notes]').prop('disabled', true);
                $('#btn-actions').hide();
                $('#comments').hide();
              }

            // Approvato
            }  else if (res.data.status_id == 2) {
              $('#status').removeClass().addClass('badge btn-success');

              $('textarea[name=notes]').prop('disabled', true);
              $('textarea[name=notes]').hide();
              $('#btn-actions').hide();
              $('#comments').show();

              $('#save-statment').prop('disabled', true);
              $('#delete-statement').prop('disabled', true);

              $('#text-notes').show();

            // Integrazione
            } else if (res.data.status_id == 3) {
              $('#status').removeClass().addClass('badge btn-warning');

              $('#text-notes').hide();
              if(role === 'ente') {
                $('#send').data('id', res.data.id);
                $('#send').prop('disabled', false);
                
                $('#save-statment').prop('disabled', false);
                $('#delete-statement').prop('disabled', false);

                $('#btn-actions').show();
                $('#comments').show();

                $('textarea[name=notes]').prop('disabled', false);


              } else {
                $('#deny').prop('disabled', true);
                $('#approve').prop('disabled', true);

                $('#btn-actions').show();
                $('#comments').show();

                $('textarea[name=notes]').prop('disabled', true);
              }

            // In approvazione
            } else if (res.data.status_id == 4) {

              $('#status').removeClass().addClass('badge btn-info');
              $('#text-notes').hide();
              if (role === 'ente') {
                $('#btn-actions').show();
                $('#send').prop('disabled', true);

                $('#save-statment').prop('disabled', true);
                $('#delete-statement').prop('disabled', true);

                $('#comments').show();
                $('textarea[name=notes]').prop('disabled', true);
              } else {
                $('#deny').data('id', res.data.id);
                $('#deny').prop('disabled', false);

                $('#approve').data('id', res.data.id);
                $('#approve').prop('disabled', false);

                $('#btn-actions').show();
                $('#comments').show();

                $('textarea[name=notes]').prop('disabled', false);
              }

              
            }
          } else {
            alert(res.msg);
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });

      // Costi
      $.ajax({
        url: pathServer + "aziende/ws/getCosts/" + false + "/" + id,
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            let cats = res.data;
            loadCosts(cats);
          } else {
            $("#accordion").append("<div>Nessuna spesa presente</div>");
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });
    }
  });

  $("form#main-form input[required], form#main-form select[required]").each(function () {
    $(this).on("change", function () {
      $(this).parent().parent().removeClass("has-error");
    });
  });

  $("input[name=file]").on("change", function () {
    if ($("input[name=file]").prop("files").length) {
      $('input[name="companies[0][uploaded_path]"]').val(
        $("input[name=file]").prop("files")[0].name
      );
    } else {
      $('input[name="companies[0][uploaded_path]"]').val();
    }
  });

  $("select[name=period_id]").on("change", function () {
    let opt = $("select[name=period_id] option:selected").text();

    if (opt == "Personalizzato") {
      $("input[name=period_label]").attr("readonly", false);
      $("input[name=period_start_date]").attr("readonly", false);
      $("input[name=period_end_date]").attr("readonly", false);

      $("input[name=period_label]").val("");
      $("input[name=period_start_date]").val("");
      $("input[name=period_end_date]").val("");
    } else {
      $("input[name=period_label]").attr("readonly", true);
      $("input[name=period_start_date]").attr("readonly", true);
      $("input[name=period_end_date]").attr("readonly", true);

      $.ajax({
        url:
          pathServer +
          "aziende/ws/getPeriod/" +
          $("select[name=period_id]").val(),
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            $("input[name=period_label]").val(res.data.label);
            $("input[name=period_start_date]").val(res.data.start_date);
            $("input[name=period_end_date]").val(res.data.end_date);
          } else {
            alert(res.msg);
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });
    }
  });

/*   $("#form-statement").submit(function (e) {
    e.preventDefault();
    let valid = true;
    $("input[required], select[required]").each(function () {
      $(this).parent().parent().removeClass("has-error");
    });

    $("input[required], select[required]").each(function () {
      if ($(this).val() == false) {
        $(this).parent().parent().addClass("has-error");
        valid = false;
      }
    });

    if (valid) {
      $("#form-statement").submit();
    }
  }); */

  $('#add-cost button[type=reset]').click(function() {
    $('form#add-cost #searchCat').val('');
    $('form#add-cost #searchCat').trigger('change');
  });

  $('#save-cat').click(function (e) {
    e.preventDefault();

    let errors = 0;

    $('form#add-cost input[required], form#add-cost select[required]').each(function() {
      if($(this).val() == null ||  $(this).val()=='') {
        $(this).parent().addClass('has-error');
        errors ++;
      }
    });

    if(errors) {
      alert('Compilare tutti i campi in rosso');
    } else {
      var formData = new FormData($('#add-cost')[0]);
      $.ajax({
        url: pathServer + "aziende/ws/saveCost",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            $('#add-cost input[name=number]').val('');
            $('#add-cost input[name=file]').val('');
            let cats = res.data;
            if (cats) {
              loadCosts(cats);
            } else {
              $("#accordion").append("<div>Nessuna spesa presente</div>");
            }
          } else {
            alert(res.msg)
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });

    }



  });

  $('#searchCat').select2({
    language: 'it',
    placeholder: 'Cerca una categoria',
    width: '100%',
    closeOnSelect: true,
    //dropdownParent: $("#divSearchGuest"),
    ajax: {
      url: pathServer + 'aziende/ws/autocompleteCategories',
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

  if (ati) {
    if (company) {
      $('select[name="companies[0][id]"]').val(company);
      $('select[name="companies[0][id]"]').change();
    } else {
      $('select[name="companies[0][id]"]').val('all');
      $('select[name="companies[0][id]"]').change();
    }

  } else {
    $.ajax({
      url: pathServer + "aziende/ws/getCosts/" + false + "/" + company,
      type: "GET",
      dataType: "json",
    })
      .done(function (res) {
        if (res.response == "OK") {
          let cats = res.data;
          loadCosts(cats);
        } else {
          $("#accordion").append("<div>Nessuna spesa presente</div>");
        }
      })
      .fail(function (richiesta, stato, errori) {
        alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
      });
  }

  $('form#add-cost input[required], form#add-cost select[required]').each(function() {
    $(this).on('change', function() {
      $(this).parent().removeClass('has-error');
    })

  });



  $('.action-status:not(:disabled)').click(function() {
    changeStatus($(this).data('id'), $(this).data('status-id'))
  });

  attachmentsNumberForBadge('agreements', $('#idItemForAttachment').html(), 'button_attachment');
});

function loadCosts(cats) {
  $("#accordion").html("");
  let status_id = $('#status').data('status_id');
  for (let cat in cats) {
    let toAppend = '';
    if (cats[cat]['id'] == 'grandTotal') {
      toAppend =
      `
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" style="background-color: white;">
            <h4>` +
              cats[cat]["name"] +
              `<span class = "f-right">&euro;` + ` ` + cats[cat]["tot"] + `</span>` +
          `</h4>
        </div>`;
    } else {
      toAppend =
      `
        <div class="panel panel-default">
          <div class="panel-heading" role="tab">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#` +
                cats[cat]["id"] + `" aria-expanded="true">` + cats[cat]["name"] +
            `</a>
            <span class = "f-right">&euro;` + ` ` + cats[cat]["tot"] + `</span>` +
          `</h4>
        </div>
        
        <div id="` + cats[cat]["id"] + `" class="panel-collapse collapse" role="tabpanel">
          <div class="panel-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Fornitore</th>
                  <th>Num doc</th>
                  <th>Data</th>
                  <th>Descrizione</th>
                  <th>Importo</th>
                  <th>Quota parte</th>
                  <th>Note</th>
                  <th>Allegato</th>`;

      if(status_id != 2) {
        toAppend += `<th class="spesa-col"></th>`;
      }

      toAppend += `</tr>
              </thead>
              <tbody>`;

      for (let cost in cats[cat]["costs"]) {
        toAppend +=
          `
          <tr>` +
          `<td>` +
          cats[cat]["costs"][cost]["supplier"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["number"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["date"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["description"] +
          `</td>` +
          `<td>` +
          '&euro;' + ' ' + cats[cat]["costs"][cost]["amount"] +
          `</td>
            <td>` +
            '&euro;' + ' ' + cats[cat]["costs"][cost]["share"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["notes"] +
          `</td><td>`;

          if (cats[cat]["costs"][cost]["attachment"]) {
            toAppend +=         '<a href="' + pathServer + 'aziende/ws/downloadFileCosts/' + cats[cat]["costs"][cost]["id"] + '">Scarica</a>';
          }

          toAppend += `</td>`;

          if(status_id != 2) {
            toAppend += `<td class="spesa-col"> <a class="btn btn-xs btn-default delete-cost" onclick=deleteCost(`+cats[cat]["costs"][cost]["id"]+`)>
            <i data-toggle="tooltip" class="fa fa-trash" data-original-title="Elimina spesa"></i>
            </a>` +
            `</td>`;
          }

          toAppend += `</tr>`;
      }

    toAppend += `</tbody>
            </table>

        </div>
    </div>`;

    }

    $("#accordion").append(toAppend);
  }

  if(status_id != 2) {
    $('#add-cost').parent().show();
  } else {
    $('#add-cost').parent().hide();
  }

}

function deleteCost (id) {
  let check = confirm('Cancellare la spesa selezionata?');
  if (check) {
  $.ajax({
    url: pathServer + "aziende/ws/deleteCost/" + id,
    type: "GET",
    dataType: "json",
  })
  .done(function (res) {
    if (res.response == "OK") {
      let cats = res.data;
      loadCosts(cats);
      if (cats.length) {
        loadCosts(cats);
      } else {
        $("#accordion").append("<div>Nessuna spesa presente</div>");
      }
    } else {
      alert(res.msg);
    }
  })
    .fail(function (richiesta, stato, errori) {
      alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
    }) 
  }
};


function changeStatus (id, status) {
  let msg = "ATTENZIONE!\n" + "Operazione irreversibile!\n";
  switch(status) {
    // Approva
    case 2:
      msg +=  "Si desidera approvare il rendiconto?";
      break;

    // Integrazione
    case 3:
      msg += "Si desidera richiedere l'interazione del rendiconto?";
      break;

    // In approvazione
    case 4:
      msg += "Si desidera inviare il rendiconto in approvazione?";
      break;
  }

  if (confirm(msg)) {
    let notes = $('textarea[name=notes]').val();
    $.ajax({
      url: pathServer + "aziende/ws/updateStatusStatementCompany/" + id,
      type: "POST",
      data: {notes: notes, status: status},
      dataType: "json",
    })
    .done(function (res) {
      if (res.response == "OK") {

        $('#status').data('status_id', status);
        $('#text-notes').text(res.data.notes);
  
        // Approvato
         if (status == 2) {
          $('#btn-actions').hide();
          $('#status').removeClass().addClass('badge btn-success');
          $('#status').text(res.data.status.name);
          
          $('#comments').show();

          $('textarea[name=notes]').hide();

          $('#text-notes').show();

          $('#add-cost').parent().hide();
  
        // Integrazione
        } else if (status == 3) {
          $('#deny').prop('disabled', true);
  
          $('#approve').prop('disabled', true);
  
          $('#status').removeClass().addClass('badge btn-warning');
          $('#status').text(res.data.status.name);

          $('#comments').show();
          $('#text-notes').hide();
          $('textarea[name=notes]').show();
          $('textarea[name=notes]').prop('disabled', true);

          $('#add-cost').parent().show();
  
        // In approvazione
        } else if (status == 4) {
          $('#add-cost').parent().hide();

          $('#send').prop('disabled', true);
          $('#save-statment').prop('disabled', true);
          $('#delete-statement').prop('disabled', true);
          $('form#add-cost').hide();
          $('#status').removeClass().addClass('badge btn-info');
          $('#status').text(res.data.status.name);

          $('#comments').show();
          $('#text-notes').hide();
          $('textarea[name=notes]').show();
          $('textarea[name=notes]').prop('disabled', true);
        }
      } else {
        alert(res.msg);
      }
    })
      .fail(function (richiesta, stato, errori) {
        alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
      }) 
  }
};

