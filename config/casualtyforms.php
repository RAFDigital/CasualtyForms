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
        50 => 'https://www.surveymonkey.com/#fiftyth',
    ),

    // Special database chars.
    'specialInputStates' => array(
        'illegible' => array(
            'label' => 'Illegible',
            'value' => '?',
            'datevalue' => '1 January 0001',
            'datevalraw' => '00001-01-01',
        ),
        'nodata' => array(
            'label' => 'N/A',
            'value' => 'N/A',
            'datevalue' => '2 January 0001',
            'datevalraw' => '00001-01-02',
        ),
    )

];
