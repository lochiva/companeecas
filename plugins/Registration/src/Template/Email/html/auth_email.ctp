<?php
use Cake\Routing\Router;
?>
<h1>Procedura di autenticazione della email</h1>
<hr>
<p>Gentile utente, grazie per esserti registrato. Clicca sul link seguente per confermare la tua email e completare la tua procedura di registrazione.</p>
<a href="<?=Router::url('/registration/users/authEmail/' . $authCode,true)?>">Conferma</a>
<p>Se non sei tu ad esserti registrato al nostro portale, puoi ignorare questo messaggio.</p>
