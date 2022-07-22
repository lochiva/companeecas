$(document).ready(function () {
  $('select[name="statement[company][id]"]').on("change", function () {
    $("#accordion").text("");
    if ($(this).val() == "all") {
      $("#company_specific").addClass("hidden");
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
              let toAppend =
                `
              <div class="panel panel-default">
                <div class="panel-heading" role="tab">
                  <h4 class="panel-title">
                      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#` +
                cats[cat]["name"] +
                `" aria-expanded="true">
                      ` +
                cats[cat]["name"] +
                ` ` +
                cats[cat]["tot"] +
                `
                      </a>
                  </h4>
                  </div>
                  
          <div id="` +
                cats[cat]["name"] +
                `" class="panel-collapse collapse in" role="tabpanel">
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
      let id = $(this).val();
      $("#company_specific").removeClass("hidden");

      // Dati
      $.ajax({
        url: pathServer + "aziende/ws/getStatementCompany/" + id,
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            $('input[name="company[billing_reference]"]').val(
              res.data.billing_reference
            );
            $('input[name="company[billing_date]"]').val(res.data.billing_date);
            $('input[name="company[billing_net_amount]"]').val(
              res.data.billing_net_amount
            );
            $('input[name="company[billing_vat_amount]"]').val(
              res.data.billing_vat_amount
            );

            if (res.data.uploaded_path.length) {
              $("#upl_file").removeClass("hidden");
              $("#uploaded_path").prop(
                "href",
                pathServer + "aziende/ws/downloadFile/" + res.data.id
              );
            } else {
              $("#upl_file").addClass("hidden");
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
            for (let cat in cats) {
              let toAppend =
                `
              <div class="panel panel-default">
                <div class="panel-heading" role="tab">
                  <h4 class="panel-title">
                      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#` +
                cats[cat]["name"] +
                `" aria-expanded="true">
                          ` +
                cats[cat]["name"] +
                ` ` +
                cats[cat]["tot"] +
                `
                      </a>
                  </h4>
                  </div>
                  
                  <div id="` +
                cats[cat]["name"] +
                `" class="panel-collapse collapse in" role="tabpanel">
                  <div class="panel-body">
                      <table class="table">
                          <thead>
                            <tr>
                              <th>Amount</th>
                              <th>Share</th>
                              <th>Attachment</th>
                            </tr>
                          </thead>

                          <tbody>`;

              for (let cost in cats[cat]["costs"]) {
                toAppend +=
                  `
                  <tr>
                    <td>` +
                  cats[cat]["costs"][cost]["amount"] +
                  `</td>
                    <td>` +
                  cats[cat]["costs"][cost]["share"] +
                  `</td>
                    <td>` +
                  cats[cat]["costs"][cost]["attachment"] +
                  `</td>
                  </tr>`;
              }

              toAppend += `</tbody>
                      </table>

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
    }
  });

  $("input[required], select[required]").each(function () {
    $(this).on("change", function () {
      $(this).parent().parent().removeClass("has-error");
    });
  });

  $("input[name=file]").on("change", function () {
    if ($("input[name=file]").prop("files").length) {
      $('input[name="company[uploaded_path]"]').val(
        $("input[name=file]").prop("files")[0].name
      );
    } else {
      $('input[name="company[uploaded_path]"]').val();
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

  $("#form-statement").submit(function (e) {
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
  });
});
