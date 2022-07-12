<?php

namespace Mizi\Response;

class InstanceResponseJson extends InstanceResponse
{
    /** Prepara o conteúdo da resposta */
    protected function prepareContent(): void
    {
        $this->contentType('json');

        if (!is_json($this->content))
            $this->content(json_encode($this->content));
    }
}