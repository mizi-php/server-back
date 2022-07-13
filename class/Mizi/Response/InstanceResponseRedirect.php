<?php

namespace Mizi\Response;

class InstanceResponseRedirect extends InstanceResponse
{
    function __construct(mixed $url = null)
    {
        $this->content(url(...$url));
    }

    /** Prepara o conteúdo da resposta */
    protected function prepareContent(): void
    {
        $this->header('Location', $this->content);
        $this->content = '';
    }
}