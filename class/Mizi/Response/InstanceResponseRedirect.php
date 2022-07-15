<?php

namespace Mizi\Response;

class InstanceResponseRedirect extends InstanceResponse
{
    function __construct(mixed $url = null)
    {
        $this->content(url(...func_get_args()));
    }

    /** Prepara o conteúdo da resposta */
    protected function prepareContent(): void
    {
        $this->header('Location', $this->content);
        $this->content = '';
    }
}