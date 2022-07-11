<?php

namespace Mizi\Middleware\Intarfece;

interface InterfaceMiddleware
{
    static function run(callable $next): mixed;
}