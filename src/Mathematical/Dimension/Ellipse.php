<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Mathematical\Geometry;
use ReeStyleIt\NumberClass\Number;

readonly class Ellipse extends PlaneShape
{
    public function __construct(public int|float $semiAxisA, public int|float $semiAxisB)
    {
        self::assertLengths($semiAxisA, $semiAxisB);
    }

    public function area(): Number
    {
        return $this->result(new Geometry($this->semiAxisA)->ellipseSurface($this->semiAxisB)->get());
    }

    public function perimeter(): Number
    {
        return $this->result(new Geometry($this->semiAxisA)->ellipseCircumference($this->semiAxisB)->get());
    }

    public function circumference(): Number
    {
        return $this->perimeter();
    }
}
