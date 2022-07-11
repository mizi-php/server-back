<?php

namespace Command;

use Exception;
use Mizi\File;
use Mizi\Terminal;

abstract class MxServer extends Terminal
{
    protected static function execute($port = '3333', $file = 'boot.php')
    {
        if ($file == 'boot.php')
            Terminal::run('boot');

        if (!File::check($file))
            throw new Exception("file [$file] not found");
        Terminal::show('-------------------------------------------------');
        Terminal::show('| Iniciando servidor PHP');
        Terminal::show('| Acesse: [#]', "http://127.0.0.1:$port/");
        Terminal::show('| Use: [#] para finalizar o servidor', "CLTR + C");
        Terminal::show("| Escutando porta [#]", $port);
        Terminal::show('-------------------------------------------------');
        Terminal::show('');

        echo shell_exec("php -S 127.0.0.1:$port $file");
    }
}