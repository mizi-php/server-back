<?php

namespace Mizi\Middleware\Intarfece;

use Closure;

interface InterfaceMiddleware
{
    static function run(Closure $next): mixed;
}