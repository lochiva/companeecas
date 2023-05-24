<?php
return [
    /*
     * my configuration
     */
    'globalConfig' => [
        'nammingEntity' => [
			'aziende'=>'un\'ente', 'configurations' => 'una configurazione',
			'documents' => 'un documento', 'contatti' => 'un contatto', 'groups' => 'un gruppo', 
			'sedi' => 'una struttura', 'users' => 'un utente', 'tags' => 'un tag',
			'documents_to_tags' => 'un tag di un documento' ,'notifications' => 'una notifica', 
			'aziende_gruppi' => 'azienda gruppo', 
			'surveys' => 'un questionario', 'surveys_interviews' => 'un\'intervista', 'surveys_answers' => 'una domanda del questionario',
			'surveys_chapters' => 'un capitolo del questionario', 'surveys_to_structures' => 'una relazione tra questionario e sede',
			'surveys_chapters_contents' => 'un testo standard per questionari', 'reports' => 'una segnalazione','surveys_answer_data'=>'un dato' ,
			'guests' => 'un ospite','statement_company'=>'azienda ATI','presenze' => 'la presenza di un ospite',
			'presenze_upload'=>'il caricamento del file delle presenze','agreements'=>'convenzioni','agreements_companies'=>'aziende',
			'statements_status_history'=>'storico',
		],
		'excludeEntity' => [
			'users_to_groups','groups', 'guests_notifications'
		]
    ]
];
