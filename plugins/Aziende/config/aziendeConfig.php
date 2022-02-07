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
            'Aziende' => ['label' => 'Enti' , 'icon' => 'fa fa-building', 'controllers' => [
              //'orders_status' => 'Stati degli ordini',
              'contatti_ruoli' => 'Ruoli dei contatti',
              'sedi_tipi' => 'Tipi di strutture',
              'aziende_gruppi' => 'Gruppi Enti'
              ]]
        ]
    ]
];
