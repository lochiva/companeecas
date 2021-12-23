<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

?>
<script>
var pathServer = '<?=Router::url('/')?>';
$(document).ready(function(){

    $.ajax({
        url : pathServer + "Ws/userNewNotice/",
        type: "GET",
        dataType: "json",
        success : function (data,stato) {
          if(data.response == 'OK'){
            $.each( data.data, function( index, element ) {

              var notice = '<li class="new_notice"  id="notice-'+element.id+'" value="'+element.id+
              '"><p class="pull-right txt-gray"><small><b>'+element.created+
              '</b></small></p> <p><i class="fa fa-envelope text-aqua"></i> '+element.message+
              '</p><small class="txt-gray"><b>'+element.creator+
              '</b></small> <a class="pull-right text-yellow" href="#" ><i class="fa fa-exclamation"></i></a></li>';
              $('#notify_container').append(notice);

            });
            var count = data.data.length;
            $('.notify_count').html(count);
            if(count > 0){
              $('.notify_count_label').html(count);
            }
          }
          $('.new_notice').hover(function(){
            var notice = $(this);
            if(notice.attr('readed') != 1){
                notice.attr('readed','1');
                $.ajax({
                    url : pathServer + "Ws/readNotice/"+notice.val(),
                    type: "GET",
                    dataType: "json",
                    success : function (data,stato) {
                      if(data.response == 'OK'){
                        notice.children('a').attr('class','pull-right text-green');
                        notice.children('a').html('<i class="fa fa-eye"></i>');
                        count = ($('.notify_count').html()-1);
                        $('.notify_count').html(count);
                        if(count > 0){
                          $('.notify_count_label').html(count);
                        }else{
                          $('.notify_count_label').html('');
                        }

                      }
                    },
                    error: function(data){
                    }
                });
              }

          });
        },
        error: function(data){

        }
    });



});

</script>
