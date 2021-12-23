function sendReport(id,fn)
{
	if(typeof id != 'undefined')
	{
		$.ajax({
          url : pathServer + "consulenza/ws/invia_report/" + id,
          type  : "post",
          dataType : "json",
          success : function (data,stato) {
              
              if(data.response == "OK"){

              	  if(typeof fn == 'function')
              	  	fn(id,data);

              }else{
                  alert(data.msg);
              }
              
          },
          error : function (richiesta,stato,errori) {
              alert("E' avvenuto un errore. Stato della chiamata: "+stato);
          }
      });
	}
}

function sendReportIrap(id,fn)
{
	//Da usare solo per UNICO SC o per quando il cliente non ha una causale irap

	$.ajax({
		url : pathServer + "consulenza/ws/invia_report_irap/" + id,
		type  : "post",
		dataType : "json",
		success : function (data,stato) {
		  
		  if(data.response == "OK"){

		      if(typeof fn == 'function')
              	  	fn(id,data);
		  }else{
		      alert(data.msg);
		  }
		  
		},
		error : function (richiesta,stato,errori) {
		  alert("E' avvenuto un errore. Stato della chiamata: "+stato);
		}
	});
}

