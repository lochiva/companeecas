<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Print Cover  (https://www.companee.it)
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
    $( window ).on( "load", function() { 
        window.print();
    });
</script>
<div class="print-cover">
    <h1><?= $azienda->denominazione ?></h1>
    <p>
    <?php if(!empty($aziendaSede)){ ?>
        <b>Sede legale:</b> <?= $aziendaSede->indirizzo ?> <?= $aziendaSede->num_civico ?><br />
        <?= $aziendaSede->cap ?> <?= $aziendaSede->comune ?> <?php if(!empty($aziendaSede->provincia)){ echo '('.$aziendaSede->provincia.')'; } ?>
    <?php }else{ ?>
        <b>Sede legale:</b> -- non inserita --
    <?php } ?>
    </p>
    <p>
    <?php if(!empty($azienda->telefono)){ ?>
        <b>Tel:</b> <?= $azienda->telefono ?><br />
    <?php } ?>
    <?php if(!empty($azienda->fax)){ ?>
        <b>Fax:</b> <?= $azienda->fax ?>
    <?php } ?>
    </p>
    <?php if(!empty($azienda->email_info)){ ?>
    <p>
        <b>Info:</b> <?= $azienda->email_info ?>
    </p>  
    <?php } ?>
</div>