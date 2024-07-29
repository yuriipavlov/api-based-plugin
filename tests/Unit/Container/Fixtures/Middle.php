<?php

namespace APIBasedPluginTests\Unit\Container\Fixtures;

class Middle
{
    public $inner;

    public function __construct(Inner $inner)
    {
        $this->inner = $inner;
    }
}
