<?php $this->Html->css('Gdpr.gdpr', ['block' => 'css']); ?>
<?php $this->Html->script('Gdpr.gdpr', ['block' => 'script']); ?>

<button type="button" class="btn btn-block btn-primary btn-flat open-overlay-gdpr" >Verifica i tuoi dati</button>

<div id="disabled_background"></div>
<div class="overlay-gdpr modal-lg" id="overlay_gdpr" >
    <div id="gdpr-loader" hidden><i class="fa fa-spinner fa-pulse fa-3x fa-fw"  ></i></div>
    <div class="overlay-gdpr-header">
        <button class="close close-overlay-gdpr" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="overlay-gdpr-title">Verifica i tuoi dati</h4>
    </div>
    <div class="overlay-gdpr-body">
        <div hidden id="response_verify_email"></div>
        <form class="form-horizontal">
            <div class="form-group no-margin-bottom">
                <div class="input">
                    <label class=" col-sm-2 control-label" for="gdpr_email">Email</label>
                    <div class="col-sm-7">
                        <input id="gdpr_email" type="email" name="gdpr_mail" class="form-control" max-length="100" placeholder="Inserire email da verificare" />
                        <span id="invalid_email_message"></span>
                    </div>
                </div>
                <div class="verify_button">
                    <button type="button" class="btn btn-primary" id="verify_email" >Verifica dati</button>
                </div>
            </div>
        </form>
        <br />
        <p>Verrà inviata una mail a questo indirizzo con un collegamento alla pagina dei relativi dati da verificare.</p>
    </div>
    <div class="overlay-gdpr-footer">
        <div class="pull-right">
            <button type="button" class="btn btn-default close-overlay-gdpr" >Chiudi</button>
        </div>
    </div>
</div>
