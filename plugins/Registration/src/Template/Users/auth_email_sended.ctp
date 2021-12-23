<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

echo $this->Html->css('Registration.style');

?>
<div class="auth-email-sended">
    <?php echo $this->Html->image('Registration.mail_sended.png'); ?>
    <br/>
    <span class="email-sended"><?=$emailSended?></span>

    <p class="msg1">È stata inviata un'e-mail per la conferma del tuo profilo</p>
    <p class="msg2">Fai clic sul collegamento riportato nell'email per confermare ed abilitare la tua utenza.</p>
    <p class="msg2">Se non vedi l'email, verifica nella cartella della posta indesiderata.</p>
    <div>
        <a href="<?=Router::url('/')?>">
            <button class="btn btn-success btn-block btn-flat" >Torna alla Home</button>
        </a>
        <a href="<?=Router::url('/registration/home/login')?>">
            <button class="btn btn-success btn-block btn-flat" >Accedi</button>
        </a>
    </div>
</div>
