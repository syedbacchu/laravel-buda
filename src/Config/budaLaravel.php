<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Buda Api Requirements
    |--------------------------------------------------------------------------
    |
    | The buda api url
    | api version
    |
    */

    'BUDA_API_BASE_URL' => env('BUDA_API_BASE_URL') ?? "https://www.buda.com/api/",
    'BUDA_API_VERSION' => env('BUDA_API_VERSION') ?? 'v2',
    'BUDA_API_KEY' => env('BUDA_API_KEY') ?? 'budaapikey',
    'BUDA_API_SECRET' => env('BUDA_API_SECRET') ?? 'budaapisecret',
];
