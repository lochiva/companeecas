<?php
/** 
* Companee :  pager   (https://www.companee.it)
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
<div id="pager" class="pager col-sm-6">
    <form>
        <i class="first glyphicon glyphicon-step-backward"></i>
        <i class="prev glyphicon glyphicon-backward"></i>
        <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
        <i class="next glyphicon glyphicon-forward"></i>
        <i class="last glyphicon glyphicon-step-forward"/></i>
        <select class="pagesize">
            <option selected="selected" value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
        </select>
    </form>
</div>