<?php

namespace Mizi\Router;

trait TraitRouterAction
{
    protected static function getAction($response): callable
    {
        return fn () => $response;
    }
}