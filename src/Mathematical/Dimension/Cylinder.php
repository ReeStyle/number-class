<?php

namespace ReeStyleIt\NumberClass\Mathematical\Dimension;

use ReeStyleIt\NumberClass\Number;

/** A closed right circular cylinder. */
readonly class Cylinder extends SolidShape
{
    public function __construct(public int|float $radius, public int|float $height)
    {
        self::assertLengths($radius, $height);
    }

    public function surface(): Number
    {
        return $this->result((2 * M_PI) * ($this->radius * ($this->radius + $this->height)));
    }

    public function volume(): Number
    {
        if (($this->radius == 0) || ($this->height == 0)) {
            return $this->result(0.0);
        }

        return $this->result((M_PI * ($this->radius * $this->radius)) * $this->height);
    }
}
