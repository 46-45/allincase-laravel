<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Midtrans
    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY', ''),
        'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    ],

    // Firebase
    'firebase' => [
        'credentials_path' => env('FIREBASE_CREDENTIALS_PATH', './firebase-credentials.json'),
    ],

    // File Upload
    'upload' => [
        'max_file_size_mb' => env('MAX_FILE_SIZE_MB', 10),
    ],

    // Geocoding
    'geocoding' => [
        'provider' => env('GEOCODING_PROVIDER', 'nominatim'),
        'google_api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

];
