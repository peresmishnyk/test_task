<?php

return [
    'services' => [
        'router' => \Peresmishnyk\Task\Services\Router::class,
        'url' => \Peresmishnyk\Task\Services\URLGenerator::class,
        'renderer' => \Peresmishnyk\Task\Services\Renderer::class,
        'data' => \Peresmishnyk\Task\Services\DataStorage::class,
        'parser' => \Peresmishnyk\Task\Services\DataParser::class,
        'filestorage' => \Peresmishnyk\Task\Services\FileStorage::class,
    ],
    'runtime_dir' => app_path('../runtime'),
    'storage_dir' => app_path('../storage')
];
