<?php

namespace Mizi\Router;

use Error;
use Exception;
use Mizi\Import;
use Mizi\Request;
use Mizi\Response\InstanceResponseFile;
use Mizi\Response\InstanceResponseRedirect;
use Mizi\Router;
use Mizi\View;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

trait TraitRouterAction
{
    protected static function getCall($response): callable
    {
        if (is_null($response))
            return fn () => self::call_null();

        if (is_closure($response))
            return fn () => self::call_closure($response);

        if (is_string($response)) {
            return match (substr($response, 0, 1)) {
                '#' => fn () => self::call_dbug(substr($response, 1)),
                '@' => fn () => self::call_action(substr($response, 1)),
                '!' => fn () => self::call_file(substr($response, 1)),
                '>' => fn () => self::call_redirect(substr($response, 1)),
                default => fn () => self::call_class($response),
            };
        }

        return fn () => $response;
    }

    protected static function call_null()
    {
        throw new Exception('Page not found', 404);
    }

    protected static function call_closure($response)
    {
        if (is_object($response)) {
            $params = self::getUseParams(new ReflectionMethod($response, '__invoke'));
        } else {
            $params = self::getUseParams(new ReflectionFunction($response));
        }
        return $response(...$params);
    }

    protected static function call_dbug($response)
    {
        return prepare($response, Router::data());
    }

    protected static function call_action($response)
    {
        View::mapPath('.', $response);

        $response = Import::return(path("$response/action.php"), Router::data());

        $response = $response ?? fn () => view(Router::data());

        if (is_closure($response))
            return self::call_closure($response);

        if (is_object($response)) {
            $method = strtolower(Request::method());

            if (!method_exists($response, $method))
                throw new Error("method [$method] not found", STS_METHOD_NOT_ALLOWED);

            $paramsMethod = self::getUseParams(new ReflectionMethod($response, $method));

            return $response->{$method}(...$paramsMethod);
        }

        return $response;
    }

    protected static function call_class($response)
    {
        $response = explode(':', $response);
        $class = array_shift($response);
        $method = array_shift($response) ?? strtolower(Request::method());

        $class = str_replace('\\', '/', $class);
        $class = str_replace_all('//', '/', $class);
        $class = trim($class, '/');
        $class = explode('/', $class);
        $class = array_map(fn ($v) => str_replace(' ', '', ucwords($v)), $class);
        $class = implode('\\', $class);

        if (!class_exists($class))
            throw new Error("class [$class] not found", STS_NOT_IMPLEMENTED);

        if (!method_exists($class, $method))
            throw new Exception("method [$method] not found", STS_METHOD_NOT_ALLOWED);

        $paramsConstruct = [];

        $paramsMethod = self::getUseParams(new ReflectionMethod($class, $method));

        if (method_exists($class, '__construct'))
            $paramsConstruct = self::getUseParams(new ReflectionMethod($class, '__construct'));

        $response = new $class(...$paramsConstruct);

        return $response->{$method}(...$paramsMethod);
    }

    protected static function call_file($response)
    {
        return new InstanceResponseFile($response);
    }

    protected static function call_redirect($response)
    {
        return new InstanceResponseRedirect($response);
    }

    /** Retorna os parametros que devem ser usados em um metodo refletido */
    protected static function getUseParams(ReflectionFunctionAbstract $reflection): array
    {
        $params = [];
        $data = Router::data();

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            if (isset($data[$name])) {
                $params[] = $data[$name];
            } else if ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new Error("parameter [$name] required", STS_BAD_REQUEST);
            }
        }

        return $params;
    }
}