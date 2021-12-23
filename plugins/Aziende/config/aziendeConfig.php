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
            'Aziende' => ['label' => 'Nodi' , 'icon' => 'fa fa-industry', 'controllers' => [
              //'orders_status' => 'Stati degli ordini',
              'contatti_ruoli' => 'Ruoli dei contatti',
              'sedi_tipi' => 'Tipi di sedi',
              'aziende_gruppi' => 'Gruppi Nodi'
              ]]
        ]
    ]
];
