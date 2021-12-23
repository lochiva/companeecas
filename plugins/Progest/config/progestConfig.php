<?php
return [
    /*
     * Plugin configuration
     */
    'documentConfig' => [
        'PluginName'   =>  'Progest',

    ],
    /*
     * Example overwrite elements and views
     */
    'custom' => [
      'elements' => ['sidebar' => 'Progest.custom/sidebar-progest','footer'=>'Progest.custom/footer-progest'],
      'views' => [ 'Aziende.Orders.index' => 'Progest.Custom/Aziende/orders_index' ],
      'words' => [
        'azienda' => 'committente','aziende' => 'committenti',
        'Azienda' => 'Committente','Aziende' => 'Committenti',
        'Elenco delle Aziende' => 'Elenco dei Committenti',
        'Nuova' => 'Nuovo' , 'Ordini' => 'Buoni d\'ordine',
      ]
    ],
    'localconfig' => [
        'PluginUsed' =>[
            'Progest' => ['label' => 'Progest' , 'icon' => 'fa fa-bell', 'controllers' => [
              'person_types' => 'Tipi di Persone' ,'invoice_types' => 'Tipi di Fatturazione',
              'grado_parentela' => 'Gradi di parentela', 'services' => 'Servizi', 'skills'=>'Competenze',
              'categories' => 'Categorie Servizi'
              ]]
        ],
        'nammingEntity' => [
            'progest_person_types' => 'un tipo di persona', 'progest_people' => 'una persona',
            'progest_invoice_types' => 'un tipo di fatturazione', 'progest_familiari' => 'un familare o riferimento',
            'progest_services' => 'un servizio',
			'progest_activities' => 'un\'attività del servizio'
        ]
    ]
];
