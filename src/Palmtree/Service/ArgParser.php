<?php

namespace Palmtree\Service;

class ArgParser
{
    protected $args;
    protected $object;

    public function __construct($args)
    {
        $this->args = $args;
    }

    public function parseSetters($object)
    {
        $callback = [$object];
        foreach ($this->args as $key => $value) {
            $method      = 'set' . ucfirst($key);
            $callback[1] = $method;
            if (is_callable($callback)) {
                $object->$method($value);
            }
        }
    }
}
