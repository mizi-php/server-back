<?php

namespace Middleware;

use Mizi\Middleware\Intarfece\InterfaceMiddleware;

/** Middleware teste */
abstract class MidTeste implements InterfaceMiddleware
{
    static function run(\Closure $next): mixed
    {
        return strtolower($next());
    }
}