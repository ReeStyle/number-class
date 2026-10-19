<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Number;

readonly class Circle extends PlaneShape
{
    public function __construct(public int|float $radius)
    {
        self::assertLengths($radius);
    }

    public function area(): Number
    {
        return $this->result(new Geometry($this->radius)->circleSurface()->get());
    }

    public function perimeter(): Number
    {
        return $this->result(new Geometry($this->radius)->circleCircumference()->get());
    }

    public function circumference(): Number
    {
        return $this->perimeter();
    }

    public function diameter(): Number
    {
        return $this->result(2 * $this->radius);
    }
}
