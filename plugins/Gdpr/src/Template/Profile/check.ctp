<?php
/**
* Gdpr is a plugin for manage attachment
*
* Companee :    Check  (https://www.companee.it)
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
    use Cake\Routing\Router;
?>
<?php if(!empty($contact)){ ?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div id="box-check-data" class="box">
                    <div class="box-header">
                        <i class="fa fa-list-ul"></i>
                        <h3 class="box-title">Verifica dati</h3>
                        <div class="modal-body">
                            <form class="form-horizontal" id="formContact" enctype="multipart/form-data">
                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label required" for="inputCognome">Cognome</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Cognome" name="cognome" id="inputCognome" class="form-control required" value="<?= $contact['cognome'] ?>">
                                        </div>
                                    </div>
                                    <div class="input">
                                        <label class="col-sm-2 control-label required" for="inputNome">Nome</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Nome" name="nome" id="inputNome" class="form-control required" value="<?= $contact['nome'] ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label " for="inputIndirizzo">Indirizzo</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Indirizzo" name="indirizzo" id="inputIndirizzo" class="form-control" value="<?= $contact['indirizzo'] ?>">
                                        </div>
                                    </div>
                                    <div class="input">
                                        <label class="col-sm-2 control-label " for="inputNumCivico">Numero Civico</label>
                                        <div class="col-sm-2">
                                            <input type="text" placeholder="Numero Civico" name="num_civico" id="inputNumCivico" class="form-control" value="<?= $contact['num_civico'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label " for="inputProvincia">Provincia</label>
                                        <div class="col-sm-4">
                                            <select type="text" placeholder="Provincia" name="provincia" id="inputProvincia" class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input">
                                        <label class="col-sm-2 control-label " for="inputComune">Comune</label>
                                        <div class="col-sm-4">
                                            <select type="text" placeholder="Comune" name="comune" id="inputComune" class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                </div>     
                                        
                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label " for="inputCap">Cap</label>
                                        <div class="col-sm-2">
                                            <select type="text" placeholder="Cap" name="cap" id="inputCap" class="form-control select2" >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2"></div>                            
                                    <div class="input">
                                        <label class="col-sm-2 control-label" for="inputNazione">Nazione</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Nazione" name="nazione" id="inputNazione" class="form-control" value="<?= $contact['nazione'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label" for="inputTelefono">Telefono</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Telefono" name="telefono" id="inputTelefono" class="form-control" value="<?= $contact['telefono'] ?>">
                                        </div>
                                    </div>
                                    <div class="input">
                                        <label class="col-sm-2 control-label" for="inputCell">Cellulare</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Cellulare" name="cellulare" id="inputCellulare" class="form-control" value="<?= $contact['cellulare'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label" for="inputFax">Fax</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Fax" name="fax" id="inputFax" class="form-control" value="<?= $contact['fax'] ?>">
                                        </div>
                                    </div>
                                    <div class="input">
                                        <label class="col-sm-2 control-label required" for="inputEmail">Email</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Email" name="email" id="inputEmail" class="form-control required" value="<?= $contact['email'] ?>" required>
                                            <span id="invalid_email_message"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label" for="inputSkype">Contatto Skype</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Contatto Skype" name="skype" id="inputSkype" class="form-control" value="<?= $contact['skype'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <div class="input">
                                        <label class="col-sm-2 control-label" for="inputCf">Codice Fiscale</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Codice Fiscale" name="cf" id="inputCf" max-length="16" class="form-control" value="<?= $contact['cf'] ?>">
                                            <span id="invalid_cf_message"></span>
                                        </div>
                                    </div>
                                    <div class="input">
                                        <label class="col-sm-2 control-label required" for="inputRuolo">Ruolo</label>
                                        <div class="col-sm-4">
                                            <select name="id_ruolo" id="inputRuolo" class="form-control required" >
                                                <?php foreach ($ruoli as $key => $ruolo) { ?>
                                                    <?php if($ruolo->id == $contact['id_ruolo']){ ?>
                                                    <option value="<?=$ruolo->id?>" selected><?=$ruolo->ruolo?></option>
                                                    <?php }else{ ?>
                                                    <option value="<?=$ruolo->id?>"><?=$ruolo->ruolo?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h3 class="text-center">Informativa privacy</h3>

                                <input type="checkbox" name="read_privacy" id="checkReadPrivacy" <?php if($contact['read_privacy']){echo 'checked';} ?> /> Compilando la presente dichiaro di aver letto attentamente l'<b>Informativa Privacy</b> ai sensi del combinato disposto di cui al D.lgs.n.196/2003 e del Regolamento UE nr. 679/2016, e di aver ricevuto copia della predetta informativa. <a href="#" data-toggle="modal" data-target="#modalPrivacyPolicy">Clicca qui per visualizzarla</a><br /><br />
                                <input type="checkbox" name="accepted_privacy" id="checkAcceptedPrivacy" <?php if($contact['accepted_privacy']){echo 'checked';} ?> /> Consento il trattamento dei miei dati personali con le modalità e per le finalità indicati nell'informativa.<br /><br />
                                <input type="checkbox" name="marketing_privacy" id="checkMarketingPrivacy" <?php if($contact['marketing_privacy']){echo 'checked';} ?> /> Consento il trattamento dei miei dati personali per le FINALITÀ DI MARKETING.<br /><br />
                                <input type="checkbox" name="third_party_privacy" id="checkThirdPartyPrivacy" <?php if($contact['third_party_privacy']){echo 'checked';} ?> /> Consento, sempre per FINALITÀ DI MARKETING, la comunicazione dei miei dati personali a terzi partner commerciali del Titolare del trattamento.<br /><br />
                                <input type="checkbox" name="profiling_privacy" id="checkprofilingPrivacy" <?php if($contact['profiling_privacy']){echo 'checked';} ?> /> Consento il trattamento dei miei dati personali per le FINALITÀ DI PROFILAZIONE.<br /><br />
                                <input type="checkbox" name="spread_privacy" id="checkSpreadPrivacy" <?php if($contact['spread_privacy']){echo 'checked';} ?> /> Consento la comunicazione dei miei dati limitatamente agli ambiti ed agli organi specificati nell'informativa.<br /><br />
                                <!-- <input type="checkbox" name="notify_privacy" id="checkNotifyPrivacy" <?php if($contact['notify_privacy']){echo 'checked';} ?> /> Avvisami in caso di modifica dei dati appartenenti all'indirizzo <span class="emailForPrivacy" ></span><br /><br /> -->

                            </form>
                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="saveContact" >Salva</button>
                            </div>                       
                        </div>                      
                    </div> 
                </div> 
            </div> 
        </div> 
    </section>

    <?= $this->element('Gdpr.modal_privacy_policy') ?>
<?php }else{ ?>
    <section class="content">
        <div id="box-check-data-success" class="box">
            <div class="box-header">
                <div class="div-success">
                    <i class="fa fa-thumbs-down error-thumbsdown"></i>
                    <h2>Errore. Il token è scaduto.</h2>
                    <h3>Iniziare una nuova procedura per la verifica dei dati accedendo alla <a href="<?= Router::url('/registration/home/login') ?>">home</a>.</h3>
                </div>                      
            </div> 
        </div> 
    </section>
<?php } ?>