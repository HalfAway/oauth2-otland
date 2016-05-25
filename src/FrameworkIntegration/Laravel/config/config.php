<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OtLand OAuth2
    |--------------------------------------------------------------------------
    |
    | This file stores the credentials required for the OtLand OAuth2 provider
    | to function properly.
    |
    */

    'client-id' => env('OTLAND_KEY'),
    'client-secret' => env('OTLAND_SECRET'),
    'redirect-uri' => env('OTLAND_REDIRECT'),

];
