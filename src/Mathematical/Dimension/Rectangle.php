<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

readonly class Rectangle extends PlaneShape
{
    public function __construct(public int|float $length, public int|float $width)
    {
        self::assertLengths($length, $width);
    }

    public function area(): Number
    {
        return $this->result($this->length * $this->width);
    }

    public function perimeter(): Number
    {
        return $this->result(2 * ($this->length + $this->width));
    }

    public function diagonal(): Number
    {
        return $this->result(hypot($this->length, $this->width));
    }
}
