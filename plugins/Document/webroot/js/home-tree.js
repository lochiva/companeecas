var treeData;
$(document).ready(function(){

  $(window).scroll(function(){
      if($('#preview-container').offset() !== undefined){
           var position = $('#preview-container').offset().top-$(window).scrollTop()-50;

           if(position < 0){
             //$('#preview').animate({ marginTop: (position*-1)+'px' }, 10);
             $('#preview').css('margin-top',(position*-1)+'px');
           }else{
             $('#preview').css('margin-top','0px');
           }
        }
    });
  if(editedDoc != 0){
    setEditedDoc(editedDoc);
  }
  getAllDocuments(true);

  $('#refresh-tree').click(function(){
      getAllDocuments(false);
  });

  $('.action-document').click(function(){
      if($(this).hasClass('delete-doc')){
        if(!confirm('Si è sicuri di voler eliminare il documeto? L\'operazione non sarà reversibile.')){
          return false;
        }
      }
      window.open($(this).attr('href')+$(this).val(),"_self");
  });


});

$(document).on('click','.generate',function(e){
    var id_document = $(this).val();
    $('[name="id_document"]').val(id_document);
});

function getAllDocuments(init)
{
  showHideLoadingSpinner();
  $.ajax({
    dataType: 'json',
    url: basePath+'document/ws/getAllDocuments',
    success: function(data){
      treeData = data.data;
      if(init){
        initializeJsTree(treeData);
      }else{
        $('#tree2').jstree(true).settings.core.data  = treeData;
        $('#tree2').jstree(true).refresh();
      }
      showHideLoadingSpinner();
    },
    error: function(data){
      showHideLoadingSpinner();
    }
  });

}

function initializeJsTree(data)
{
  $('#tree2').jstree({

	        'dragAndDrop': true,
	        'autoOpen': false,
					"types" : {
			      "file" : {
			        "icon" : "fa fa-file-o grey-icon"
			      }
					},
					'core': {
		        'themes': {
		            'name': 'proton',
		            'responsive': true
		        },
						'data': data,
						"check_callback" : true
					},
					"search": {
						 "case_insensitive": true,
						 "show_only_matches" : true,
             "show_only_matches_children" : true,
					 },
           "contextmenu": {
              "items": jstreeMenu
          },
					"plugins" : [ "dnd" ,"types","search","state","contextmenu" ]

	  	}).bind("move_node.jstree", function(e, data) {
        var conf = confirm("Sei sicuro di voler spostare il documento? ");
        if(conf){
          showHideLoadingSpinner();
          var parent = (data.parent != "#" ? data.parent : 0);
          var type = "position";
          if(data.parent != data.old_parent){
             type = "parent";
          }
          $.ajax({
                type: "POST",
                dataType: 'json',
                url: basePath+'document/ws/moveDocument/'+type,
                data: {parent: parent, position:data.position, old_parent:data.old_parent,
                  old_position:data.old_position, id:data.node.id},
                success: function(data){
                  showHideLoadingSpinner();
                  if(data.response == 'KO'){
                    alert(data.msg);
                    getAllDocuments(false);
                  }
                },
                error: function(data){
                  showHideLoadingSpinner();
                  getAllDocuments(false);
                }
          });
        }else{
          getAllDocuments(false);
        }

      }).bind("select_node.jstree", function (e, data) {
      //alert("node_id: " + data.node.id);
        $.ajax({
              dataType: 'json',
              url: basePath+'document/ws/getDocument/'+data.node.id,
              success: function(data){
                $('#preview .panel-body').html(' '+data.data.text1);
                $('#preview .title').html(' '+data.data.title);
                $('#preview .cliente').html(' '+(data.data.azienda !== null ? data.data.azienda.denominazione : '&zwnj;'));
                $('#preview .project').html(' '+(data.data.ordine !== null ? data.data.ordine.name : '&zwnj;' ));
                $('#preview .revision').html(' '+data.data.revision);
                //$('#preview .panel-body').css('max-height','370px');
                $('.data-id').val(data.data.id);
                $('.data-id_document').val(data.data.id_document);
                $('#preview .tags').html('');
                if(data.data.tags !== undefined && $.isArray(data.data.tags) && data.data.tags.length > 0){
                    $.each(data.data.tags, function( index, value){
                        $('#preview .tags').append('<span class="tag-view">'+htmlEntities(value.name)+'</span>');
                    });
                }else{
                    $('#preview .tags').html('&zwnj;');
                }
                var treeHeight = $('#tree2').height();
                /*if(parseInt($('#preview .panel-body').css('max-height')) < (treeHeight-150)){
                    $('#preview .panel-body').css('max-height',(treeHeight-150)+'px');
                }*/

              },
              error: function(data){

              }
          });
      });



		var to = false;
			$('#plugins4_q').keyup(function () {
				if(to) { clearTimeout(to); }
				to = setTimeout(function () {
					var v = $('#plugins4_q').val();
					if(v.length > 2){
							$('#tree2').jstree(true).search(v);
					}else{
						$('#tree2').jstree(true).search('');
					}
				}, 100);
			});
		$('#open_all').click(function(){
			$("#tree2").jstree('open_all');
		});
		$('#close_all').click(function(){
			$("#tree2").jstree('close_all');
		});

}

function setEditedDoc(selectedId)
{
    var jsTreeTemp = localStorage.getItem('jstree');
    if(jsTreeTemp != undefined && jsTreeTemp != null){
        jsTreeTemp = JSON.parse(jsTreeTemp);
        jsTreeTemp.state.core.selected = [String(selectedId)];
        localStorage.setItem('jstree',JSON.stringify(jsTreeTemp));
    }

}

function jstreeMenu(node) {
    return {
        orderASC: {
            "label": "Ordina  crescente",
            "icon": "fa fa-sort-amount-asc",
            "action": function(obj) {
                var id = $('#tree2').jstree('get_selected');
                callOrderDocuments(id,'ASC');
            },
            "_class": "class"
        },
        orderDESC: {
            "label": "Ordina decrescente",
            "icon": "fa fa-sort-amount-desc",
            "action": function(obj) {
                var id = $('#tree2').jstree('get_selected');
                callOrderDocuments(id,'DESC');
             }
        }
    };
}

function callOrderDocuments(id,order)
{
  $.ajax({
       type: "GET",
       dataType: 'json',
       url: basePath+'document/ws/orderDocuments/'+id+'/'+order,
       success: function(data){
         if(data.response == 'OK'){
           getAllDocuments(false);
         }else{
           alert(data.msg);
         }
       },
       error: function(data){
         alert('Errore, il server non risponde.');
       }
  });

}
