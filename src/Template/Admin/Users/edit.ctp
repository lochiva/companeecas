<?php
use Cake\Routing\Router;
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
    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-log-in"></i> Gestione Credenziali di accesso</h1>
    <h3>Da questa pagina è possibile gestire le credenziali di accesso al portale e modificare la password.</h3>
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
        <?php echo $this->Form->input('role', ['options' => ['admin' => 'Admin', 'ente' => 'Ente']]);
            echo $this->Form->input('level',['type' => 'number']);
            echo $this->Form->input('auth_email', ['type' => 'checkbox', 'label' => ['text' => "Autenticato"]]);
        ?>
    </fieldset>
    <a class="btn btn-danger delete-user" href="<?=Router::url('/admin/users/delete/' . $user->id)?>">Elimina</a>
    <a class="btn btn-warning" href="<?=Router::url('/admin/users')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class'=> 'btn btn-success', 'id' => 'btnSave']) ?>
    
    <?= $this->Form->end() ?>
</div>