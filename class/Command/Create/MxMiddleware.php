<?php

namespace Command\Create;

use Exception;
use Mizi\File;
use Mizi\Import;
use Mizi\Terminal;

abstract class MxMiddleware extends Terminal
{
    protected static function execute($middlewareName = '')
    {
        if (!$middlewareName)
            throw new Exception('Informe um nome para o arquivo de middleware');

        $tmp = $middlewareName;
        $tmp = explode('.', $tmp);
        $tmp = array_map(fn ($value) => ucfirst($value), $tmp);

        $class = "Mid" . array_pop($tmp);

        $namespace = implode('\\', $tmp);
        $namespace = trim("Middleware\\$namespace", '\\');

        $filePath = "class/" . str_replace('\\', '/', $namespace) . "/$class.php";

        if (File::check($filePath))
            throw new Exception("Arquivo [$filePath] já existe");

        $data = [
            '[#]',
            'name' => $middlewareName,
            'class' => $class,
            'namespace' => $namespace,
            'PHP' => '<?php',
        ];

        $base = path(dirname(__DIR__, 3) . '/library/template/middleware.txt');

        $content = Import::output($base, $data);

        File::create($filePath, $content);

        Terminal::show('Middleware [[#]] criado com sucesso.', $middlewareName);
    }
}