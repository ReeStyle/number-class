<?php

namespace ReeStyleIt\NumberClass;

use ReeStyleIt\NumberClass\Exceptions\MustBeNumberException;

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
            throw new MustBeNumberException();
        }
    }
}
