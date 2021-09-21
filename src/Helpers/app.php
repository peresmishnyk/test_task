<?php

use Peresmishnyk\Task\Services\App;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!function_exists('app_path')) {
    function app_path($subpath = null)
    {
        return __DIR__ . DS . '..' . (is_null($subpath) ? '' : DS . str_replace('/', DS, $subpath));
    }
}

if (!function_exists('config_path')) {
    function config_path($key = null)
    {
        return app_path('config') . (is_null($key) ? '' : DS . $key . '.php');
    }
}

if (!function_exists('config')) {
    function config($key)
    {
        return require config_path($key);
    }
}

if (!function_exists('storage_path')) {
    function storage_path($key = null)
    {
        return config('app')['storage_dir'] . (is_null($key) ? '' : DS . $key);
    }
}

if (!function_exists('app')) {
    function app(): App
    {
        return App::getInstance();
    }
}

if (!function_exists('db')) {
    function db(): PDO
    {
        return app()->service('db');
    }
}


if (!function_exists('view')) {
    function view($template, $args = []): string
    {
        return app()->service('renderer')->render($template, $args);
    }
}

if (!function_exists('route')) {
    function route($name, $params = []): string
    {
        return app()->service('url')->route($name, $params);
    }
}

if (!function_exists('uuid_v4')) {
    function uuid_v4()
    {
        $data = openssl_random_pseudo_bytes(16, $secure);
        if (false === $data) {
            return false;
        }
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

if (!function_exists('abort')) {
    function abort($code, $message = 'something get wrong'): \Symfony\Component\HttpFoundation\Response
    {
        $content = view('exceptions.twig', [
            'message' => $message,
            'code' => $code
        ]);
        return new \Symfony\Component\HttpFoundation\Response($content, $code);
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $code = 301): \Symfony\Component\HttpFoundation\Response
    {
        return new \Symfony\Component\HttpFoundation\RedirectResponse($url, $code);
    }
}


