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

    'imagefile' => array(
        'dir' => '/Forms',
        'prefix' => '_',
        'separator' => 'CF',
        'type' => '.jpg'
    ),

    // 15 mins, converted to seconds.
    'timeoutLimit' => 15 * 60

];
