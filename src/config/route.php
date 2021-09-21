<?php

use Peresmishnyk\Task\Services\Route;

return [
    Route::get('/', [\Peresmishnyk\Task\Http\Controllers\MainController::class, 'index'], 'index'),
    Route::get('/demo', [\Peresmishnyk\Task\Http\Controllers\MainController::class, 'demo'], 'demo'),
    Route::get('/all', [\Peresmishnyk\Task\Http\Controllers\MainController::class, 'all'], 'all'),
    Route::get('/show/{uuid:[\w|-]+}', [\Peresmishnyk\Task\Http\Controllers\MainController::class, 'show'], 'show'),
    Route::post('/submit/file', [\Peresmishnyk\Task\Http\Controllers\SubmitController::class, 'upload'], 'upload'),
    Route::post('/submit/download', [\Peresmishnyk\Task\Http\Controllers\SubmitController::class, 'download'], 'download'),
    Route::post('/submit/plain', [\Peresmishnyk\Task\Http\Controllers\SubmitController::class, 'plain'], 'plain'),
];