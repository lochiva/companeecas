<?php
return [
    /*
     * Plugin configuration
     */
    'documentConfig' => [
        'PluginName'   =>  'Surveys',

    ],
    /*
     * Example overwrite elements and views
     */
    'localconfig' => [
        'PluginUsed' =>[
            'Surveys' => [
                'label' => 'Modelli' , 
                'icon' => 'fa fa-list-alt', 
                'user_level' => 500, 
                'controllers' => [
                    'surveys_statuses' => 'Stati dei modelli',
                    'surveys_interviews_statuses' => 'Stati dei moduli',
                    'surveys_placeholders' => 'Segnaposto'
                ]
            ]
        ]
    ]
];
