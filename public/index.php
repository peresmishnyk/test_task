<?php

// Composer autoload
require str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../vendor/autoload.php');

// Error handler
if (class_exists(\Whoops\Run::class)) {
    $whoops = new \Whoops\Run;
    $error_handler = new \Whoops\Handler\PrettyPageHandler;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
} else {
    error_reporting(0);
}

$app = app();
$response = $app->service('router')->dispatch();
echo $response;





