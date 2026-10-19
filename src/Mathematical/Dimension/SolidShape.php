<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

abstract readonly class SolidShape extends Shape
{
    abstract public function surface(): Number;

    abstract public function volume(): Number;

    public function surfaceArea(): Number
    {
        return $this->surface();
    }
}
