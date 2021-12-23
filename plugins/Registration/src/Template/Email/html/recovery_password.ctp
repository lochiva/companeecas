<?php
use Cake\Routing\Router;
?>
<h1>Procedura di recupero password</h1>
<hr>
<p>Hai recentemente richiesto di reimpostare la password.<br />
Il tuo username è il seguente: <b><?php echo $user; ?></b><br />
Fai clic sul link in basso per iniziare il processo di ripristino password.</p>
<a href="<?=Router::url('/registration/users/newPassword/' . $recoveryCode,true)?>">Recupera password</a>
<p>Se non hai richiesto la modifica della password, puoi ignorare questo messaggio e la password resterà invariata.</p>
