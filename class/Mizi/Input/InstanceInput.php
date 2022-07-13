<?php

namespace Mizi\Input;

use Mizi\Input\InstanceInputField;
use Mizi\Input\TraitInputMessageError;
use Mizi\Request;

class InstanceInput
{

    use TraitInputMessageError;

    protected array $data = [];
    protected array $field = [];

    function __construct(?array $data = null)
    {
        $this->data = $data ?? Request::data();
    }

    /** Cria/Retorna um campo do input */
    function &field(string $name, ?string $alias = null): InstanceInputField
    {
        $alias = $alias ?? $name;

        if (!isset($this->field[$name]))
            $this->field[$name] = new InstanceInputField($alias, $this->data[$name] ?? null);

        return $this->field[$name];
    }

    /** Retorna um ou todos os valores do input */
    function data(?string $name = null)
    {
        if (!func_num_args())
            return array_map(fn ($i) => $i->get(), $this->field);

        return isset($this->field[$name]) ? $this->field[$name]->get() : null;
    }
}