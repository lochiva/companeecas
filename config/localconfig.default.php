<?php
return [
    /*
     * my configuration
     */
    'localconfig' => [
        'ApplicationName'   =>  'Application Default',
        'AdminControllers' => [
            //"Groups" => ['label' => 'Gruppi' , 'icon' => 'glyphicon glyphicon-briefcase'],
        ],
        'report_invii_actions' => ['inviiUnico','inviiCausali','inviiUnicoEnc','inviiUnicoSc'],
		'SecretKey' => 'stringa segreta per token di sicurezza',
		'GoogleApiKey' => 'AIzaSyB6eO4MyYwdnAy21adV0imJjgndoyKWLO8',
		'HttpsEnabled' => true,
    ]
];