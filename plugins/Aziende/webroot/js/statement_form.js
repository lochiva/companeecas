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
      $('#deny').parent().parent().hide();
      $('#approve').parent().hide();
      $('#status').parent().hide();
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
      $('#add-cost').show();
      $('#add-cost input[type=hidden][name=statement_company]').val(id);
      $('#save-cat').prop('disabled', false);
      $("#company_specific").removeClass("hidden");

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
            $('textarea[name=notes]').val(res.data.notes);

            // In corso
            if (res.data.status_id == 1) {
              $('#deny').parent().parent().hide();
              $('#approve').parent().hide();

              if (role === 'ente') {
                $('#send').data('id', res.data.id);
                $('#send').parent().parent().show();
                $('#send').prop('disabled', false);
                $('textarea[name=notes]').val(res.data.notes);
                $('textarea[name=notes]').prop('disabled', true);
              } else {
                $('#send').parent().parent().hide();
              }

              $('#status').removeClass().addClass('badge btn-default');
              $('#save-statment').prop('disabled', false);
              $('#delete-statement').prop('disabled', false);
              $('form#add-cost').show();
              
            // Approvato
            }  else if (res.data.status_id == 2) {
              $('#deny').parent().parent().hide();
              $('#approve').parent().hide();
              $('#send').parent().parent().hide();
              $('#save-statment').prop('disabled', true);
              $('#delete-statement').prop('disabled', false);
              $('form#add-cost').hide();
              $('#status').removeClass().addClass('badge btn-success');

            // Integrazione
            } else if (res.data.status_id == 3) {
              if(role === 'ente') {
                $('#deny').parent().parent().hide();
                $('#approve').parent().hide();
                $('#send').data('id', res.data.id);
                $('#send').parent().parent().show();
                $('#send').prop('disabled', false);
                $('textarea[name=notes]').prop('disabled', false);

              } else {
                $('#deny').parent().parent().show();
                $('#deny').prop('disabled', true);
                $('#approve').parent().show();
                $('#approve').prop('disabled', true);
                $('#send').parent().parent().hide();
                $('textarea[name=notes]').prop('disabled', true);

              }
              $('#save-statment').prop('disabled', false);
              $('#delete-statement').prop('disabled', false);
              $('form#add-cost').show();
              $('#status').removeClass().addClass('badge btn-warning');

            // In approvazione
            } else if (res.data.status_id == 4) {
              if (role === 'ente') {
                $('#send').parent().parent().show();
                $('#send').prop('disabled', true);
                $('textarea[name=notes]').prop('disabled', true);
              } else {
                $('#send').parent().parent().hide();
                $('#deny').data('id', res.data.id);
                $('#deny').parent().parent().show();
                $('#deny').prop('disabled', false);
                $('#approve').data('id', res.data.id);
                $('#approve').parent().show();
                $('#approve').prop('disabled', false);
              }
              $('#save-statment').prop('disabled', true);
              $('#delete-statement').prop('disabled', false);
              $('form#add-cost').hide();
              $('#status').removeClass().addClass('badge btn-info');
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
                  <th>Allegato</th>
                  <th></th>
                </tr>
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

          toAppend += `</td> <td> <a class="btn btn-xs btn-default delete-cost" onclick=deleteCost(`+cats[cat]["costs"][cost]["id"]+`)>
          <i data-toggle="tooltip" class="fa fa-trash" data-original-title="Elimina spesa"></i>
          </a>` +
          `</td>
          </tr>`;
      }

    toAppend += `</tbody>
            </table>

        </div>
    </div>`;

    }

    $("#accordion").append(toAppend);
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
  let notes = $('textarea[name=notes]').val();
  $.ajax({
    url: pathServer + "aziende/ws/updateStatusStatementCompany/" + id,
    type: "POST",
    data: {notes: notes, status: status},
    dataType: "json",
  })
  .done(function (res) {
    if (res.response == "OK") {

       if (status == 2) {
        $('#deny').parent().parent().hide();
        $('#approve').parent().hide();
        $('#send').parent().parent().hide();
        $('#save-statment').prop('disabled', true);
        $('#delete-statement').prop('disabled', false);
        $('form#add-cost').hide();
        $('#status').removeClass().addClass('badge btn-success');
        $('#status').text(res.data.status.name);

      // Integrazione
      } else if (status == 3) {
        $('#deny').data('id', res.data.id);
        $('#deny').parent().parent().show();
        $('#deny').prop('disabled', true);
        $('#approve').data('id', res.data.id);
        $('#approve').parent().show();
        $('#approve').prop('disabled', true);
        $('#send').data('id', res.data.id);
        $('#send').parent().parent().show();
        $('#send').prop('disabled', false);
        $('#save-statment').prop('disabled', false);
        $('#delete-statement').prop('disabled', false);
        $('form#add-cost').show();
        $('#status').removeClass().addClass('badge btn-warning');
        $('#status').text(res.data.status.name);
        $('textarea[name=notes]').prop('disabled', true);

      // In approvazione
      } else if (status == 4) {
        $('#deny').data('id', res.data.id);
        $('#deny').parent().parent().show();
        $('#deny').prop('disabled', false);
        $('#approve').data('id', res.data.id);
        $('#approve').parent().show();
        $('#approve').prop('disabled', false);
        $('#send').data('id', res.data.id);
        $('#send').parent().parent().show();
        $('#send').prop('disabled', true);
        $('#save-statment').prop('disabled', true);
        $('#delete-statement').prop('disabled', false);
        $('form#add-cost').hide();
        $('#status').removeClass().addClass('badge btn-info');
        $('#status').text(res.data.status.name);
        $('textarea[name=notes]').prop('disabled', true);
      }
    } else {
      alert(res.msg);
    }
  })
    .fail(function (richiesta, stato, errori) {
      alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
    }) 

};

