<?php

namespace Mizi\Router;

trait TraitRouterData
{
    protected static array $data = [];

    /** Define/Retorna um ou todos os dados fornecidos via URI */
    static function data()
    {
        if (func_num_args() > 1)
            self::$data[func_get_arg(0)] = func_get_arg(1);

        if (func_num_args())
            return self::$data[func_get_arg(0)] ?? null;

        return self::$data;
    }

    /** Define as variaveis interpretadas da URI */
    protected static function setData(array $route, array $uri): void
    {
        $template = $route['template'];
        $params = $route['params'];

        $template = trim($template, '/');
        $template = explode('/', $template);

        foreach ($template as $pos => $part) {
            if ($part != '...') {
                $value = array_shift($uri) ?? '';
                if ($part == '#') {
                    $name = array_shift($params) ?? '';
                    if ($name == '') {
                        self::$data[$pos] = $value;
                    } else {
                        self::$data[$name] = $value;
                        self::$data[$pos] = &self::$data[$name];
                    }
                }
            }
        }

        foreach ($uri as $param) {
            self::$data[] = $param;
        }
    }
}