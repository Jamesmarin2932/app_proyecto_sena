<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'https://proyecto-sena-facturacion-fronted.vercel.app',
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => ['/^https:\/\/.*\.vercel\.app$/'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];

