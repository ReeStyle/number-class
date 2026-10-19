<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

abstract readonly class PlaneShape extends Shape
{
    abstract public function area(): Number;

    abstract public function perimeter(): Number;

    public function surface(): Number
    {
        return $this->area();
    }
}
