<?php

return [
    \Peresmishnyk\Task\Services\Router::class => [
        'constructParams' => [
            config('route')
        ]
    ],
    \Peresmishnyk\Task\Services\URLGenerator::class => [
        'constructParams' => [
            config('route')
        ]
    ],
    \Peresmishnyk\Task\Services\Renderer::class => [
        'constructParams' => ['twig']
    ],
    PDO::class => [
        'constructParams' => ['sqlite:' . app_path('../storage/db')]
    ],
    \Peresmishnyk\Task\Services\PhoneParser::class => [
        'constructParams' => [
            config('app')['storage_dir'] . DIRECTORY_SEPARATOR . 'countryInfo.txt'
        ]
    ],
    \Peresmishnyk\Task\Services\IpParser::class => [
        'constructParams' => [
            'ipstack'
        ]
    ],
    \Peresmishnyk\Task\Services\FileStorage::class => [
        'constructParams' => [
            storage_path('uploads')
        ]
    ],
];