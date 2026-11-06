<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tasa BCV (Dólar Venezuela)
    |--------------------------------------------------------------------------
    |
    | Servicio de tasas de cambio oficiales del BCV. La tasa se consulta y
    | se almacena en caché durante el día para evitar consultas repetidas.
    | Si la API no responde, se usa la tasa de respaldo configurada.
    |
    */

    'bcv' => [
        'url' => env('BCV_RATES_URL', 'https://rates.dolarvzla.com/v1/dollar?page=bcv'),
        'fallback_url' => env('BCV_RATES_FALLBACK_URL', 'https://pydolarvef.org/api/v1/dollar'),
        'fallback_rate' => (float) env('BCV_FALLBACK_RATE', 36.50),
        'cache_ttl' => (int) env('BCV_CACHE_TTL', 60 * 60),
    ],

];
