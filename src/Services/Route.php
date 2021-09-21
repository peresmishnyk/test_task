<?php

namespace Peresmishnyk\Task\Services;

class Route
{
    protected static $allowed_method = ['get', 'post'];

    public static function __callStatic(string $name, $args)
    {
        if (in_array($name, static::$allowed_method)) {
            array_unshift($args, strtoupper($name));
            return forward_static_call_array([static::class, 'compact'], $args);
        }
    }

    protected static function compact(string $method, string $route, callable $handler, string $name): array
    {
        return compact('method', 'route', 'handler', 'name');
    }
}