<?php
return [
    /*
     * my configuration
     */
    'globalConfig' => [
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
			'surveys_chapters_contents' => 'un testo standard per questionari', 'reports' => 'una segnalazione','surveys_answer_data'=>'un dato' ,
			'reports_victims' => 'l\'anagrafica di una vittima', 'reports_witnesses' => 'l\'anagrafica di un testimone',
			'guests' => 'un ospite','statement_company'=>'azienda ATI','presenze' => 'la presenza di un ospite',
			'presenze_upload'=>'il caricamento del file delle presenze'
		],
		'excludeEntity' => [
			'calendar_events_frozen' ,'users_to_groups','groups', 'guests_notifications'
		]
    ]
];
