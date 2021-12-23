
$(document).ready(function(){


	$('#idAzienda').select2({
		 language: 'it',
     width: '100%',
     placeholder: 'Selezione un azienda',
     closeOnSelect: true,
     minimumInputLength: 3,
     ajax: {
       url: basePath+'aziende/ws/autocompleteAziende',
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

	 $('#idOrder').select2({
		  language: 'it',
			width: '100%',
			placeholder: 'Seleziona un ordine',
		});

		$('#idAzienda').change(function(){
			//alert($(this).val());
			$('#idOrder').html('');
			if($(this).val() != "" ){
	      loadOrdersAzienda($(this).val());
			}

		});

		$('#idTags').select2({
			 language: 'it',
       width: '100%',
       placeholder: 'Aggiungi tag',
       tags:true,
       tokenSeparators: [',', ' '],
       minimumInputLength: 2,
       ajax: {
         url: basePath+'ws/autocompleteTags',
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

	//###############################################################################################################################################
	//Gestione aggiungi cliente

	$('#add-client').click(function(){

		var client = prompt("Inserire il nome del nuovo Cliente", "");

		if(client != ""){

			$.ajax({
			    url : basePath + "document/ws/addClient/" + client,
			    dataType : "json",
			    success : function (data,stato) {

			        if(data.response == "OK"){

			        	$('select#client').html('');
			        	$('select#project').html('');
			        	for (var key in data.data.clients) {
			        		//alert(data.data.clients[key].name);
			        		var selected = "";
			        		if(data.data.clients[key].name == client){
			        			selected = 'selected';
			        		}

			        		$('select#client').append('<option value="' + data.data.clients[key].id + '" ' + selected + '>' + data.data.clients[key].name + '</option>');
			        	}

			        }else{
			        	alert(data.msg);
			        }

			    },
			    error : function (richiesta,stato,errori) {
			        alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
			    }
			});

		}else{
			alert('Nome non valido, si prega di riprovare.');
		}

	});

	//###############################################################################################################################################
	//Gestione aggiungi progetto

	$('#add-project').click(function(){

		var project = prompt("Inserire il nome del nuovo Progetto", "");
		var idClient = $('#id_client').val();

		if(project != ""){

			$.ajax({
			    url : basePath + "document/ws/addProject/" + project + '/' + idClient,
			    dataType : "json",
			    success : function (data,stato) {

			        if(data.response == "OK"){

			        	$('select#id_project').html('');
			        	for (var key in data.data.projects) {
			        		//alert(data.data.clients[key].name);
			        		var selected = "";
			        		if(data.data.projects[key].name == project){
			        			selected = 'selected';
			        		}

			        		$('select#id_project').append('<option value="' + data.data.projects[key].id + '" ' + selected + '>' + data.data.projects[key].name + '</option>');
			        	}

			        }else{
			        	alert(data.msg);
			        }

			    },
			    error : function (richiesta,stato,errori) {
			        alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
			    }
			});

		}else{
			alert('Nome non valido, si prega di riprovare.');
		}

	});

	//###############################################################################################################################################
	//Gestione cambio select cliente

	$('#id_client').change(function(){

		//alert($(this).val());

		loadProject($(this).val());

	});

	//###############################################################################################################################################
	//Gestione dell'albero dei parent
	$('#tree1').tree({
        data: data,
        dragAndDrop: true,
        autoOpen: false
    });


	for(item in parent){
		//alert(parent[item]);
		var node = $('#tree1').tree('getNodeById', parent[item]);
		$('#tree1').tree('openNode', node);
	}

	var node = $('#tree1').tree('getNodeById', idNode);
	$('#tree1').tree('scrollToNode', node);
	$('#tree1').tree('addToSelection', node);

	$('#tree1').bind(
	    'tree.move',
	    function(event) {

			event.preventDefault();

	    	if (confirm('Si è sicuri di voler spostare il documento?')) {
	            event.move_info.do_move();
		        console.log('moved_node', event.move_info.moved_node);
		        console.log('target_node', event.move_info.target_node);
		        console.log('position', event.move_info.position);
		        console.log('previous_parent', event.move_info.previous_parent);

		        //alert('Old Parent: ' + event.move_info.previous_parent.id_document + ' New Parent: ' +  event.move_info.target_node.id_document);

		        var newParent = event.move_info.target_node.id_document;
		        var myId = event.move_info.moved_node.id;

		        $.ajax({
			    url : basePath + "document/ws/mouveParentDocument/" + myId + '/' + newParent,
			    dataType : "json",
			    success : function (data,stato) {

			        if(data.response == "OK"){

			        }else{
			        	alert(data.msg);
			        }

			    },
			    error : function (richiesta,stato,errori) {
			        alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
			    }
			});

		 	}
	    }
	);


});

//#######################################################################################################################################################

function in_array(needle, haystack) {

	for (key in haystack) {
	  if (haystack[key] === needle) {
	    return true;
	  }
	}

  	return false;

}

function loadProject(idClient){

	if(idClient != ""){
		$.ajax({
		    url : basePath + "document/ws/getProjectByClient/" + idClient,
		    dataType : "json",
		    success : function (data,stato) {

		        if(data.response == "OK"){

		        	if($('select#id_project').hasClass('home')){
		        		$('select#id_project').html('<option value="" >Tutti</option>');
		        	}else{
		        		$('select#id_project').html('');
		        	}

		        	for (var key in data.data.projects) {
		        		//alert(data.data.clients[key].name);

		        		$('select#id_project').append('<option value="' + data.data.projects[key].id + '" >' + data.data.projects[key].name + '</option>');
		        	}

		        }else{
		        	alert(data.msg);
		        }

		    },
		    error : function (richiesta,stato,errori) {
		        alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
		    }
		});
	}else{

		$('select#id_project').html('<option value="" >Tutti</option>');

	}

}

function loadOrdersAzienda(id, selectedId)
{
  if(id != null && id != undefined){
    	$.ajax({
    	    url : basePath + "aziende/Ws/getOrdersAzienda/" + id  ,
    	    type: "GET",
    	    async: false,
    	    dataType: "json",
    	    data:{},
    	    success : function (data,stato) {

    	        if(data.response == "OK"){
    						//console.log(data);
    	        	var option = '<option style="color: graytext;" value="0">Nessuno</option>';

    	        	for (var item in data.data) {

    								option += '<option id="order-num-'+data.data[item].id+'" idcontatto="'+data.data[item].id_contatto+'" value="' + data.data[item].id+ '">' + data.data[item].name + '</option>';

    						}

    	        	$('#idOrder').html(option);

    						if(selectedId !== undefined){
    							$('#idOrder').val(selectedId);

    						}
								$('#idOrder').change();

    	        }

    	    },
    	    error : function (richiesta,stato,errori) {
    	        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    	    }
    	});
    }

}
