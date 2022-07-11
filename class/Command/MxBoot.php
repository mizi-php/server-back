<?php

namespace Command;

use Mizi\File;
use Mizi\Import;
use Mizi\Terminal;

abstract class MxBoot extends Terminal
{
    protected static function execute()
    {
        $fileName = "./boot.php";

        if (!File::check($fileName)) {
            $base = path(dirname(__DIR__, 2) . '/library/template/boot.txt');

            $base = Import::content($base);

            $content = prepare($base, ['PHP' => '<?php']);

            File::create($fileName, $content);

            Terminal::show('Arquivo de boot instalado');
        } else {
            Terminal::show('Arquivo de boot encontrado');
        }
    }
}