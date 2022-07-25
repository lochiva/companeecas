<div class="modal fade" id="modalStatement" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo rendiconto</h4>
            </div>

            <div class="modal-body">

                <form class="form-horizontal" id="form-statement">

                    <input type="hidden" name="agreement_id">

                    <div class="form-group">
                        <label class="required control-label col-sm-2">CIG</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" required id="cig">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required control-label col-sm-2">Anno</label>
                        <div class="col-sm-10">
                            <input type="number" name="year" required class="form-control">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="required control-label col-sm-2">Periodo</label>
                        <div class="col-sm-10">
                            <select name="period_id" class="form-control" required>
                                <option selected disabled>-- Selezionare un periodo --</option>
                                <?php foreach ($periods as $key => $value) : ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="required control-label col-sm-2">Etichetta</label>
                        <div class="col-sm-10">
                            <input type="text" name="period_label" required readonly class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required control-label col-sm-2">Inizio</label>
                        <div class="col-sm-10">
                            <input type="date" name="period_start_date" required readonly class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required control-label col-sm-2">Fine</label>
                        <div class="col-sm-10">
                            <input type="date" name="period_end_date" required readonly class="form-control">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="save" disabled>Salva</button>
            </div>


        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('input[required], select[required]').each(function() {
            $(this).on('change', function() {
                $(this).parent().parent().removeClass('has-error');
            });
        });

        $('input[required], select[required]').each(function() {
            $(this).on('blur', function() {
                $(this).parent().parent().removeClass('has-error');
            });
        });

        $('select[name=period_id]').on('change', function() {
            let opt = $('select[name=period_id] option:selected').text();

            if (opt == 'Personalizzato') {
                $('input[name=period_label]').attr('readonly', false);
                $('input[name=period_start_date]').attr('readonly', false);
                $('input[name=period_end_date]').attr('readonly', false);

                $('input[name=period_label]').val('');
                $('input[name=period_start_date]').val('');
                $('input[name=period_end_date]').val('');

            } else {
                $('input[name=period_label]').attr('readonly', true);
                $('input[name=period_start_date]').attr('readonly', true);
                $('input[name=period_end_date]').attr('readonly', true);

                $.ajax({
                    url: pathServer + 'aziende/ws/getPeriod/' + $('select[name=period_id]').val(),
                    type: "GET",
                    dataType: 'json'
                }).done(function(res) {
                    if (res.response == 'OK') {
                        $('input[name=period_label]').val(res.data.label);
                        $('input[name=period_start_date]').val(res.data.start_date);
                        $('input[name=period_end_date]').val(res.data.end_date);

                    } else {
                        alert(res.msg);
                    }
                }).fail(function(richiesta, stato, errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                });
            }
        });

        $('#cig').on('blur', function() {
            $.ajax({
                url: pathServer + 'aziende/ws/checkCig/' + $('#cig').val(),
                type: "GET",
                dataType: 'json'
            }).done(function(res) {
                if (res.response == 'OK') {
                    $('input[name=agreement_id]').val(res.data.id);
                    $('#save').prop('disabled', false);
                } else {
                    $('#save').prop('disabled', true);
                    $('#cig').parent().parent().addClass('has-error');
                    alert('Il CIG inserito non è valido');

                }
            }).fail(function(richiesta, stato, errori) {
                alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                $('#save').prop('disabled', true);
            });

        });

        $("#save").click(function(e) {
            e.preventDefault();
            $('input[required], select[required]').each(function() {
                $(this).parent().parent().removeClass('has-error');

            });

            let valid = true;
            $('input[required], select[required]').each(function() {
                if ($(this).val() == false) {
                    $(this).parent().parent().addClass('has-error');
                    valid = false;
                }
            });

            if (valid) {
                var formData = new FormData($('#form-statement')[0]);
                $.ajax({
                    url: pathServer + 'aziende/ws/saveStatement',
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    dataType: 'json'
                }).done(function(res) {
                    if (res.response == 'OK') {
                        $('input[name=agreement_id]').val(res.data.id);
                        $('#save').prop('disabled', false);
                        window.location.assign(pathServer + 'aziende/statements/view/' + res.data.id);
                    } else {
                        alert(res.msg);
                    }
                }).fail(function(richiesta, stato, errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                });
            }
        });

    });

    $(document).on('hide.bs.modal', '#modalStatement', function(e) {
        $('#form-statement')[0].reset();
        $('input[name=period_label]').attr('readonly', true);
        $('input[name=period_start_date]').attr('readonly', true);
        $('input[name=period_end_date]').attr('readonly', true);
        $('#save').prop('disabled', true);

    });
</script>