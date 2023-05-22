<?php
/** 
* Companee :    error (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
?>
<script>
$(document).ready(function(){
  /*setTimeout(function(){
    $('#error-message').hide("slow");
  }, 4000);*/
  $('.close-error-message').click(function() {
    $(this).parent().hide("slow");
  });
});
</script>
<div id="error-message" class="message error alert alert-danger">
  <span class="close-error-message">x</span>
  <?= h($message) ?>
</div>
