<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$registrationType = Configure::read('dbconfig.registration.REGISTRATION_TYPE');
$connectionTimetask = Configure::read('dbconfig.calendar.TIMETASK_CONNECTION');
?>
<?= $this->Html->css('Registration.password'); ?>
<?= $this->Html->script('Registration.password', ['block']); ?>
<script>
    $(document).ready(function(){
        $('.delete-user').click(function(e){

            if(!confirm("Si è sicuri di voler eliminare l'utente? L'operazione non sarà reversibile.")){
                e.preventDefault();
            }

        });

        $('#getTimetaskAnag').click(function(){
            var email = $('#emailTimetask').val();
            var id = $(this).attr('data-id');
            if(email != ''){
                $.ajax({
                    url: '<?=Router::url('/admin/registration/Users/getTimetaskAnag')?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {email: email, id: id}
                }).done(function(res) {

                    location.reload();

                }).fail(function(richiesta,stato,errori){
                    alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                });
            }else{
                alert('Inserisci un indirizzo email per poter caricare l\'anagrafica di timetask.');
            }
        })
    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-user"></i> Gestione Utenti</h1>
    <h3>Da questa pagina è possibile gestire tutti i dati di registrazione inerenti gli utenti.</h3>
</div>
<hr>
<div class="edit-user">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <?php echo $this->Form->input('username'); ?>
        <?php echo $this->Form->input('password', array('id' => 'newPassword', 'value' => '', 'required' => false, 'type' => 'password', 'autocomplete' => 'new-password')) ?>
        <div hidden class="col-md-12" id="divPasswordValidation">
            <b>La password deve contenere:</b><br>
            <span id="lowercaseValidation" class="invalid">almeno una lettera <b>minuscola</b></span><br>
            <span id="uppercaseValidation" class="invalid">almeno una lettera <b>maiuscola</b></span><br>
            <span id="numberValidation" class="invalid">almeno un <b>numero</b></span><br>
            <span id="specialValidation" class="invalid">almeno un <b>carattere speciale</b></span><br>
            <span id="lengthValidation" class="invalid">un minimo di <b>8 caratteri</b></span><br>
        </div>
        <?php echo $this->Form->input('confirm_password', array('id'=>'confirmPassword', 'value' => '', 'required' => false, 'type' => 'password', 'autocomplete' => 'new-password')) ?>
        <div class="col-md-12" id="divCheckPasswordMatch"></div>
        <?php echo $this->Form->input('email'); ?>
        <?php echo $this->Form->input('role', ['options' => ['ente' => 'Ente', 'admin' => 'Admin' ]]); ?>
        <?php echo $this->Form->input('level',['type' => 'number']); ?>
        <?php echo $this->Form->input('auth_email', ['type' => 'checkbox', 'label' => ['text' => "Autenticato"]]); ?>
        <?php /*if($connectionTimetask == '1'){ 
            echo $this->Form->input('timetask_token', ['label' => ['text' => "Token Timetask"]]);
        }*/ ?>
        <?php

        switch ($registrationType) {
            case '1':
                //Registrazione con anagrafica
                echo $this->Element('user_anag_consulenza');
            break;

            case '0':
            default:
                //Registrazione base solo con email, username e password.
            break;
        }

        ?>
    </fieldset>

    <?php if($connectionTimetask == '1'){ ?>
    <!--<hr>
    <p>Immettere l'email utilizzata su Timetask per recuperare l'anagrafica (è necessario aver gia salvato il token Timetask)</p>
    <?php if(empty($user->anagrafica_timetask)){ ?>
    <input type="email" id="emailTimetask" value="" placeholder="Email Timetask" /><button type="button" class="btn btn-default clearfloat" id="getTimetaskAnag" data-id="<?= $user->id ?>">Carica anagrafica Timetask</button>
    <?php }else{ ?>
    <input disabled type="email" id="emailTimetask" value="" placeholder="Email Timetask" /><button disabled type="button" class="btn btn-default clearfloat" id="getTimetaskAnag" data-id="<?= $user->id ?>" title="Anagrafica di Timetask già caricata">Carica anagrafica Timetask</button> <i class="glyphicon glyphicon-ok success-icon"></i>
    <?php } ?>
    -->
    <?php } ?>
    <br />
    <a class="btn btn-danger delete-user" href="<?=Router::url('/admin/registration/Users/delete/' . $user->id)?>">Elimina</a>
    <a class="btn btn-warning" href="<?=Router::url('/admin/registration/Users')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class'=> 'btn btn-success', 'id' => 'btnSave']) ?>

    <?= $this->Form->end() ?>
</div>
