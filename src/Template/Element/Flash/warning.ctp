<?php
/** 
* Companee :    warning (https://www.companee.it)
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
  setTimeout(function(){
    $('#warning-message').hide("slow");
  }, 4000);
});
</script>
<div id="warning-message" class="message warning alert alert-warning"><?= h($message) ?></div>
