<?php

namespace Middleware\ServerBack;

use Error;
use Exception;
use Mizi\Middleware\InterfaceMiddleware;
use Mizi\Response\InstanceResponse;

/** Middleware serverBack.Error */
abstract class MidError implements InterfaceMiddleware
{
    static function run(callable $next): mixed
    {
        try {
            return $next();
        } catch (Exception $e) {
            return new InstanceResponse('Error ' . $e->getCode(), $e->getCode());
        } catch (Error $e) {
            return new InstanceResponse('Error desconhecido', STS_INTERNAL_SERVER_ERROR);
        }
    }
}