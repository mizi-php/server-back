<?php

namespace Mizi\Router;

trait TraitRouterUtil
{
    protected static $routes = [];

    /** Retorna a rota registrada que corresponde a URL atual */
    protected static function getRouteMatch(array $uri): ?string
    {
        foreach (array_keys(self::$routes) as $route)
            if (self::checkRouteMatchUri($route, $uri))
                return $route;

        return null;
    }

    /** Verifica se uma rota é compativel com uma lista de caminhos */
    protected static function checkRouteMatchUri(string $route, array $uri): bool
    {
        $route = trim($route, '/');
        $route = explode('/', $route);

        while (count($route)) {
            $esperado = array_shift($route);
            $recebido = array_shift($uri) ?? '';

            if ($recebido != $esperado) {
                if (is_blank($recebido))
                    return $esperado == '...';

                if ($esperado != '#' && $esperado != '...')
                    return false;
            }

            if ($esperado == '...' && count($uri))
                $route[] = '...';
        }

        if (count($uri))
            return false;

        return true;
    }

    /** Organiza um array de rotas para interpretação */
    protected static function organize(array &$array, bool $order = true): void
    {
        uksort($array, function ($a, $b) {
            if (substr_count($a, '/') != substr_count($b, '/'))
                return substr_count($b, '/') <=> substr_count($a, '/');

            $arrayA = explode('/', $a);
            $arrayB = explode('/', $b);
            $na = '';
            $nb = '';
            $max = max(count($arrayA), count($arrayB));

            for ($i = 0; $i < $max; $i++) {
                $na .= ($arrayA[$i] ?? '#') == '#' ? '1' : (($arrayA[$i] ?? '') == '...' ? '2' : '0');
                $nb .= ($arrayB[$i] ?? '#') == '#' ? '1' : (($arrayB[$i] ?? '') == '...' ? '2' : '0');
            }

            $result = intval($na) <=> intval($nb);

            if ($result)
                return $result;

            $result = count($arrayA) <=> count($arrayB);

            if ($result)
                return $result * -1;

            $result = strlen($a) <=> strlen($b);

            if ($result)
                return $result * -1;
        });

        if (!$order)
            $array =  array_reverse($array);
    }

    /** Explode uma rota em um array de template e params */
    protected static function explodeRoute(string $route): array
    {
        $template = self::clearRoute($route);

        $params = [];
        $template = explode('/', $template);

        foreach ($template as $n => $param) {
            if (str_starts_with($param, '[#')) {
                $template[$n] = '#';
                $params[] = substr($param, 2, -1);
            }
        }

        $template = implode('/', $template);

        return [$template, $params];
    }

    /** Limpa um template de rota */
    protected static function clearRoute(string $template): string
    {
        $template = "$template/";

        $template = str_replace('[', '[#', $template);
        $template = str_replace_all('[##', '[#', $template);

        $template = str_replace_all(['...', '.../', '......'], '/...', $template);

        $template = str_replace_all('//', '/', "/$template");
        return $template;
    }

    /** Verifica se um template de rota é válido */
    protected static function checkValidRoute(string $template): bool
    {
        $nMore = substr_count($template, '...');
        return boolval($nMore == 0 || ($nMore == 1 && str_ends_with($template, '...')));
    }
}