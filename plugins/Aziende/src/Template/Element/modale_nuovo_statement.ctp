<?php

/**
 * Aziende is a plugin for manage attachment
 *
 * Companee :    Modale Nuovo Statement  (https://www.companee.it)
 * Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
 * 
 * Licensed under The GPL  License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
 * @link          https://www.ires.piemonte.it/ 
 * @since         1.2.0
 * @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
 */
?>

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
                            <input type="text" class="form-control" required id="cig" maxlength=10>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required control-label col-sm-2">Periodo</label>
                        <div class="col-sm-10">
                            <select name="period_id" class="form-control" required disabled>
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

        $("#save").click(function(e) {
            e.preventDefault();
            $('input[required], select[required]').each(function() {
                $(this).parent().parent().removeClass('has-error');

            });

            let valid = true;
            let message = '';
            $('input[required], select[required]').each(function() {
                if ($(this).val() == false || $(this).val() == null || $(this).val().length == 0) {
                    $(this).parent().parent().addClass('has-error');
                    message = 'Correggere o compilare i campi in ROSSO.';
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
                        window.location.assign(pathServer + 'aziende/statements/view/' + res.data.id);
                    } else {
                        alert(res.msg);
                    }
                }).fail(function(richiesta, stato, errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                });
            } else {
                alert(message);
            }
        });

        $('#cig').on('input', (e) => {

            if ($('#cig').val()?.length === 10) {
                $.ajax({
                    url: pathServer + 'aziende/ws/checkCig/' + $('#cig').val(),
                    type: "GET",
                    dataType: 'json',
                    async: false
                }).done(function(res) {
                    if (res.response == 'OK') {
                        $('input[name=agreement_id]').val(res.data.id);
                        $('select[name=period_id]').append($('<option>').val(null).text('Selezionare').attr('selected', true).attr('disabled', true));
                        res.data.periods.forEach(element => {
                            $('select[name=period_id]').append($('<option>').val(element.id).text(element.label));
                        });
                        $('select[name=period_id]').attr('disabled', false);
                        $('#save').attr('disabled', false);

                    } else {
                        alert(res.msg);
                        resetForm();
                    }
                }).fail(function(richiesta, stato, errori) {
                    alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
                    resetForm();
                });
            } else {
                resetForm();
            }
        });

    });

    $(document).on('hide.bs.modal', '#modalStatement', function(e) {
        resetForm();
        $('input[name=period_label]').attr('readonly', true);
        $('input[name=period_start_date]').attr('readonly', true);
        $('input[name=period_end_date]').attr('readonly', true);
        $('input[required], select[required]').each(function() {
            $(this).parent().parent().removeClass('has-error');
        });
    });

    function resetForm() {
        $('input[name=agreement_id]').val(null);
        $('#cig').val(null);
        $('select[name=period_id]').empty();
        $('input[name=period_label]').val(null);
        $('input[name=period_start_date]').val(null);
        $('input[name=period_end_date]').val(null);
        $('#save').attr('disabled', true);
    }
</script>