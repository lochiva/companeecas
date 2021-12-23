<?php

use Cake\Core\Configure;
use Cake\Log\Log;

Configure::load('Tooltility.tooltilityConfig', 'default');

// Inizializzo log tooltility
Log::config('tooltility', [
    'className' => 'File',
    'path' => LOGS,
    'file' => 'tooltility.log',
    'levels' => ['info', 'error'],
    'scopes' => ['tooltility']
]);