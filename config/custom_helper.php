<?php

use Cake\Core\Configure;

$__customWords = Configure::read('custom.words');

if (!function_exists('__c'))
{
    function __c($word)
    {
        global $__customWords;
        if(!empty($__customWords[$word])){
            return $__customWords[$word];
        }

        return htmlspecialchars($word);
    }
}
