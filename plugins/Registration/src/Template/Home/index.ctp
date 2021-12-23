<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>
<h1>Questa è la Home del plugin registration</h1>
<div>
    <ul>
        <li>
            <a href="<?=Router::url('/registration/users/add')?>">Registrazione Utente</a>
        </li>
        <li>
            <a href="<?=Router::url('/registration/users/recoveryPassword')?>">Recupera password</a>
        </li>
        <li>
            <a href="<?=Router::url('/registration/users/edit')?>">Edit del proprio profilo (richiede login)</a>
        </li>
    </ul>
</div>
<div>
    <h3>Config da file (plugins/config/registrationConfig.php)</h3>
    <pre>
        <?php print_r(Configure::read('registrationConfig')); ?>
    </pre>
</div>