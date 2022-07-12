<?php

namespace Mizi\Response;

use Mizi\File;
use Mizi\Import;

class InstanceResponseFile extends InstanceResponse
{
    function __construct(mixed $filePath = null, ?int $status = null)
    {
        parent::__construct($filePath, $status);
        $this->cache(true);
    }

    /** Define o caminho do arquivo da resposta */
    function content(mixed $filePath): static
    {
        return parent::content($filePath);
    }

    /** Prepara o conteúdo da resposta */
    protected function prepareContent(): void
    {
        $file = $this->content;

        if (File::check($file)) {
            $this->content(Import::content($file));

            $ex = File::getOnly($file);
            $ex = explode('.', $ex);
            $ex = array_pop($ex);

            $this->contentType($ex);

            $this->downloadName = $this->downloadName ?? File::getOnly($file);
        } else {
            $this->content('Arquivo não encontrado');
            $this->status(STS_NOT_FOUND);
            $this->download(false);
        }
    }
}