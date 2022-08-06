<?php

namespace Common;

class ClassName
{
    protected ?int $id;
    public ?string $name;



    public function __construct(int $id = null, string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return "Привет от " . $this->name . PHP_EOL;
    }
}
