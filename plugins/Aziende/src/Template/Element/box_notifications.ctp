<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    box notications  (https://www.companee.it)
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
?>
<div class="box box-warning collapsed-box box-notifications">
    <div class="box-header">
        <h3 class="box-title"><span class="badge bg-aqua notifications-count"><?= $notificationsCount ?></span> Notifiche <?= $enteType == 2 ? 'Emergenza Ucraina' : '' ?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div style="display: none;" class="box-body">
        <?php if (count($notifications) > 0) { ?>
            <?php foreach ($notifications as $n) { ?>
            <p><?= $n['message'] ?></p>
            <?php } ?>
            <a href="<?= Router::url('/aziende/guests/notifications/'.$enteType); ?>" class="btn btn-primary pull-right">
                Vai alla gestione delle notifiche
            </a>
        <?php } else { ?>
            Non ci sono notifiche.
        <?php } ?>
    </div>
</div>    