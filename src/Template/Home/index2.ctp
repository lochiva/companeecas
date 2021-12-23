<?php
use Cake\Routing\Router;
?>
<div>
    <h1>Questa pagina richiede l'autenticazione per potervi accedere</h1>
    <ul>
        <li>
            <a href="<?=Router::url('/home/index');?>">Torna alla Home del sito</a>
        </li>
        <li>
            <a href="<?=Router::url('/users/add');?>">Pagina di registrazione di Front End</a><br/>
        </li>
        <li>
            <a href="<?=Router::url('/users/logout');?>">Pagina di logout</a><br/>
        </li>
    </ul>
</div>