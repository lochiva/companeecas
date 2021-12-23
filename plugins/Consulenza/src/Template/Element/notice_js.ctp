<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>
<script>
 var pathServer = '<?php echo Router::url('/')?>';

 function noticeRead(nid){

  $.ajax({
    url: pathServer + "consulenza/ws/noticeRead", 
    type: 'post',   
    data: {nid:nid},
    async: true,
    dataType: 'json',
    success: function(result){
          
      if(result.response == "OK"){
          getTotMyNotice();
          getMyNotice();
      }else{
        alert(result.msg);
      }

      }
  });

}

function getTotMyNotice(){

  $.ajax({
    url: pathServer + "consulenza/ws/getCountMyNewNotice", 
    type: 'post',   
    async: true,
    dataType: 'json',
    success: function(result){
          
      if(result.response == "OK"){
        if(result.data>0){
            if(result.data>1){
              notifica_label = ' notifiche';
            } else {
              notifica_label = ' notifica';
            }
              $('#notify_count').html(result.data);
              $('#notify_count_text').html('Hai '+result.data+ notifica_label+' da leggere');
         }else {
              $('#notify_count').hide();
              $('#notify_count_text').html('Nessuna notifica da leggere');
         }
      }else{
        alert(result.msg);
      }

      }
  });

}

function getMyNotice(){

  $.ajax({
    url: pathServer + "consulenza/ws/getMyNewNotice", 
    type: 'post',   
    async: true,
    dataType: 'json',
    success: function(result){
          
      if(result.response == "OK"){
        $('#notify_container').html('');
          $.each(result.data, function() {
                $('#notify_container').append('<li id="notice-'+this.id+'" attr="'+this.id+'"><p class="pull-right txt-gray"><small><b>'+this.dateWrited+'</b></small></p> <p><i class="fa fa-envelope text-aqua"></i> '+this.message+'</p><small class="txt-gray"><b>'+this.users_source.nome+' '+this.users_source.cognome+'</b></small> <a class="pull-right" href="#" onclick="noticeRead('+this.id+')"><i class="fa fa-times-circle"></i></a></li>');
          });
      }else{
        alert(result.msg);
      }

      }
  });

}



$(document).ready(function(){

  getTotMyNotice();

  getMyNotice();

  $( ".dropdown-menu" ).click(function(event) {
      // stop bootstrap.js to hide the parents
      event.stopPropagation();
      // hide the open children
      $( this ).find(".dropdown-submenu").removeClass('open');
      // add 'open' class to all parents with class 'dropdown-submenu'
      $( this ).parents(".dropdown-submenu").addClass('open');
      // this is also open (or was)
      $( this ).toggleClass('open');
  });  
  
});
</script>