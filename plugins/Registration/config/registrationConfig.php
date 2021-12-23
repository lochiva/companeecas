<?php
return [
    /*
     * Plugin configuration
     */
    'registrationConfig' => [
        'PluginName'   =>  'Registration',
        'RegistrationType' => 1,     //0:Registrazione veloce|1:Registrazione con anagrafica
        'SenderEmail' => 'info@lochiva.com',
        'SenderAlias' => 'Lochiva',
        'AuthEmail' => false,        //true => la mail deve essere autenticata | false => l'utente è subito autenticato
        'RegistrationFrontEnd' => false
    ],
    'localconfig' => [
        'PluginUsed' =>[
          "Registration" => ['label' => 'Utenti' , 'icon' => 'glyphicon glyphicon-user']
        ]
    ]
];
