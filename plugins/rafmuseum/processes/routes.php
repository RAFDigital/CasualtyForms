<?php

Route::post('feedback', function () {
    $data = json_decode(post('data'));

    // Get the message and image.
    $message = $data[0]['Issue'];
    $image = $data[1];

    return $message;
});