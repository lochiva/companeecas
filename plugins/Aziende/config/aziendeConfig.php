<?php
return [
    /*
     * Plugin configuration
     */
    'documentConfig' => [
        'PluginName'   =>  'Aziende',

    ],
    /*
     * Example overwrite elements and views
     */
    'custom' => [
      'elements' => ['Aziende.modale_nuova_azienda' => 'Aziende.modale_nuova_azienda'],
      'views' => [ 'Aziende.Home.index' => 'Aziende.Home/index']
    ],
    'localconfig' => [
        'PluginUsed' =>[
            'Aziende' => [
                'label' => 'Enti' , 
                'icon' => 'fa fa-building', 
                'user_level' => 500, 
                'controllers' => [
                    //'orders_status' => 'Stati degli ordini',
                    'contatti_ruoli' => 'Ruoli dei contatti',
                    'sedi_tipi' => 'Tipi di strutture',
                    'aziende_gruppi' => 'Gruppi Enti',
                    'guests_exit_types' => 'Tipologie uscite ospiti',
                    'periods' => 'Periodi',
                    'costs_categories' => 'Categorie dei costi',
                    'status' => 'Status dei rendiconti',
                    'police_station_types' => 'Tipi di stazioni di polizia',
                    'police_stations' => 'Stazioni di polizia'
                ]
            ]
        ],
        'formTemplate' => [
            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}} class="form-control"/>',

            'select' => '<select class="form-control" name="{{name}}" {{attrs}} /> {{content}} </select>',

            'inputContainer' => 
                '<div class="col-md-6">
                    {{content}}
                </div>',

            'formGroup' => '{{label}} {{input}}',

            'selectContainer' => 
                '<div class="col-md-6">
                    {{content}}
                </div>',
        ]
    ]
];
