<?php

namespace Middleware\ServerBack;

use Mizi\Middleware\InterfaceMiddleware;
use Mizi\Response\InstanceResponse;

/** Middleware serverBack.Response */
abstract class MidResponse implements InterfaceMiddleware
{
    static function run(callable $next): mixed
    {
        $response = $next();
        if (!is_class($response, InstanceResponse::class))
            $response = new InstanceResponse($response);
        return $response;
    }
}