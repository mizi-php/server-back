<?php

namespace Mizi;

abstract class Request
{
    protected static $method;
    protected static $relativeMethod;

    protected static $header;

    protected static $ssl;
    protected static $host;

    protected static $path;
    protected static $lockPath;

    protected static $query;
    protected static $data;

    protected static $files;

    /** Retorna o metodo da requisição atual */
    static function method(): string
    {
        self::$method = self::$method ?? self::current_method();

        return self::$method;
    }

    /** Retorna o metodo da requisição atual */
    static function relativeMethod(): string
    {
        self::$relativeMethod = self::$relativeMethod ?? self::current_relativeMethod();

        return self::$relativeMethod;
    }

    /** Retorna um ou todos os cabeçalhos da requisição atual */
    static function header(): mixed
    {
        self::$header = self::$header ?? self::current_header();

        if (func_num_args())
            return self::$header[func_get_arg(0)] ?? null;

        return self::$header;
    }

    /** Verifica se a requisição atual utiliza HTTPS */
    static function ssl(): bool
    {
        self::$ssl = self::$ssl ?? self::current_ssl();

        return self::$ssl;
    }

    /** Retorna o host usado na requisição atual */
    static function host(): string
    {
        self::$host = self::$host ?? self::current_host();

        return self::$host;
    }

    /** Retorna um ou todos os caminhos da URI da requisição atual */
    static function path(): mixed
    {
        self::$path = self::$path ?? self::current_path();

        if (func_num_args())
            return self::$path[func_get_arg(0)] ?? null;

        return self::$path;
    }

    /** Retorna um ou todos os caminhos protegidos da URI na requisição atual */
    static function lockPath(): mixed
    {
        self::$lockPath = self::$lockPath ?? self::current_lockPath();

        if (func_num_args())
            return self::$lockPath[func_get_arg(0)] ?? null;

        return self::$lockPath;
    }

    /** Retorna um ou todos os dados passados na QUERY GET da requisição atual */
    static function query(): mixed
    {
        self::$query = self::$query ?? self::current_query();

        if (func_num_args())
            return self::$query[func_get_arg(0)] ?? null;

        return self::$query;
    }

    /** Retorna um ou todos os dados enviados no corpo da requisição atual */
    static function data(): mixed
    {
        self::$data = self::$data ?? self::current_data();

        if (func_num_args())
            return self::$data[func_get_arg(0)] ?? null;

        return self::$data;
    }

    /** Retorna um o todos os arquivos enviados na requisição atual */
    static function file(): array
    {
        self::$files = self::$files ?? self::current_file();

        if (func_num_args())
            return self::$files[func_get_arg(0)] ?? [];

        return self::$files;
    }


    protected static function current_method(): string
    {
        return IS_TERMINAL ? 'TERMINAL' : strtoupper($_SERVER['REQUEST_METHOD']);
    }

    protected static function current_relativeMethod(): string
    {
        if (env('RELATIVE_METHOD') && IS_POST && self::data('_method')) {
            $method = strtoupper(self::data('_method'));
            unset(self::$data['_method']);
            return $method;
        } else {
            return self::method();
        }
    }

    protected static function current_header(): array
    {
        return getallheaders();
    }

    protected static function current_ssl(): bool
    {
        return boolval(env('FORCE_SSL') ?? strtolower($_SERVER['HTTPS'] ?? '') == 'on');
    }

    protected static function current_host(): string
    {
        return $_SERVER['HTTP_HOST'] ?? '';
    }

    protected static function current_path(): array
    {
        $path = urldecode($_SERVER['REQUEST_URI']);
        $path = explode('?', $path);
        $path = array_shift($path);
        $path = trim($path, '/');
        $path = explode('/', $path);

        $path = array_filter($path, fn ($path) => !is_blank($path));

        return array_slice($path, env('LOCK_PATH'));
    }

    protected static function current_lockPath(): array
    {
        $path = urldecode($_SERVER['REQUEST_URI']);
        $path = explode('?', $path);
        $path = array_shift($path);
        $path = trim($path, '/');
        $path = explode('/', $path);

        $path = array_filter($path, fn ($path) => !is_blank($path));

        return array_slice($path, 0, env('LOCK_PATH'));
    }

    protected static function current_query(): array
    {
        $query = [];

        $query = $_SERVER['REQUEST_URI'];
        $query = parse_url($query)['query'] ?? '';
        parse_str($query, $query);

        return $query;
    }

    protected static function current_data(): array
    {
        $data = [];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                if (!empty($_POST)) {
                    $data = $_POST;
                    break;
                }
            case 'GET':
            case 'PUT':
            case 'DELETE':
                $inputData = file_get_contents('php://input');
                if (is_json($inputData)) {
                    $data = json_decode($inputData, true);
                } else {
                    parse_str($inputData, $data);
                }
                break;
            default:
                $data = [];
        }

        return $data;
    }

    protected static function current_file(): array
    {
        $files = [];

        foreach ($_FILES as $name => $file) {
            if (is_array($file['error'])) {
                for ($i = 0; $i < count($file['error']); $i++) {
                    $files[$name][] = [
                        'name' => $file['name'][$i],
                        'full_path' => $file['full_path'][$i],
                        'type' => $file['type'][$i],
                        'tmp_name' => $file['tmp_name'][$i],
                        'error' => $file['error'][$i],
                        'size' => $file['size'][$i],
                    ];
                }
            } else {
                $files[$name][] = [
                    'name' => $file['name'],
                    'full_path' => $file['full_path'],
                    'type' => $file['type'],
                    'tmp_name' => $file['tmp_name'],
                    'error' => $file['error'],
                    'size' => $file['size'],
                ];
            }
        }

        return $files;
    }
}