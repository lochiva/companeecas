<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    guests notify (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
use Cake\Core\Configure;

?>
<script>
var pathServer = '<?=Router::url('/')?>';
$(document).ready(function(){

    $.ajax({
        url : pathServer + "aziende/ws/getGuestsNotificationsCount/1",
        type: "GET",
        dataType: "json"
	}).done(function(res) {
		if(res.response == 'OK'){
			var count = res.data;
			if(count > 0){
				$('.guests_notify_count_label').html(count);
			} else {
				$('.guests_notify_count_label').html('');
			}
		}
	}).fail(function(richiesta,stato,errori){
		alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
	});

});
</script>


<li class="dropdown notifications-menu">
	<a href="<?= Router::url('/aziende/guests/notifications/1');?>">
	<i class="fa fa-bell-o"></i>
	<span class="label label-info guests_notify_count_label"></span>
	</a>
</li>