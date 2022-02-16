<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

?>
<script>
var pathServer = '<?=Router::url('/')?>';
$(document).ready(function(){

    $.ajax({
        url : pathServer + "aziende/ws/getGuestsNotificationsCount/",
        type: "GET",
        dataType: "json"
	}).done(function(res) {
		if(res.response == 'OK'){
			var count = res.data;
			if(count > 0){
				$('.guests_notify_count_label').html(count);
			}
		}
	}).fail(function(richiesta,stato,errori){
		alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	});

});
</script>


<li class="dropdown notifications-menu">
	<a href="<?= Router::url('/aziende/guests/notifications');?>">
	<i class="fa fa-bell-o"></i>
	<span class="label label-info guests_notify_count_label"></span>
	</a>
</li>