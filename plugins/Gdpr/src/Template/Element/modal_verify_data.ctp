<?php
/**
* Gdpr is a plugin for manage attachment
*
* Companee :    Modal Verify Data  (https://www.companee.it)
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
