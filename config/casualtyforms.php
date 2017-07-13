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
        'prefix' => 'CF',
        'type' => '.jpg'
    ),

    // 15 mins, converted to seconds.
    'timeoutLimit' => 15 * 60,

    // Survey links and thresholds.
    'surveys' => array(
        'sessions' => array(
            2 => 'https://www.surveymonkey.co.uk/r/casualtyformsecondsession'
        ),
        'transcriptions' => array(
            100 => 'https://www.surveymonkey.co.uk/r/100casultyforms'
        )
    ),

];
