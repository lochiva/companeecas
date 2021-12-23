$.fn.datepicker.dates['it'] = {
    days: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'],
    today: "Oggi",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};
$(document).ready(function(){
  $('input').change(function(){
    $(this).parentsUntil('div.form-group').parent().removeClass('has-error');
  });
   $('select').change(function(){
    $(this).parentsUntil('div.form-group').parent().removeClass('has-error');
  });
  // REPORT COMPLEANNI
  $('.xls-reportBirthdays').click(function(){
      if(formValidation('reportBirthdays')){
        var month = $('#reportBirthdays [name="month[month]"]').val();
        var gruppo = $('#reportBirthdays [name="gruppi"]').val();
        window.open(pathServer+'progest/report/reportBirthdays/'+month+'/'+gruppo+'/xls', '_self');
      }
  });
  $('.stamp-reportBirthdays').click(function(){
      if(formValidation('reportBirthdays')){
        var month = $('#reportBirthdays [name="month[month]"]').val();
        var gruppo = $('#reportBirthdays [name="gruppi"]').val();
        window.open(pathServer+'progest/report/reportBirthdays/'+month+'/'+gruppo, '_blank');
      }
  });
  // REPORT INDIRIZZARIO
  $('.xls-reportIndirizzario').click(function(){
      if(formValidation('reportIndirizzario')){
        var gruppo = $('#reportIndirizzario [name="gruppi"]').val();
        var servizio = $('#reportIndirizzario [name="servizi"]').val();
        window.open(pathServer+'progest/report/reportIndirizzario/'+gruppo+'/'+servizio+'/xls', '_self');
      }
  });
  $('.stamp-reportIndirizzario').click(function(){
      if(formValidation('reportIndirizzario')){
        var gruppo = $('#reportIndirizzario [name="gruppi"]').val();
        var servizio = $('#reportIndirizzario [name="servizi"]').val();
        window.open(pathServer+'progest/report/reportIndirizzario/'+gruppo+'/'+servizio, '_blank');
      }
  });
});
