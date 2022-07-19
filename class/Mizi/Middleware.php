<?php

namespace Mizi;

abstract class Middleware
{
    protected static array $registred = [];

    /** Registra uma middleware */
    static function register(string $middlewareName, mixed $middleware): void
    {
        self::$registred[$middlewareName] = $middleware;
    }

    /** Remove o registro de uma middleware */
    static function unregister(string $middlewareName): void
    {
        if (isset(self::$registred[$middlewareName]))
            unset(self::$registred[$middlewareName]);
    }

    /** Retorna uma middleware registrada */
    static function get(string $middlewareName): mixed
    {
        return self::$registred[$middlewareName] ?? null;
    }
}
