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
        'nammingEntity' => [
          'aziende'=>'un\'ente', 'calendar_events' => 'un evento', 'configurations' => 'una configurazione',
          'documents' => 'un documento', 'contatti' => 'un contatto', 'groups' => 'un gruppo', 'orders' => 'un ordine',
          'sedi' => 'una struttura', 'users' => 'un utente', 'tags' => 'un tag', 'scadenzario' => 'una scadenza',
          'documents_to_tags' => 'un tag di un documento' ,'notifications' => 'una notifica', 'invoices' => 'una fattura',
          'offers' => 'un offerta' , 'orders_status' => 'uno stato degli ordini', 'skills' => 'una competenza',
          'progest_people' => 'una persona', 'aziende_gruppi' => 'azienda gruppo', 'cespiti' => 'un cespite', 
          'log_file_upload' => 'log caricamenti con ImportData', 'invoices_articles' => 'gli articoli di una fattura attiva',
          'leads_ensembles' => 'gli ensemble delle domande', 'leads_questions' => 'le domande degli ensemble',
          'leads_interviews' => 'le interviste', 'leads_answers' => 'le risposte alle interviste',
          'surveys' => 'un questionario', 'surveys_interviews' => 'un\'intervista', 'surveys_answers' => 'una domanda del questionario',
          'surveys_chapters' => 'un capitolo del questionario', 'surveys_to_structures' => 'una relazione tra questionario e sede',
          'surveys_chapters_contents' => 'un testo standard per questionari', 'reports' => 'una segnalazione',
          'reports_victims' => 'l\'anagrafica di una vittima', 'reports_witnesses' => 'l\'anagrafica di un testimone',
          'guests' => 'un ospite'
        ],
        'excludeEntity' => [
          'calendar_events_frozen' ,'users_to_groups','groups'
	  ],
	  'SecretKey' => 'stringa segreta per token di sicurezza',
	  'GoogleApiKey' => 'AIzaSyB6eO4MyYwdnAy21adV0imJjgndoyKWLO8',
	  'HttpsEnabled' => true,
    ],
];
