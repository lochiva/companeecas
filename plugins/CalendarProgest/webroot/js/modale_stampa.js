$(document).ready(function(){

    $("#formStampOperatori, #formStampPersone, #formStampMoteOre").submit(function(event){

        var view = $('#calendar').fullCalendar( 'getView' );
        if(view.type !== 'agendaWeek'){
            alert('Devi essere in visualizzazione della settimana per stampare.');
            return false;
        }
        $(".form-stampa [name='start']").val(view.start.format('YYYY-MM-DD'));
        $(".form-stampa [name='end']").val(view.end.format('YYYY-MM-DD'));
        return true;
    });


    $('#formStampOperatori [name="select_all"]').change(function(){
        if($(this).is(":checked")){
            $('#formStampOperatori input').prop('checked',true);
        }else{
            $('#formStampOperatori input').prop('checked',false);
        }
    });

    $('#formStampPersone [name="select_all"]').change(function(){
        if($(this).is(":checked")){
            $('#formStampPersone input').prop('checked',true);
        }else{
            $('#formStampPersone input').prop('checked',false);
        }
    });

    $('#formStampMoteOre .stamp').click(function(){
      /*console.log($("#formStampMoteOre").serialize());
      var formData= new FormData(document.getElementById('formStampMoteOre') );
      for(var pair of formData.entries()) {
         console.log(pair[0]+ ', '+ pair[1]);
      }*/
    });

});
$(document).on('hidden.bs.modal','#myModalStampOperatori', function (e) {
	  $('#formStampOperatori')[0].reset();
});
$(document).on('shown.bs.modal','#myModalStampOperatori', function (e) {
	  $('#formStampOperatori input[value="'+$('#user-caledar-view').val()+'"]').prop('checked',true);
});

$(document).on('hidden.bs.modal','#myModalStampPersone', function (e) {
	  $('#formStampPersone')[0].reset();
});
$(document).on('shown.bs.modal','#myModalStampPersone', function (e) {
	  $('#formStampPersone input[value="'+$('#person-caledar-view').val()+'"]').prop('checked',true);
});

$(document).on('hidden.bs.modal','#myModalStampMonteOre', function (e) {
	  $('#formStampMoteOre')[0].reset();
});
$(document).on('shown.bs.modal','#myModalStampMonteOre', function (e) {
	  $('#formStampMoteOre input[value="'+$('#person-caledar-view').val()+'"]').prop('checked',true);
});
