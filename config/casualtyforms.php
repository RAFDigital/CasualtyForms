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
        10 => 'https://www.surveymonkey.co.uk/r/casualtyformsecondsession',
        100 => 'https://www.surveymonkey.co.uk/r/100casultyforms',
    ),

];
