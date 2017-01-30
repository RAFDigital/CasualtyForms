<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom application config items.
    |--------------------------------------------------------------------------
    |
    | Configuration vars for the Casualty Forms project.
    |
    */

    // Image file contruction info.
    'imagefile' => array(
        'dir' => '/Forms',
        'prefix' => '_',
        'separator' => 'CF',
        'type' => '.jpg'
    ),

    // 15 mins, converted to seconds.
    'timeoutLimit' => 15 * 60,

    // Survey links and thresholds.
    'surveys' => array(
        1 => 'https://www.surveymonkey.com/#first',
        20 => 'https://www.surveymonkey.com/#twentyth'
    ),

];
