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