<?php

namespace Peresmishnyk\Task\Services;

use Peresmishnyk\Interfaces\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    protected $dispatcher;
    protected $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
        $this->dispatcher = \FastRoute\simpleDispatcher(
            function (\FastRoute\RouteCollector $r) {
                foreach ($this->routes as $route) {
                    $r->addRoute($route['method'], $route['route'], $route['handler']);
                }
            });
    }

    public function dispatch()
    {
        $request = Request::createFromGlobals();

        // Fetch method and URI from somewhere
        $httpMethod = $request->getMethod();
        $uri = $request->getPathInfo();

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                $response = abort(404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                $response = abort(405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $content = forward_static_call($handler, $request, $vars);
                $response = $content instanceof Response ? $content : new Response($content);
                break;
        }
        $response->prepare($request);
        $response->send();
    }
}