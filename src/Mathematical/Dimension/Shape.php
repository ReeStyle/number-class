<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Exceptions\GeometryException;
use ReeStyleIt\NumberClass\Number;

abstract readonly class Shape
{
    protected static function assertLengths(int|float ...$lengths): void
    {
        foreach ($lengths as $length) {
            if (! is_finite($length) || $length < 0) {
                throw GeometryException::invalidLength();
            }
        }
    }

    protected function result(int|float $value): Number
    {
        if (! is_finite($value)) {
            throw new GeometryException('Shape result exceeds the supported numeric range.');
        }

        return new Number($value);
    }
}
