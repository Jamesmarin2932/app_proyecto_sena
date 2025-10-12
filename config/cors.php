<?php

return [

    // Rutas que aceptan CORS
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Métodos permitidos
    'allowed_methods' => ['*'],

    // Dominios permitidos (frontend)
    'allowed_origins' => [
        'https://maranube.vercel.app', // nuevo dominio // producción
        'https://proyecto-sena-facturacion-fron-git-41bf46-james-marins-projects.vercel.app', // preview
        'http://localhost:5173', // desarrollo local
    ],

    // Headers permitidos
    'allowed_headers' => ['*'],

    // Headers expuestos (opcional)
    'exposed_headers' => [],

    // Tiempo de cache de preflight
    'max_age' => 0,

    // Permitir cookies y autenticación
    'supports_credentials' => true,
];
