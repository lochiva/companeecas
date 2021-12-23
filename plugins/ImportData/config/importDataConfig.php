<?php
return [
    /*
     * Plugin configuration
     */
    'importDataConfig' => [
        'PluginName'   =>  'ImportData',
		'Tables' => [
			[
				'tableName' => 'cespiti',
				'fieldsLocked' => ['id','cancellato','created','modified']
			],
			[
				'tableName' => 'documents',
				'fieldsLocked' => ['id','last_saved','created','modified']
			],
			[
				'tableName' => 'testUploadData',
				'fieldsLocked' => ['id','created','modified']
			],
		],
		'filesPath' => ROOT.'/uploadedFiles',
		'filterLabels' => [
			'0' => 'Trasforma in intero',
			'1' => 'Trasforma in decimale',
			'2' => 'Aggiungi prefisso',
			'3' => 'Estrai anno da data',
			'4' => 'Converti data gg-mm-aaaa -> aaaa-mm-gg',
		]
    ],
];
