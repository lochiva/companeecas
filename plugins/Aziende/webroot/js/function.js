function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

function ControllaPIVA(pi,type){
    if(type === undefined){
      type = 'La partita IVA';
    }
    if( pi == '' ){
    	return 'Inserire una partita IVA';
    }

    if( pi.length != 11 ){
    	return type+" dovrebbe essere lunga\n" + "esattamente 11 caratteri.\n";
    }

    validi = "0123456789";
    for( i = 0; i < 11; i++ ){
        if( validi.indexOf( pi.charAt(i) ) == -1 ){
        	return type+" contiene un carattere non valido `" + pi.charAt(i) + "'.\nI caratteri validi sono le cifre.\n";
        }

    }

    s = 0;
    for( i = 0; i <= 9; i += 2 )
        s += pi.charCodeAt(i) - '0'.charCodeAt(0);
    for( i = 1; i <= 9; i += 2 ){
        c = 2*( pi.charCodeAt(i) - '0'.charCodeAt(0) );
        if( c > 9 )  c = c - 9;
        s += c;
    }
    if( ( 10 - s%10 )%10 != pi.charCodeAt(10) - '0'.charCodeAt(0) ){
    	return "il codice di controllo non corrisponde.\n";
    }

    return 'OK';
}

function ControllaCF(cf){
    var validi, i, s, set1, set2, setpari, setdisp;

    if( cf == '' ){
    	return 'Inserire il codice fiscale';
    }

    cf = cf.toUpperCase();

    if( cf.length != 16 ){
    	return "Il codice fiscale dovrebbe essere lungo\n" +"esattamente 16 caratteri.\n";
    }

    validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for( i = 0; i < 16; i++ ){
        if( validi.indexOf( cf.charAt(i) ) == -1 ){
        	 return "Il codice fiscale contiene un carattere non valido `" + cf.charAt(i) + "'.\nI caratteri validi sono le lettere e le cifre.\n";
        }

    }

    set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
    s = 0;
    for( i = 1; i <= 13; i += 2 )
        s += setpari.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
    for( i = 0; i <= 14; i += 2 )
        s += setdisp.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
    if( s%26 != cf.charCodeAt(15)-'A'.charCodeAt(0) ){
    	return "il codice di controllo non corrisponde.\n";
    }

    return "OK";
}

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