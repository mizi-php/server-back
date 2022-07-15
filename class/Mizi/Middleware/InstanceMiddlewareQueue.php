<?php

namespace Mizi\Middleware;

class InstanceMiddlewareQueue
{
    protected array $queue = [];

    /** Executa uma action após uma fila de middlewares */
    function run(mixed $action): mixed
    {
        if (!is_closure($action))
            $action  = fn () => $action;

        $this->add($action);

        $executeList = [...$this->queue, $action];

        return $this->execute($executeList);
    }

    /** Modifica as middlewares registradas */
    function mod(string|array|callable ...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if (is_array($middleware)) {
                $this->mod(...$middleware);
            } else {
                if (is_string($middleware) && str_starts_with($middleware, '!')) {
                    $this->remove(substr($middleware, 1));
                } else {
                    $this->add($middleware);
                }
            }
        }
    }

    /** Adiciona uma middleware na fila de execução */
    function add(string|array|callable ...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if (is_array($middleware)) {
                $this->add(...$middleware);
            } else {
                $this->queue[] = $middleware;
            }
        }
    }

    /** Remove uma middleware string da fila de execução */
    function remove(string|array ...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if (is_array($middleware)) {
                $this->remove(...$middleware);
            } else {
                foreach ($this->queue as $p => $v)
                    if ($v === $middleware)
                        unset($this->queue[$p]);
            }
        }
    }

    /** Chama a proxima mdidleware da fila ou retorna o resutado das middlewares */
    protected function execute(mixed &$middlewares): mixed
    {
        if (count($middlewares)) {
            $middleware = array_shift($middlewares);
            $middleware = $this->getCallable($middleware);
            $next = fn () => $this->execute($middlewares);
            return $middleware($next);
        }
        return null;
    }

    /** Retorna uma Callable de exeução de uma middleware */
    protected function getCallable(mixed $middleware)
    {
        if (is_closure($middleware))
            return $middleware;

        if (is_string($middleware)) {

            $middleware = $middleware;
            $middleware = explode('.', $middleware);
            $middleware = array_map(fn ($value) => ucfirst($value), $middleware);

            $middleware[] = "Mid" . array_pop($middleware);

            $middleware = implode('\\', $middleware);
            $middleware = trim("Middleware\\$middleware", '\\');

            if (class_exists($middleware))
                return fn ($next) => $middleware::run($next);

            $middleware = null;
        }

        if (is_null($middleware))
            return fn ($next) => $next();

        return fn ($next) => $middleware;
    }
}