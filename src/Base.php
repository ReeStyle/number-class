<?php

namespace ReeStyleIt\NumberClass;

use UnexpectedValueException;

abstract class Base
{
    protected readonly mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function get(): mixed
    {
        return $this->value;
    }

    protected function assertNumber(): void
    {
        if (!is_int($this->value) && !is_float($this->value)) {
            throw new UnexpectedValueException('Value must be an integer or float.');
        }
    }
}
