<?php

namespace Peresmishnyk\Task\Services;

class URLGenerator
{
    protected $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function route(string $name, array $params = [])
    {
        $routes = array_filter($this->routes, function ($el) use ($name) {
            return $el['name'] == $name;
        });
        $route = count($routes) == 1 ? array_pop($routes) : null;

        if (!is_null($route)) {
            return preg_replace_callback("/{(?'name'\w+)[^}]+}/", function ($matches) use ($params) {
                return $params[$matches['name']];
            }, $route['route']);
        }
    }
}