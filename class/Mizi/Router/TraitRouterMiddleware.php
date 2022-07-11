<?php

namespace Mizi\Router;

use Mizi\Middleware\InstanceMiddlewareQueue;

trait TraitRouterMiddleware
{
    use TraitRouterUtil;

    protected static ?InstanceMiddlewareQueue $middlewareQueue;
    protected static array $middlewareRoutes = [];

    /** Registra middlewares para serem chamadas antesde de uma ou mais rotas */
    static function middleware(mixed $routes, mixed $middlewares = [])
    {
        if (func_num_args() == 1) {
            $middlewares = $routes;
            $routes = '';
        }

        $routes = is_array($routes) ? $routes : [$routes];

        $and = [];
        $or = [];

        foreach ($routes as $route) {
            if (!str_ends_with($route, '/') && !str_ends_with($route, '...'))
                $route = "$route/...";

            list($template) = self::explodeRoute($route);

            if (self::checkValidRoute($template)) {
                if (str_starts_with($template, '/!')) {
                    $template = substr($template, 2);
                    $and[] = "/$template";
                } else {
                    $or[] = $template;
                }
            }
        }

        self::$middlewareRoutes[] = [$and, $or, $middlewares];
    }

    /** Adiciona as middlewares correspondentes a rota atual */
    protected static function applyModMiddlewaresURI($uri): void
    {
        self::$middlewareQueue = self::$middlewareQueue ?? new InstanceMiddlewareQueue();

        foreach (self::$middlewareRoutes as $item) {
            list($and, $or, $middlewares) = $item;

            $check_and = true;

            while ($check_and && count($and)) {
                $template = array_shift($and);
                $check_and = !self::checkRouteMatchUri($template, $uri);
            }

            $check_or = count($or) ? false : true;

            while (!$check_or && count($or)) {
                $template = array_shift($or);
                $check = self::checkRouteMatchUri($template, $uri);
                $check_or = boolval($check_or || $check);
            }

            if ($check_and && $check_or)
                self::$middlewareQueue->mod($middlewares);
        }
    }
}