<?php

namespace Mizi;

use Mizi\Router\TraitRouterAction;
use Mizi\Router\TraitRouterData;
use Mizi\Router\TraitRouterMiddleware;
use Mizi\Router\TraitRouterUtil;

abstract class Router
{
    use TraitRouterUtil;
    use TraitRouterData;
    use TraitRouterMiddleware;
    use TraitRouterAction;

    protected static $group = [];

    /** Resolve a URL atual em uma rota registrada */
    static function solve()
    {
        $response = null;

        $uri = Request::path();

        self::applyModMiddlewaresURI($uri);

        self::organize(self::$routes);

        $route = self::getRouteMatch($uri);

        if (!is_null($route)) {
            self::setData(self::$routes[$route], $uri);
            $response = self::$routes[$route];
        }

        $response = self::$middlewareQueue->run(self::getAction($response));

        // $response = self::$middleware->run(self::getCallableResponse($response));

        // $response = is_class($response, Response::class) ? $response : new Response($response);

        // if ($send) $response->send();

        return $response;
    }

    /** Adiciona uma rota a lista de interpretações */
    static function add(string $route, mixed $response): void
    {
        $route = [...self::$group, $route];
        $route = implode('/', $route);

        list($template, $params) = self::explodeRoute($route);
        if (self::checkValidRoute($template)) {
            self::$routes[$template] = [
                'route' => $route,
                'template' => $template,
                'response' => $response,
                'params' => $params
            ];
        }
    }

    /** Adiciona varias rotas dentro de um grupo de rotas */
    static function group(string $group, callable $addRoutesCallable): void
    {
        self::$group[] = $group;
        $addRoutesCallable();
        array_pop(self::$group);
    }
}