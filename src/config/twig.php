<?php

return [
    'template_path' => app_path('Template'),
    'options' => [
        'cache' => config('app')['runtime_dir'] . DIRECTORY_SEPARATOR . 'template_cache',
        'debug' => true
    ]
];