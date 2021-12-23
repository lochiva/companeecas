$(document).on('click','.delete-contatto',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare il contatto?')){
		deleteContatto($(this).attr('data-id'));
	}

});
$(document).on('click','.delete-sede',function(e){

	e.preventDefault();

	if(confirm('Si è sicuri di voler eliminare la sede?')){
		deleteSede($(this).attr('data-id'));
	}

});

function deleteContatto(id){

	$.ajax({
	    url : pathServer + "aziende/Ws/deleteContatto/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	location.reload();

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function deleteSede(id){

	$.ajax({
	    url : pathServer + "aziende/Ws/deleteSede/" + id,
	    type: "GET",
	    dataType: "json",
	    success : function (data,stato) {

	        if(data.response == "OK"){

	        	location.reload();

	        }else{
	        	alert(data.msg);
	        }

	    },
	    error : function (richiesta,stato,errori) {
	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	    }
	});

}

function afterSaveModalAziende(){
		$('.close').click();
		location.reload();
}
