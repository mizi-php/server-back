<?php

namespace Mizi\Middleware;

interface InterfaceMiddleware
{
    static function run(callable $next): mixed;
}